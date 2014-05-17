<?php
/**
 * DB Connection
 * The class to connect to the database
 * @author: Dave Slack <me@davidslack.co.uk>
 */

namespace Builders;


class Connect {
	private $database_host;
	private $database_user;
	private $database_pass;
	private $database_db;
	public $query;

	public $isConnected;
	protected $data;

	/**
	 * Create the connection
	 * @param array $options
	 * @throws \Exception
	 */
	public function __construct($options=array())
	{
		// Get the settings
		$this->database_host = DB_HOST;
		$this->database_user = DB_USER;
		$this->database_pass = DB_PASS;
		$this->database_db = DB_NAME;

		$this->isConnected = true;
		try {
			$this->data = new \PDO("mysql:host={$this->database_host};dbname={$this->database_db};charset=utf8", $this->database_user, $this->database_pass, $options);
			$this->data->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
			$this->data->setAttribute(\PDO::ATTR_DEFAULT_FETCH_MODE, \PDO::FETCH_ASSOC);
		}
		catch(\PDOException $e) {
			$this->isConnected = false;
			throw new \Exception($e->getMessage());
		}

		// Create the queryBuilder obj
		$this->query = new QueryBuilder();
	}

	/**
	 * Disconnect from the DB to force no usage
	 */
	public function Disconnect()
	{
		$this->data = null;
		$this->isConnected = false;
	}

	/**
	 * Pass in some SQL and get out an array of arrays
	 * @param $query
	 * @param array $params
	 * @return mixed
	 * @throws \Exception
	 */
	public function execute($query, $params=array())
	{
		try{
			$stmt = $this->data->prepare($query);
			$stmt->execute($params);

			// If we are using a select string then show the results
			$select = strpos($stmt->queryString, 'SELECT');
			if($select !== false)
				return $stmt->fetchAll();

			// If we are using a select string then show the results
			$describe = strpos($stmt->queryString, 'DESCRIBE');
			if($describe !== false)
				return $stmt->fetchAll();

			// If we are using a select string then show the results
			$describe = strpos($stmt->queryString, 'SHOW FULL COLUMNS');
			if($describe !== false)
				return $stmt->fetchAll();

		}catch(\PDOException $e){
			throw new \Exception($e->getMessage());
		}

		return false;
	}
} 