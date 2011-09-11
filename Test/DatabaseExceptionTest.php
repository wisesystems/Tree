<?php

namespace Tree\Test;

require_once '../Database/Connection.php';
require_once '../Exception/DatabaseException.php';
require_once 'Fake/Connection.php';
require_once 'Fake/BrokenConnection.php';

use \Tree\Database\Query;
use \Tree\Exception\DatabaseException;
use \PHPUnit_Framework_TestCase;
use \Tree\Test\Fake_Connection;
use \Tree\Test\Fake_BrokenConnection;

/**
 * DatabaseExceptionTest
 *
 * Verifies that database exceptions are thrown under expected circumstances
 *
 * @author     Henry Smith <henry@henrysmith.org> 
 * @copyright  2011 Henry Smith
 * @license    GPLv2.0
 * @package    Tree
 * @subpackage Test
 * @uses       \Tree\Database\Connection
 * @version    0.00
 */
class DatabaseExceptionTest extends PHPUnit_Framework_TestCase {

	/**
	 * Verifies that Database throws an exception if being used when the
	 * connection has failed to open
	 *
	 * @covers            \Tree\Database\Database::requireConnection
	 * @expectedException \Tree\Exception\DatabaseException
	 * @expectedException \Tree\Exception\DatabaseException::CONNECTION_FAILED
	 */
	public function testThrowsExceptionIfMisconfigured()
	{
		$db = new Fake_BrokenConnection;
		$db->query('SELECT * FROM test');
	}

}

