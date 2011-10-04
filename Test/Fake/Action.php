<?php

namespace Tree\Test;

require_once '../Component/Action.php';
require_once '../Behaviour/Http200Response.php';

use \Tree\Component\Action;
use \Tree\Behaviour\Http200Response;

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
class Fake_Action extends Action implements Http200Response {

	public function main(array $input)
	{
		if ($input['id'] == 12345) {
			return 200;
		} else {
			return 404;
		}
	}

	public function get200Response($request)
	{
		return '<p>test</p>';
	}

}

