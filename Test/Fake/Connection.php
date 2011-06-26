<?php

namespace Tree\Test;

use \Tree\Database\Connection;

class Fake_Connection extends Connection {

	public function isConnected()
	{
		return true;
	}

	public function setIniValues(array $config)
	{
	}

	protected function vendorConnect()
	{
		return true;
	}

	protected function vendorEscape($string)
	{
		return addslashes($string);
	}

	protected function vendorQuery($sql)
	{
		return null;
	}

}

