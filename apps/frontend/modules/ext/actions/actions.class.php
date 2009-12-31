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
      $c->add(AuditPeer::OBJECT_CHANGES, "%$search%", Criteria::LIKE);
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
