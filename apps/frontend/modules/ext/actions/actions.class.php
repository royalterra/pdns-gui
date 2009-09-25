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
   * Error
   * 
   * @todo Implement 'watchdog' table
   */
  public function executeError()
  {
    return $this->renderJson(array("success"=>true,"info"=>"Error logged."));
  }
}
