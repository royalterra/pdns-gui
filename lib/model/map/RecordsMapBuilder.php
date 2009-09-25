<?php



class RecordsMapBuilder {

	
	const CLASS_NAME = 'lib.model.map.RecordsMapBuilder';

	
	private $dbMap;

	
	public function isBuilt()
	{
		return ($this->dbMap !== null);
	}

	
	public function getDatabaseMap()
	{
		return $this->dbMap;
	}

	
	public function doBuild()
	{
		$this->dbMap = Propel::getDatabaseMap('propel');

		$tMap = $this->dbMap->addTable('records');
		$tMap->setPhpName('Records');

		$tMap->setUseIdGenerator(true);

		$tMap->addPrimaryKey('ID', 'Id', 'int', CreoleTypes::INTEGER, true, null);

		$tMap->addColumn('DOMAIN_ID', 'DomainId', 'int', CreoleTypes::INTEGER, false, null);

		$tMap->addColumn('NAME', 'Name', 'string', CreoleTypes::VARCHAR, false, 255);

		$tMap->addColumn('TYPE', 'Type', 'string', CreoleTypes::VARCHAR, false, 6);

		$tMap->addColumn('CONTENT', 'Content', 'string', CreoleTypes::VARCHAR, false, 255);

		$tMap->addColumn('TTL', 'Ttl', 'int', CreoleTypes::INTEGER, false, null);

		$tMap->addColumn('PRIO', 'Prio', 'int', CreoleTypes::INTEGER, false, null);

		$tMap->addColumn('CHANGE_DATE', 'ChangeDate', 'int', CreoleTypes::INTEGER, false, null);

	} 
} 