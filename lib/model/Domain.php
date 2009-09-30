<?php

/**
 * Subclass for representing a row from the 'domains' table.
 *
 * 
 *
 * @package lib.model
 */ 
class Domain extends BaseDomain
{
  /**
   * Gets timestamp when domain was created
   */
  public function getCreatedAt()
  {
    $c = new Criteria();
    $c->add(AuditPeer::OBJECT, 'Domain');
    $c->add(AuditPeer::TYPE, 'ADD');
    $c->add(AuditPeer::OBJECT_KEY, $this->getId());
    $c->addAscendingOrderByColumn(AuditPeer::ID);
    
    $audit = AuditPeer::doSelectOne($c);
    
    return $audit->getCreatedAt();
  }
}

sfPropelBehavior::add('Domain', array('audit'));
