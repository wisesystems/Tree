<?php

namespace Tree\Test;

use \Tree\Database\Connection;

/**
 * Fake_Connection 
 *
 * A fake database connection class to be used to test query generation code
 * without setting up a real database with which to interact 
 * 
 * @author     Henry Smith <henry@henrysmith.org> 
 * @copyright  2011 Henry Smith
 * @license    GPLv2.0
 * @package    Tree
 * @subpackage Test
 * @uses       \Tree\Database\Connection
 * @version    0.00
 */
class Fake_Connection extends Connection {

	public function setIniValues(array $config)
	{
	}

	protected function vendorConnect()
	{
		return true;
	}

	protected function vendorEscape($string)
	{
		return addslashes($string);
	}

	protected function vendorQuery($sql)
	{
		return null;
	}

	protected function vendorIsConnected()
	{
		return true;
	}

}

