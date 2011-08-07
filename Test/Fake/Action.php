<?php

namespace Tree\Test;

use \Tree\Component\Action;
use \Tree\Behaviour\Http200Handler;

/**
 * Fake_Action 
 *
 * A simple fake action for use in testing the Action component
 * 
 * @author     Henry Smith <henry@henrysmith.org> 
 * @copyright  2011 Henry Smith
 * @license    GPLv2.0
 * @package    Tree
 * @subpackage Test
 * @uses       \Tree\Component\Action
 * @version    0.00
 */
class Fake_Action extends Action implements Http200Handler {

	public function main(array $input)
	{
		if ($input['id'] == 12345) {
			return 200;
		} else {
			return 404;
		}
	}

	public function handle200($request)
	{
		return '<p>test</p>';
	}

}

