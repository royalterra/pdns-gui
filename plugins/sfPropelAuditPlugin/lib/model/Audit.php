<?php

/**
 * Subclass for representing a row from the 'audit' table.
 *
 * 
 *
 * @package plugins.sfPropelAuditPlugin.lib.model
 */ 
class Audit extends BaseAudit
{
  public function getUser()
  {
    switch ($this->getUserType())
    {
      case 'U':
        $user = UserPeer::retrieveByPK($this->getUserId());
        break;
      case 'P':
        $user = PartnerUserPeer::retrieveByPK($this->getUserId());
        break;
      case 'S':
        $user = SupplierUserPeer::retrieveByPK($this->getUserId());
        break;
      default:
        $user = new User();
        $user->setFirstName('Anonymous');
    }
    
    return $user;
  }
  
}
