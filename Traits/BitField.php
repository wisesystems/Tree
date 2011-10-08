<?php

namespace Tree\Traits;

/**
 * BitField 
 *
 * Handles the manipulation and examination of bit field values so that classes
 * with such values can avoid reimplementing this same old set of logic
 * 
 * @author     Henry Smith <henry@henrysmith.org> 
 * @copyright  2011 Henry Smith
 * @license    GPLv2.0
 * @package    Tree
 * @subpackage Traits
 * @version    0.00
 */
trait BitField {

	/**
	 * Indicates whether the given flag is present in the given bit field
	 * 
	 * @access public
	 * @param  integer $bitfield   Haystack
	 * @param  integer $flag       Needle
	 * @return boolean
	 */
	public function getBitFieldFlag($bitfield, $flag)
	{
		return ($bitfield & $flag) != 0;
	}

	/**
	 * Sets the given flag to 1 in the given bit field
	 * 
	 * @access public
	 * @param  integer $bitfield 
	 * @param  integer $flag 
	 * @return integer
	 */
	public function setBitFieldFlag($bitfield, $flag)
	{
		$bitfield |= $flag;

		return $bitfield;
	}

	/**
	 * Sets the given flag to 0 in the given bit field
	 * 
	 * @access public
	 * @param  integer $bitfield 
	 * @param  integer $flag 
	 * @return integer
	 */
	public function unsetBitFieldFlag($bitfield, $flag)
	{
		$bitfield &= ~$flag;

		return $bitfield;
	}

}

