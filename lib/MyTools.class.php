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
  public static function getLastCommit()
  {
    if (!$time = @file_get_contents(SF_ROOT_DIR.'/log/last_commit.log'))
    {
      $time = 0;
      
      file_put_contents(SF_ROOT_DIR.'/log/last_commit.log',$time);
    }
    
    return $time;
  }
}
