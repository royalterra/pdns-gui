<?php


abstract class BaseDomains extends BaseObject  implements Persistent {


	
	protected static $peer;


	
	protected $id;


	
	protected $name;


	
	protected $master;


	
	protected $last_check;


	
	protected $type;


	
	protected $notified_serial;


	
	protected $account;

	
	protected $alreadyInSave = false;

	
	protected $alreadyInValidation = false;

	
	public function getId()
	{

		return $this->id;
	}

	
	public function getName()
	{

		return $this->name;
	}

	
	public function getMaster()
	{

		return $this->master;
	}

	
	public function getLastCheck()
	{

		return $this->last_check;
	}

	
	public function getType()
	{

		return $this->type;
	}

	
	public function getNotifiedSerial()
	{

		return $this->notified_serial;
	}

	
	public function getAccount()
	{

		return $this->account;
	}

	
	public function setId($v)
	{

		
		
		if ($v !== null && !is_int($v) && is_numeric($v)) {
			$v = (int) $v;
		}

		if ($this->id !== $v) {
			$this->id = $v;
			$this->modifiedColumns[] = DomainsPeer::ID;
		}

	} 
	
	public function setName($v)
	{

		
		
		if ($v !== null && !is_string($v)) {
			$v = (string) $v; 
		}

		if ($this->name !== $v) {
			$this->name = $v;
			$this->modifiedColumns[] = DomainsPeer::NAME;
		}

	} 
	
	public function setMaster($v)
	{

		
		
		if ($v !== null && !is_string($v)) {
			$v = (string) $v; 
		}

		if ($this->master !== $v) {
			$this->master = $v;
			$this->modifiedColumns[] = DomainsPeer::MASTER;
		}

	} 
	
	public function setLastCheck($v)
	{

		
		
		if ($v !== null && !is_int($v) && is_numeric($v)) {
			$v = (int) $v;
		}

		if ($this->last_check !== $v) {
			$this->last_check = $v;
			$this->modifiedColumns[] = DomainsPeer::LAST_CHECK;
		}

	} 
	
	public function setType($v)
	{

		
		
		if ($v !== null && !is_string($v)) {
			$v = (string) $v; 
		}

		if ($this->type !== $v) {
			$this->type = $v;
			$this->modifiedColumns[] = DomainsPeer::TYPE;
		}

	} 
	
	public function setNotifiedSerial($v)
	{

		
		
		if ($v !== null && !is_int($v) && is_numeric($v)) {
			$v = (int) $v;
		}

		if ($this->notified_serial !== $v) {
			$this->notified_serial = $v;
			$this->modifiedColumns[] = DomainsPeer::NOTIFIED_SERIAL;
		}

	} 
	
	public function setAccount($v)
	{

		
		
		if ($v !== null && !is_string($v)) {
			$v = (string) $v; 
		}

		if ($this->account !== $v) {
			$this->account = $v;
			$this->modifiedColumns[] = DomainsPeer::ACCOUNT;
		}

	} 
	
	public function hydrate(ResultSet $rs, $startcol = 1)
	{
		try {

			$this->id = $rs->getInt($startcol + 0);

			$this->name = $rs->getString($startcol + 1);

			$this->master = $rs->getString($startcol + 2);

			$this->last_check = $rs->getInt($startcol + 3);

			$this->type = $rs->getString($startcol + 4);

			$this->notified_serial = $rs->getInt($startcol + 5);

			$this->account = $rs->getString($startcol + 6);

			$this->resetModified();

			$this->setNew(false);

						return $startcol + 7; 
		} catch (Exception $e) {
			throw new PropelException("Error populating Domains object", $e);
		}
	}

	
	public function delete($con = null)
	{
		if ($this->isDeleted()) {
			throw new PropelException("This object has already been deleted.");
		}

		if ($con === null) {
			$con = Propel::getConnection(DomainsPeer::DATABASE_NAME);
		}

		try {
			$con->begin();
			DomainsPeer::doDelete($this, $con);
			$this->setDeleted(true);
			$con->commit();
		} catch (PropelException $e) {
			$con->rollback();
			throw $e;
		}
	}

	
	public function save($con = null)
	{
		if ($this->isDeleted()) {
			throw new PropelException("You cannot save an object that has been deleted.");
		}

		if ($con === null) {
			$con = Propel::getConnection(DomainsPeer::DATABASE_NAME);
		}

		try {
			$con->begin();
			$affectedRows = $this->doSave($con);
			$con->commit();
			return $affectedRows;
		} catch (PropelException $e) {
			$con->rollback();
			throw $e;
		}
	}

	
	protected function doSave($con)
	{
		$affectedRows = 0; 		if (!$this->alreadyInSave) {
			$this->alreadyInSave = true;


						if ($this->isModified()) {
				if ($this->isNew()) {
					$pk = DomainsPeer::doInsert($this, $con);
					$affectedRows += 1; 										 										 
					$this->setId($pk);  
					$this->setNew(false);
				} else {
					$affectedRows += DomainsPeer::doUpdate($this, $con);
				}
				$this->resetModified(); 			}

			$this->alreadyInSave = false;
		}
		return $affectedRows;
	} 
	
	protected $validationFailures = array();

	
	public function getValidationFailures()
	{
		return $this->validationFailures;
	}

	
	public function validate($columns = null)
	{
		$res = $this->doValidate($columns);
		if ($res === true) {
			$this->validationFailures = array();
			return true;
		} else {
			$this->validationFailures = $res;
			return false;
		}
	}

	
	protected function doValidate($columns = null)
	{
		if (!$this->alreadyInValidation) {
			$this->alreadyInValidation = true;
			$retval = null;

			$failureMap = array();


			if (($retval = DomainsPeer::doValidate($this, $columns)) !== true) {
				$failureMap = array_merge($failureMap, $retval);
			}



			$this->alreadyInValidation = false;
		}

		return (!empty($failureMap) ? $failureMap : true);
	}

	
	public function getByName($name, $type = BasePeer::TYPE_PHPNAME)
	{
		$pos = DomainsPeer::translateFieldName($name, $type, BasePeer::TYPE_NUM);
		return $this->getByPosition($pos);
	}

	
	public function getByPosition($pos)
	{
		switch($pos) {
			case 0:
				return $this->getId();
				break;
			case 1:
				return $this->getName();
				break;
			case 2:
				return $this->getMaster();
				break;
			case 3:
				return $this->getLastCheck();
				break;
			case 4:
				return $this->getType();
				break;
			case 5:
				return $this->getNotifiedSerial();
				break;
			case 6:
				return $this->getAccount();
				break;
			default:
				return null;
				break;
		} 	}

	
	public function toArray($keyType = BasePeer::TYPE_PHPNAME)
	{
		$keys = DomainsPeer::getFieldNames($keyType);
		$result = array(
			$keys[0] => $this->getId(),
			$keys[1] => $this->getName(),
			$keys[2] => $this->getMaster(),
			$keys[3] => $this->getLastCheck(),
			$keys[4] => $this->getType(),
			$keys[5] => $this->getNotifiedSerial(),
			$keys[6] => $this->getAccount(),
		);
		return $result;
	}

	
	public function setByName($name, $value, $type = BasePeer::TYPE_PHPNAME)
	{
		$pos = DomainsPeer::translateFieldName($name, $type, BasePeer::TYPE_NUM);
		return $this->setByPosition($pos, $value);
	}

	
	public function setByPosition($pos, $value)
	{
		switch($pos) {
			case 0:
				$this->setId($value);
				break;
			case 1:
				$this->setName($value);
				break;
			case 2:
				$this->setMaster($value);
				break;
			case 3:
				$this->setLastCheck($value);
				break;
			case 4:
				$this->setType($value);
				break;
			case 5:
				$this->setNotifiedSerial($value);
				break;
			case 6:
				$this->setAccount($value);
				break;
		} 	}

	
	public function fromArray($arr, $keyType = BasePeer::TYPE_PHPNAME)
	{
		$keys = DomainsPeer::getFieldNames($keyType);

		if (array_key_exists($keys[0], $arr)) $this->setId($arr[$keys[0]]);
		if (array_key_exists($keys[1], $arr)) $this->setName($arr[$keys[1]]);
		if (array_key_exists($keys[2], $arr)) $this->setMaster($arr[$keys[2]]);
		if (array_key_exists($keys[3], $arr)) $this->setLastCheck($arr[$keys[3]]);
		if (array_key_exists($keys[4], $arr)) $this->setType($arr[$keys[4]]);
		if (array_key_exists($keys[5], $arr)) $this->setNotifiedSerial($arr[$keys[5]]);
		if (array_key_exists($keys[6], $arr)) $this->setAccount($arr[$keys[6]]);
	}

	
	public function buildCriteria()
	{
		$criteria = new Criteria(DomainsPeer::DATABASE_NAME);

		if ($this->isColumnModified(DomainsPeer::ID)) $criteria->add(DomainsPeer::ID, $this->id);
		if ($this->isColumnModified(DomainsPeer::NAME)) $criteria->add(DomainsPeer::NAME, $this->name);
		if ($this->isColumnModified(DomainsPeer::MASTER)) $criteria->add(DomainsPeer::MASTER, $this->master);
		if ($this->isColumnModified(DomainsPeer::LAST_CHECK)) $criteria->add(DomainsPeer::LAST_CHECK, $this->last_check);
		if ($this->isColumnModified(DomainsPeer::TYPE)) $criteria->add(DomainsPeer::TYPE, $this->type);
		if ($this->isColumnModified(DomainsPeer::NOTIFIED_SERIAL)) $criteria->add(DomainsPeer::NOTIFIED_SERIAL, $this->notified_serial);
		if ($this->isColumnModified(DomainsPeer::ACCOUNT)) $criteria->add(DomainsPeer::ACCOUNT, $this->account);

		return $criteria;
	}

	
	public function buildPkeyCriteria()
	{
		$criteria = new Criteria(DomainsPeer::DATABASE_NAME);

		$criteria->add(DomainsPeer::ID, $this->id);

		return $criteria;
	}

	
	public function getPrimaryKey()
	{
		return $this->getId();
	}

	
	public function setPrimaryKey($key)
	{
		$this->setId($key);
	}

	
	public function copyInto($copyObj, $deepCopy = false)
	{

		$copyObj->setName($this->name);

		$copyObj->setMaster($this->master);

		$copyObj->setLastCheck($this->last_check);

		$copyObj->setType($this->type);

		$copyObj->setNotifiedSerial($this->notified_serial);

		$copyObj->setAccount($this->account);


		$copyObj->setNew(true);

		$copyObj->setId(NULL); 
	}

	
	public function copy($deepCopy = false)
	{
				$clazz = get_class($this);
		$copyObj = new $clazz();
		$this->copyInto($copyObj, $deepCopy);
		return $copyObj;
	}

	
	public function getPeer()
	{
		if (self::$peer === null) {
			self::$peer = new DomainsPeer();
		}
		return self::$peer;
	}

} 