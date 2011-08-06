<?php

namespace Tree\Test;

require_once '../Orm/Entity.php';

use \Tree\Orm\Entity;

class Fake_Entity extends Entity {

	protected $columnList = array(
		'id',
		'title',
		'body',
	);

}

