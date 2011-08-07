<?php

namespace Tree\Test;

use \Tree\Database\Connection;

/**
 * Fake_BrokenConnection 
 *
 * A Connection implementation intended to mimic a connection that is failing
 * to open for use in verifying the error handling in this situation
 *
 * @author     Henry Smith <henry@henrysmith.org> 
 * @copyright  2011 Henry Smith
 * @license    GPLv2.0
 * @package    Tree
 * @subpackage Test
 * @uses       \Tree\Database\Connection
 * @version    0.00
 */
class Fake_BrokenConnection extends Connection {

	protected function vendorIsConnected()
	{
		return false;
	}

	public function setIniValues(array $config)
	{
	}

	protected function vendorConnect()
	{
		return false;
	}

	protected function vendorEscape($string)
	{
		return addslashes($string);
	}

	protected function vendorQuery($sql)
	{
		return null;
	}

}


