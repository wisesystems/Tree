<?php

namespace Tree\Exception;

use \Exception;

class TemplateException extends Exception {

	const MISSING_REQUIRED_VARIABLE = 1;
	const INVALID_VALUE_NAME = 2;
	const MISSING_TEMPLATE_FILENAME = 3;

}

