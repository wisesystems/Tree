<?php

namespace Tree\Test;

require_once '../Traits/BitField.php';
require_once 'Fake/BitFieldClass.php';

use \PHPUnit_Framework_TestCase;
use \Tree\Traits\BitField;
use \Tree\Test\Fake_BitFieldClass;

/**
 * BitFieldTest
 *
 * Verifies that the BitField trait's bit manipulation behaves correctly
 * 
 * @author     Henry Smith <henry@henrysmith.org> 
 * @copyright  2011 Henry Smith
 * @license    GPLv2.0
 * @package    Tree
 * @subpackage Test
 * @uses       PHPUnit_Framework_TestCase
 * @version    0.00
 */
class BitFieldTest extends PHPUnit_Framework_TestCase {

	private $bitfield;

	public function setUp()
	{
		$this->bitfield = new Fake_BitFieldClass;
	}

	/**
	 * @covers \Tree\Traits\BitField::getBitFieldValue
	 * @test
	 */
	public function getBitFieldValue()
	{
		$this->assertFalse($this->bitfield->getBitFieldValue(0, 0));
		$this->assertFalse($this->bitfield->getBitFieldValue(0, 1));
		$this->assertTrue($this->bitfield->getBitFieldValue(1, 1));
		$this->assertFalse($this->bitfield->getBitFieldValue(2, 0));
		$this->assertFalse($this->bitfield->getBitFieldValue(2, 1));
		$this->assertTrue($this->bitfield->getBitFieldValue(2, 2));
		$this->assertFalse($this->bitfield->getBitFieldValue(3, 0));
		$this->assertTrue($this->bitfield->getBitFieldValue(3, 1));
		$this->assertTrue($this->bitfield->getBitFieldValue(3, 2));
		$this->assertTrue($this->bitfield->getBitFieldValue(3, 3));
		$this->assertFalse($this->bitfield->getBitFieldValue(4, 0));
		$this->assertFalse($this->bitfield->getBitFieldValue(4, 1));
		$this->assertFalse($this->bitfield->getBitFieldValue(4, 2));
		$this->assertFalse($this->bitfield->getBitFieldValue(4, 3));
		$this->assertTrue($this->bitfield->getBitFieldValue(4, 4));
	}

}

