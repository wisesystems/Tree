<?php

namespace Tree\Test;

use \Tree\Database\Connection;

/**
 * Fake_BrokenConnection 
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

	public function isConnected()
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


