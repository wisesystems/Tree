<?php

namespace Tree\Test;

require 'PHPUnit/Autoload.php';
require '../Database/Connection.php';
require '../Database/Query.php';
require '../Database/Query/Where.php';
require '../Database/Query/Select.php';
require 'Fake/Connection.php';

use \Tree\Database\Query;
use \Tree\Database\Query_Select;
use \PHPUnit_Framework_TestCase;
use \Tree\Test\Fake_Connection;

class QuerySelectTest extends PHPUnit_Framework_TestCase {

	public function setUp()
	{
		$this->db = new Fake_Connection;
	}

	public function testBasicSelectQuery()
	{
		$query = new Query_Select($this->db);

		$query->select('*')->from('sometable');

		$sql = $query->getSql();

		$this->assertEquals("SELECT\n\t*\nFROM\n\t`sometable`\n", $sql);
	}

	public function testTableNameAlias()
	{
		$query = new Query_Select($this->db);

		$query->select('*')->from(array('sometable' => 'alias'));

		$sql = $query->getSql();

		$this->assertEquals("SELECT\n\t*\nFROM\n\t`sometable` `alias`\n", $sql);
	}

	public function testOrderBy()
	{
		$query = new Query_Select($this->db);

		$query->select('*');
		$query->from('sometable');
		$query->orderBy('test', 'asc');

		$sql = $query->getSql();

		echo $sql;

		$this->assertEquals("SELECT\n\t*\nFROM\n\t`sometable`\nORDER BY\n\t`test` ASC\n", $sql);
	}

}

