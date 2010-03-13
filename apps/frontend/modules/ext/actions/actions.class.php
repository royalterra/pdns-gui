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
    $this->errors = array();
    
    if (!function_exists('json_encode'))
    {
      $this->errors[] = 'PHP JSON support missing - see <a href="http://uk3.php.net/manual/en/book.json.php" target="_blank">http://uk3.php.net/manual/en/book.json.php</a>';
    }
    
    if (!in_array('mod_rewrite',apache_get_modules()))
    {
      $this->errors[] = 'Apache mod_rewrite module missing';
    }
    
    if ($this->errors)
    {
      $this->setTemplate('missing-mods');
      return sfView::SUCCESS;
    }
    
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
