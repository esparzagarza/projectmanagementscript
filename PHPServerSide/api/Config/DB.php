<?php

namespace App;

class DB
{

	public static $_connection;

	public static function getConnection()
	{
		self::$_connection = null;

		try {

			self::$_connection = new \PDO(DB_DSN, DB_USERNAME, DB_PASSWORD, DB_OPTIONS);

		} catch (\PDOException $exception) {

			echo "Connection error: " . $exception->getMessage();
		}

		return self::$_connection;
	}
}
