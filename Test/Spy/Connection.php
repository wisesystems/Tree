<?php

namespace Tree\Test;

require_once '../Database/Connection';

use \Tree\Database\Connection;

/**
 * Spy_Connection 
 *
 * A spy database connection class whose purpose is to keep track of SQL
 * queries so that they can be inspected
 * 
 * @author     Henry Smith <henry@henrysmith.org> 
 * @copyright  2011 Henry Smith
 * @license    GPLv2.0
 * @package    Tree
 * @subpackage Test
 * @uses       \Tree\Database\Connection
 * @version    0.00
 */

class Spy_Connection extends Connection {

	public $queries = array();

	public function setIniValues(array $config)
	{
	}

	protected function vendorConnect()
	{
		return true;
	}

	protected function vendorEscape($string)
	{
		return "'" . addslashes($string) . "'";
	}

	protected function vendorQuery($sql)
	{
		$this->queries[] = $sql;
		return null;
	}

	protected function vendorIsConnected()
	{
		return true;
	}

}

