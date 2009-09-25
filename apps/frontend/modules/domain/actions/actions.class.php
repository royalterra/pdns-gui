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
}
