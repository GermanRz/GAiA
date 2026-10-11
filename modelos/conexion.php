<?php

class Conexion
{

	private static $instancia = null;

	private function __construct() {}

	private function __clone() {}

	static public function conectar()
	{

		if (self::$instancia === null) {

			self::$instancia = new PDO(
				"mysql:host=localhost;dbname=gaia_db",
				"root",
				"root"
			);

			self::$instancia->exec("set names utf8");

		}

		return self::$instancia;
	}
}

