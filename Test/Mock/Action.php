<?php

namespace Tree\Test;

use \Tree\Component\Action;

/**
 * Mock_Action 
 *
 * A simple mock action for use in testing the Action component
 * 
 * @author     Henry Smith <henry@henrysmith.org> 
 * @copyright  2011 Henry Smith
 * @license    GPLv2.0
 * @package    Tree
 * @subpackage Test
 * @uses       \Tree\Component\Action
 * @version    0.00
 */
class Mock_Action extends Action {

	public function main(array $input)
	{
		return "Article: {$input['id']}";
	}

}

