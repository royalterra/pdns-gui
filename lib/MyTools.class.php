<?php

/**
 * Class with various Tools.
 * 
 * @ingroup tools
 * @author Chris Maciejewski <chris@wima.co.uk>
 * @version    SVN: $Id: MyTools.class.php 1171 2009-08-18 14:56:03Z chris $
 */ 
class MyTools
{
  /**
   * Gets last commit timestamp
   * 
   * @return int
   */
  public static function getLastCommit()
  {
    if (!$time = @file_get_contents(SF_ROOT_DIR.'/log/last_commit.log'))
    {
      $time = 0;
      
      file_put_contents(SF_ROOT_DIR.'/log/last_commit.log',$time);
    }
    
    return $time;
  }
  
  /**
   * Commits changes (increases serial by 1)
   * 
   * @return array
   */
  public static function commit()
  {
    $commited = array();
    
    $c = new Criteria();
    $c->add(AuditPeer::OBJECT, 'Record');
    $c->addGroupByColumn(AuditPeer::DOMAIN_ID);
    $c->add(AuditPeer::CREATED_AT, date("Y-m-d H:i:s",MyTools::getLastCommit()), Criteria::GREATER_THAN);
    
    foreach (AuditPeer::doSelect($c) as $audit)
    {
      $domain = DomainPeer::retrieveByPK($audit->getDomainId());
      
      $commited[] = $domain->getName();
      
      // get SOA record
      $c = new Criteria();
      $c->add(RecordPeer::DOMAIN_ID, $domain->getId());
      $c->add(RecordPeer::TYPE, 'SOA');
      
      $SOA = RecordPeer::doSelectOne($c);
      
      $temp = explode(" ",$SOA->getContent());
      
      $serial = $temp[2];
      
      $date = substr($serial,0,8);
      $id = substr($serial,8);
      
      // today ?
      if ($date == date("Ymd"))
      {
        $id++;
        
        if (strlen($id) == 1) $id = "0".$id;
        
        $serial = $date.$id;
      }
      else
      {
        $serial = date("Ymd")."01";
      }
      
      if ($serial >= 4294967295) continue; // don't allow >= 32bit unsigned
      
      $SOA->setContent(implode(" ",array($temp[0],$temp[1],$serial)));
      $SOA->save();
    }
    
    if ($commited)
    {
      file_put_contents(SF_ROOT_DIR.'/log/last_commit.log',time());
      
    }
    
    return $commited;
  }
}
