<?php

namespace Tree\Test;

require_once 'PHPUnit/Autoload.php';
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
 * DatabaseConnectionTest
 *
 * Verifies that database connections behave properly and report exception
 * states right
 *
 * @author     Henry Smith <henry@henrysmith.org> 
 * @copyright  2011 Henry Smith
 * @license    GPLv2.0
 * @package    Tree
 * @subpackage Test
 * @uses       \Tree\Database\Connection
 * @version    0.00
 */
class DatabaseConnectionTest extends PHPUnit_Framework_TestCase {

	public function testThrowsExceptionIfMisconfigured()
	{
		$db   = new Fake_BrokenConnection;
		$code = null;

		try {
			$db->query('SELECT * FROM test');
		} catch (DatabaseException $e) {
			$code = $e->getCode();
		}

		$this->assertEquals(DatabaseException::CONNECTION_FAILED, $code);
	}

}

