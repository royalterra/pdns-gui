<?php

/**
 * Domain actions.
 *
 * @package    symfony
 * @subpackage host
 * @author     Your name here
 * @version    SVN: $Id: actions.class.php 2692 2006-11-15 21:03:55Z fabien $
 */
class domainActions extends MyActions
{
  /**
   * Search and replace
   */
  public function executeReplace()
  {
    $c = new Criteria();
    $c->add(RecordPeer::TYPE, $this->getRequestParameter('search_type'));
    $c->add(RecordPeer::CONTENT, $this->getRequestParameter('search_content'));
    
    $replaced = '';
    
    foreach (RecordPeer::doSelect($c) as $record)
    {
      $replaced.= $record->getDomain()->getName()." ".$record->getType()." ".$record->getContent().' => ';
      $replaced.= $this->getRequestParameter('replace_type')." ";
      $replaced.= $this->getRequestParameter('replace_content')."<br/>";
      
      $record->setType($this->getRequestParameter('replace_type'));
      $record->setContent($this->getRequestParameter('replace_content'));
      $record->save();
    }
    
    if ($replaced)
    {
      $info = "The following records has been replaced:<br/>".$replaced;
    }
    else
    {
      $info = "No replacements made.";
    }
    
    return $this->renderJson(array("success"=>true,"info"=>$info));
  }
  
  /**
   * Commits all changes to zone records (updates SOA serial)
   */
  public function executeCommit()
  {
    $commited = '';
    
    $c = new Criteria();
    $c->add(AuditPeer::OBJECT, 'Record');
    $c->addGroupByColumn(AuditPeer::DOMAIN_ID);
    $c->add(AuditPeer::CREATED_AT, date("Y-m-d H:i:s",MyTools::getLastCommit()), Criteria::GREATER_THAN);
    
    foreach (AuditPeer::doSelect($c) as $audit)
    {
      $domain = DomainPeer::retrieveByPK($audit->getDomainId());
      
      if ($audit->getCreatedAt() == $domain->getCreatedAt())
      {
        continue;
      }
      
      $commited.= $domain->getName()."<br/>";
      
      // get SOA record
      $c = new Criteria();
      $c->add(RecordPeer::DOMAIN_ID, $domain->getId());
      $c->add(RecordPeer::TYPE, 'SOA');
      
      $SOA = RecordPeer::doSelectOne($c);
      
      $temp = explode(" ",$SOA->getContent());
      
      $serial = $temp[2];
      
      $date = substr($serial,0,8);
      $id = substr($serial,8);
      
      // today ?
      if ($date == date("Ymd"))
      {
        if ($id >= 99)
        {
          
          return $this->renderJson(array("success"=>false,"info"=>"Doamin ".$domain->getName()." serial: $serial"));
          return false;
        }
        
        $id++;
        
        if (strlen($id) == 1) $id = "0".$id;
        
        $serial = $date.$id;
        
      }
      else
      {
        $serial = date("Ymd")."01";
      }
      
      $SOA->setContent(implode(" ",array($temp[0],$temp[1],$serial)));
      $SOA->save();
    }
    
    if ($commited)
    {
      file_put_contents(SF_ROOT_DIR.'/log/last_commit.log',time());
      
      $info = "Commited changes to the following domains:<br/>".$commited;
    }
    else
    {
      $info = "No changes to commit.";
    }
    
    return $this->renderJson(array("success"=>true,"info"=>$info));
  }

  /**
   * List
   */
  public function executeList()
  {
    $this->output = array();
    
    $c = new Criteria();
    $c->addAscendingOrderByColumn(DomainPeer::NAME);
    
    foreach (DomainPeer::doSelect($c) as $domain)
    {
      $data = $domain->toArray(BasePeer::TYPE_FIELDNAME);
      
      $records = array();
      
      $domain_needs_commit = false;
      
      foreach ($domain->getRecords() as $record)
      {
        $record_data = $record->toArray(BasePeer::TYPE_FIELDNAME);
        
        $record_data['needs_commit'] = $record->needsCommit();
        
        if ($record_data['needs_commit']) $domain_needs_commit = true;
        
        $records[] = $record_data;
      }
      
      // check for deleted records
      $c = new Criteria();
      $c->add(AuditPeer::TYPE, 'DELETE');
      $c->add(AuditPeer::OBJECT, 'Record');
      $c->add(AuditPeer::DOMAIN_ID, $domain->getId());
      $c->add(AuditPeer::CREATED_AT, date("Y-m-d H:i:s",MyTools::getLastCommit()), Criteria::GREATER_THAN);
      
      if (AuditPeer::doCount($c))
      {
        $domain_needs_commit = true;
      }
      
      
      $data['needs_commit'] = $domain_needs_commit;
      $data['records'] = $records;
      
      $this->output[] = $data;
    }
    
    if ($this->isAjax())
    {
      return $this->renderStore('Domain',$this->output);
    }
  }
  
  /**
   * Add
   */
  public function executeAdd()
  {
    if ($this->isGET())
    {
      return $this->renderJson(array("success"=>false,"info"=>"POST only."));
    }
    else
    {
      $name = $this->getRequestParameter('name');
      
      $domain = new Domain();
      $domain->setName($name);
      $domain->setType($this->template->getType());
      $domain->save();
      
      foreach ($this->template->getTemplateRecords() as $tr)
      {
        
        $record = new Record();
        $record->setDomainId($domain->getId());
        $record->setName(str_replace("%DOMAIN%",$name,$tr->getName()));
        $record->setType($tr->getType());
        
        if ($tr->getType() == 'SOA')
        {
          $content = str_replace("%DOMAIN%",$name,$tr->getContent());
          $content = str_replace("%SERIAL%",date("Ymd")."01",$content);
        }
        else
        {
          $content = $tr->getContent();
        }
        
        $record->setContent($content);
        $record->setTtl($tr->getTtl());
        $record->setPrio($tr->getPrio());
        $record->save();
      }
      
      return $this->renderJson(array("success"=>true,"info"=>"Domain added."));
    }
  }
  
  public function validateAdd()
  {
    if ($this->isPOST())
    {
      $c = new Criteria();
      $c->add(DomainPeer::NAME, $this->getRequestParameter('name'));
      
      if (DomainPeer::doSelectOne($c))
      {
        $this->getRequest()->setError('name','This name is already in use.');
        return false;
      }
      
      if (!$this->template = TemplatePeer::retrieveByPK($this->getRequestParameter('template_id')))
      {
        $this->getRequest()->setError('template_id','Invalid template id.');
        return false;
      }
    }
    
    return true;
  }
  
  /**
   * Edit
   */
  public function executeEdit()
  {
    if ($this->isGET())
    {
      return $this->renderJson(array("success"=>false,"info"=>"POST only."));
    }
    else
    {
      $ids = array();
      
      foreach ($this->getRequestParameter('record') as $data)
      {
        if (!$record = RecordPeer::retrieveByPK($data['id']))
        {
          $record = new Record();
          $record->setDomainId($this->domain->getId());
        }
        
        $record->setName($data['name']);
        $record->setType($data['type']);
        $record->setContent($data['content']);
        $record->setTtl($data['ttl']);
        
        if ($data['type'] == 'MX')
        {
          $record->setPrio($data['prio']);
        }
        
        $record->save();
        
        $ids[] = $record->getId();
      }
      
      $c = new Criteria();
      $c->add(RecordPeer::DOMAIN_ID, $this->domain->getId());
      $c->add(RecordPeer::ID, $ids, Criteria::NOT_IN);
      
      foreach (RecordPeer::doSelect($c) as $record)
      {
        $record->delete();
      }
      
      return $this->renderJson(array("success"=>true,"info"=>"Domain updated."));
    }
  }
  
  public function validateEdit()
  {
    if ($this->isPOST())
    {
      if (!$this->domain = DomainPeer::retrieveByPK($this->getRequestParameter('id')))
      {
        $this->getRequest()->setError('id','Invalid domain id.');
        return false;
      }
      
      if (!is_array($this->getRequestParameter('record')))
      {
        $this->getRequest()->setError('record','record[] needs to be an array.');
        return false;
      }
      
      $i = 1;
    
      $SOA_count = 0;
      $NS_count = 0;
      
      foreach ($this->getRequestParameter('record') as $data)
      {
        
        if (!isset($data['name']) || !isset($data['type']) || !isset($data['content']) 
          || !isset($data['ttl']))
        {
          $this->getRequest()->setError('record',"Row $i: some data is missing.");
          return false;
        }
        
        if (!$data['name'])
        {
          $this->getRequest()->setError('record',"Row $i: name can't be left blank.");
          return false;
        }
        
        if (!in_array($data['type'],array("SOA","NS","MX","A","CNAME","TXT")))
        {
          $this->getRequest()->setError('record',"Row $i: invalid record type.");
          return false;
        }
        
        switch ($data['type'])
        {
          case 'SOA':
            if (!preg_match('/^[a-z,\.,0-9,-,_]+\s[a-z,\.,0-9,-,_]+'.$this->domain->getName().'\s[0-9]+$/',$data['content']))
            {
              $this->getRequest()->setError('record',"Row $i: invalid SOA content.");
              return false;
            }
            break;
          case 'NS':
            if (!preg_match('/^[a-z,.,0-9,-,_]+$/',$data['content']))
            {
              $this->getRequest()->setError('record',"Row $i: invalid NS content.");
              return false;
            }
            break;
        }
        
        if (!preg_match('/^[0-9]+$/',$data['ttl']))
        {
          $this->getRequest()->setError('record',"Row $i: TTL has to be a number.");
          return false;
        }
        
        if ($data['ttl'] < 5 || $data['ttl'] > 86400)
        {
          $this->getRequest()->setError('record',"Row $i: TTL has to be in a range of 5-86400.");
          return false;
        }
        
        
        if ($data['type'] == 'MX')
        {
          
          if (!preg_match('/^[0-9]+$/',$data['prio']))
          {
            $this->getRequest()->setError('record',"Row $i: Prio has to be a number.");
            return false;
          }
          
          if ($data['prio'] < 0 || $data['prio'] > 100)
          {
            $this->getRequest()->setError('record',"Row $i: Prio has to be in a range of 0-100.");
            return false;
          }
        }
        
        if (!$data['content'])
        {
            $this->getRequest()->setError('record',"Row $i: Content can't be left blank.");
            return false;
        }
        
        if ($data['type'] == 'SOA') $SOA_count++;
        if ($data['type'] == 'NS') $NS_count++;
        
        $i++;
      }
      
      if ($SOA_count !== 1)
      {
        $this->getRequest()->setError('record',"Only one SOA record allowed.");
        return false;
      }
      
      if ($NS_count < 1 || $NS_count > 10)
      {
        $this->getRequest()->setError('record',"Number of NS records should be in a range of 1-10.");
        return false;
      }
    }
    
    return true;
  }
  
}
