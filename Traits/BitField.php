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
	 * Indicates whether the given flag is present in the given bitfield
	 * 
	 * @access public
	 * @param  integer $bitfield   Haystack
	 * @param  integer $flag       Needle
	 * @return boolean
	 */
	public function getBitFieldValue($bitfield, $flag)
	{
		return ($bitfield & $flag) != 0;
	}

}

