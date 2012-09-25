<?php
	namespace SqlDiff\Util;

	use SqlDiff\Database\Table;
	use PDO;
	use PDOException;

	/**
	 *    A utility class to find more information to resolve an outstanding issue
	 *     with incomplete XML data returned from mysqldump in InnoDB tables
	 *
	 * @see http://bugs.mysql.com/bug.php?id=66821
	 */
	class DatabaseUtil
	{
		private static $dbName;
		private static $host;
		private static $username;
		private static $password;

		public static function setup($dbName, $host="127.0.0.1", $username="root", $password="")
		{
			self::$dbName = $dbName;
			self::$host = $host;
			self::$username = $username;
			self::$password = $password;
		}

		/**
		 * Initialize the database connection
		 *
		 * @return \PDO
		 */
		private static function initConnection()
		{
			try
			{
				$connection = new PDO("mysql:dbname=".self::$dbName.";host=".self::$host, self::$username, self::$password);
			}
			catch(PDOException $e)
			{
				die("Could not connect to database using supplied credentials");
			}

			return $connection;
		}

		/**
		 * Determine whether an index in a table is an InnoDB foreign key
		 *
		 * @param \SqlDiff\Database\Table $table
		 * @param                         $keyName
		 *
		 * @return bool
		 */
		public static function isForeignKey(Table $table, $keyName)
		{
			$statement = "SELECT COUNT(KEY_COLUMN_USAGE.REFERENCED_TABLE_NAME) AS count
							 FROM INFORMATION_SCHEMA.KEY_COLUMN_USAGE
							 WHERE KEY_COLUMN_USAGE.TABLE_SCHEMA = :schema AND KEY_COLUMN_USAGE.TABLE_NAME = :table
							 AND KEY_COLUMN_USAGE.CONSTRAINT_NAME = :key";

			$connection = self::initConnection();
			$query      = $connection->prepare($statement, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
			$query->setFetchMode(PDO::FETCH_ASSOC);

			$query->execute(array(":schema" => self::$dbName, ":table" => $table->getName(), ":key" => $keyName));
			$result = $query->fetch();

			return (int) $result["count"] == 1;
		}

		/**
		 * @param \SqlDiff\Database\Table $table
		 * @param                         $keyName
		 *
		 * @return mixed|null
		 */
		private static function getFKDetails(Table $table, $keyName)
		{
			$statement = "SELECT KEY_COLUMN_USAGE.REFERENCED_TABLE_NAME, KEY_COLUMN_USAGE.COLUMN_NAME, KEY_COLUMN_USAGE.REFERENCED_COLUMN_NAME,
										 REFERENTIAL_CONSTRAINTS.*
										 FROM INFORMATION_SCHEMA.KEY_COLUMN_USAGE, INFORMATION_SCHEMA.REFERENTIAL_CONSTRAINTS
										 WHERE KEY_COLUMN_USAGE.TABLE_SCHEMA = :schema AND KEY_COLUMN_USAGE.TABLE_NAME = :table
										 AND KEY_COLUMN_USAGE.CONSTRAINT_NAME = :key AND
										 REFERENTIAL_CONSTRAINTS.CONSTRAINT_NAME = :key AND
										 REFERENTIAL_CONSTRAINTS.TABLE_NAME = :table AND
										 REFERENTIAL_CONSTRAINTS.CONSTRAINT_SCHEMA = :schema";

			$connection = self::initConnection();
			$query      = $connection->prepare($statement, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
			$query->setFetchMode(PDO::FETCH_ASSOC);

			$query->execute(array(":schema" => self::$dbName, ":table" => $table->getName(), ":key" => $keyName));
			$result = $query->fetch();

			if(!empty($result["REFERENCED_TABLE_NAME"]))
				return $result;

			return null;
		}

		/**
		 * Get the creation statement for an InnoDB foreign key
		 *
		 * @param \SqlDiff\Database\Table $table
		 * @param                         $keyName
		 *
		 * @return bool|string
		 */
		public static function getFKCreateStatement(Table $table, $keyName)
		{
			$details = self::getFKDetails($table, $keyName);

			if($details)
			{
				$create = "CONSTRAINT `{$details['CONSTRAINT_NAME']}`
	FOREIGN KEY (`{$details['COLUMN_NAME']}`)
	REFERENCES `{$details['CONSTRAINT_SCHEMA']}`.`{$details['REFERENCED_TABLE_NAME']}` (`{$details['REFERENCED_COLUMN_NAME']}`)
	ON DELETE {$details['DELETE_RULE']}
	ON UPDATE {$details['UPDATE_RULE']}";

				return $create;
			}

			return false;
		}

		/**
		 * Get the ALTER statement for an InnoDB foreign key
		 *
		 * @param \SqlDiff\Database\Table $table
		 * @param                         $keyName
		 *
		 * @return bool|string
		 */
		public static function getFKAlterStatement(Table $table, $keyName)
		{
			$details = self::getFKDetails($table, $keyName);

			if($details)
			{
				$alter = "ALTER TABLE `{$details['CONSTRAINT_SCHEMA']}`.`{$details['TABLE_NAME']}`
	ADD CONSTRAINT `{$details['CONSTRAINT_NAME']}`
		FOREIGN KEY (`{$details['COLUMN_NAME']}`)
		REFERENCES `{$details['CONSTRAINT_SCHEMA']}`.`{$details['REFERENCED_TABLE_NAME']}` (`{$details['REFERENCED_COLUMN_NAME']}`)
		ON DELETE {$details['DELETE_RULE']}
		ON UPDATE {$details['UPDATE_RULE']};";

				return $alter;
			}

			return false;
		}
	}
