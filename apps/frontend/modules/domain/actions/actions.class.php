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
   * List
   *
   */
  public function executeList()
  {
    $this->output = array();
    
    foreach (DomainPeer::doSelect(new Criteria()) as $domain)
    {
      $data = $domain->toArray(BasePeer::TYPE_FIELDNAME);
      
      $records = array();
      
      foreach ($domain->getRecords() as $record)
      {
        $records[] = $record->toArray(BasePeer::TYPE_FIELDNAME);
      }
      
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
