<?php
/**
 * ExtJS  interface.
 *
 * @ingroup gui
 */
class extActions extends MyActions
{
  /**
   * Executes index action
   */
  public function executeIndex()
  {
    sfConfig::set('sf_web_debug', false);
    
    $this->noAjax();
  }

  /**
   * ExtJS application
   */
  public function executeApplication()
  {

  }
  
  /**
   * Audit
   */
  public function executeAudit()
  {
    $this->output = array();
    
    $c = new Criteria();
    $c->add(AuditPeer::OBJECT, 'Record');
    $c->addDescendingOrderByColumn(AuditPeer::ID);
    
    if ($search = $this->getRequestParameter('search'))
    {
      $c_search = new Criteria();
      $c_search->add(DomainPeer::NAME, "%$search%", Criteria::LIKE);
      
      $search_ids = array();
      
      foreach (DomainPeer::doSelect($c_search) as $domain)
      {
        $search_ids[] = $domain->getId();
      }
      
      $c->add(AuditPeer::DOMAIN_ID, $search_ids, Criteria::IN);
    }
    
    foreach (AuditPeer::doSelect($c) as $audit)
    {
      $this->output[] = $audit->toStore();
    }
    
    if ($this->isAjax())
    {
      return $this->renderStore('Audit',$this->output);
    }
  }
  
  /**
   * Error
   * 
   * @todo Implement 'watchdog' table
   */
  public function executeError()
  {
    return $this->renderJson(array("success"=>true,"info"=>"Error logged."));
  }
}
