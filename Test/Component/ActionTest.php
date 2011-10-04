<?php

namespace Tree\Test\Component;

require_once '../Component/Action.php';
require_once '../Behaviour/Http200Response.php';
require_once 'Fake/Action.php';

use \PHPUnit_Framework_TestCase;
use \Tree\Component\Action;
use \Tree\Test\Fake_Action;

/**
 * ActionTest 
 *
 * Tests for correct filtering of input values by Action
 * 
 * @author     Henry Smith <henry@henrysmith.org> 
 * @copyright  2011 Henry Smith
 * @license    GPLv2.0
 * @package    Tree
 * @subpackage Test
 * @uses       PHPUnit_Framework_TestCase
 * @version    0.00
 */
class ActionTest extends PHPUnit_Framework_TestCase {

	private $action;

	public function setUp()
	{
		$this->action = new Fake_Action;
	}

	/**
	 * Tests that performAction() correctly passes any input values that
	 * have been received through to the main() method
	 *
	 * @covers \Tree\Component\Action::performAction
	 */
	public function testPerformActionPassesInputToMain()
	{
		$this->action->setParameter('id', 12345);

		$response = $this->action->performAction();

		$this->assertEquals(200, $response);
	}

}

