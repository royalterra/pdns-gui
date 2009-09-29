<?php

/**
 * Template actions.
 *
 * @package    symfony
 * @subpackage host
 * @author     Your name here
 * @version    SVN: $Id: actions.class.php 2692 2006-11-15 21:03:55Z fabien $
 */
class templateActions extends MyActions
{

  /**
   * List
   *
   */
  public function executeList()
  {
    $this->output = array();
    
    foreach (TemplatePeer::doSelect(new Criteria()) as $template)
    {
      $this->output[] = $template->toArray(BasePeer::TYPE_FIELDNAME);
    }
    
    if ($this->isAjax())
    {
      return $this->renderStore('Template',$this->output);
    }
  }
}
