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
	 * @covers \Tree\Traits\BitField::getBitFieldFlag
	 * @test
	 */
	public function getBitFieldFlag()
	{
		$this->assertFalse($this->bitfield->getBitFieldFlag(0b000, 0b000));
		$this->assertFalse($this->bitfield->getBitFieldFlag(0b000, 0b001));
		$this->assertTrue($this->bitfield->getBitFieldFlag(0b001, 0b001));
		$this->assertFalse($this->bitfield->getBitFieldFlag(0b010, 0b000));
		$this->assertFalse($this->bitfield->getBitFieldFlag(0b010, 0b001));
		$this->assertTrue($this->bitfield->getBitFieldFlag(0b010, 0b010));
		$this->assertFalse($this->bitfield->getBitFieldFlag(0b011, 0b000));
		$this->assertTrue($this->bitfield->getBitFieldFlag(0b011, 0b001));
		$this->assertTrue($this->bitfield->getBitFieldFlag(0b011, 0b010));
		$this->assertTrue($this->bitfield->getBitFieldFlag(0b011, 0b011));
		$this->assertFalse($this->bitfield->getBitFieldFlag(0b100, 0b000));
		$this->assertFalse($this->bitfield->getBitFieldFlag(0b100, 0b001));
		$this->assertFalse($this->bitfield->getBitFieldFlag(0b100, 0b010));
		$this->assertFalse($this->bitfield->getBitFieldFlag(0b100, 0b011));
		$this->assertTrue($this->bitfield->getBitFieldFlag(0b100, 0b100));
	}

	/**
	 * @covers \Tree\Traits\BitField::setBitFieldFlag
	 * @test
	 */
	public function setBitFieldFlag()
	{
		$bitfield = 0b01100;
		$flag     = 0b00001;
		$expected = 0b01101;
		$actual   = $this->bitfield->setBitFieldFlag($bitfield, $flag);

		$this->assertEquals($expected, $actual);
	}

}

