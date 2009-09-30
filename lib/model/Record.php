<?php

/**
 * Subclass for representing a row from the 'records' table.
 *
 * 
 *
 * @package lib.model
 */ 
class Record extends BaseRecord
{
  public function needsCommit()
  {
    $c = new Criteria();
    $c->add(AuditPeer::OBJECT, 'Record');
    $c->add(AuditPeer::OBJECT_KEY, $this->getId());
    $c->add(AuditPeer::DOMAIN_ID, $this->getDomainId());
    $c->add(AuditPeer::CREATED_AT, date("Y-m-d H:i:s",MyTools::getLastCommit()), Criteria::GREATER_THAN);
    $c->addAnd(AuditPeer::CREATED_AT, $this->getDomain()->getCreatedAt(), Criteria::NOT_EQUAL);
    
    return AuditPeer::doCount($c);
  }
}

sfPropelBehavior::add('Record', array('audit'));
