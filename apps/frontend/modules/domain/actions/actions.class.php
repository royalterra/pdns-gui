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
      $this->output[] = $domain->toArray(BasePeer::TYPE_FIELDNAME);
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
}
