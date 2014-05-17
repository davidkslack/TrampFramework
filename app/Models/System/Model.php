<?php
/**
 * Class Model
 * Default system model which all models should extend
 * @author: Dave Slack <me@davidslack.co.uk>
 *
 * Usage:
 */
namespace Models\System;
use Builders\Connect;
use Builders\QueryBuilder;

class Model extends Connect
{
	public $table;
	public $addData;

	public function __construct()
	{
		parent::__construct();

		// If we don't have a class name we can use the
		$className = explode( '\\', get_class($this) );
		$className = end( $className );
		$this->table = strtolower($className);
	}

	/**
	 * Add or update depending on if the id exists
	 */
	public function add($addData = array())
	{
		// Add the data to the obj
		$this->addData = $addData;

		// Is the data array empty
		if(empty($this->addData))
		{
			new \Builders\Messages(array('error', 'No data to add'));
		}
		elseif(isset($this->addData['id']))// If there is an id
		{
			// If there is an id check it exists
			$array = $this->get($this->addData['id']);

			if(!empty($array))
			{
				// It exists so update it
				$this->update();
			}
			else
			{
				// It doesn't exit so create it
				$this->create();
			}
		}
		else // No id so we assume this is new
		{
			$this->create();
		}
	}

	/**
	 * Create a new record
	 */
	public function create()
	{
		$this->query
			->insert($this->addData)
			->into($this->table);

		// Execute the query
		return $this->execute( $this->query->getQuery() );
	}

	/**
	 * Update an old record
	 */
	public function update()
	{

	}

	/**
	 * TODO: Need a WHERE
	 * @param null $select
	 * @return mixed
	 */
	public function getAll($select = NULL)
	{
		// Select everything if we have nothing to select
		if($select == NULL)
			$select = '*';

		// Create the query
		$query = $this->query
			->select($select)
			->from($this->table)
			->limit(50);

		// Execute the query
		return $this->execute( $query );
	}

	public function get( $id, $select = '*' )
	{
		// Create the query
		$this->query
			->select($select)
			->from($this->table);

		// If the id isn't an array then we can just use the it
		if(!is_array($id))
		{
			$this->query->where('id = ' .$id);
		}
		// Use the array to find the user
		else
		{
			// Create the where and any ANDs
			$where = '';
			foreach( $id as $key => $value )
			{
				$where .= ' AND ' .$key ." = '" .$value ."'";
			}
			$where = substr($where, 5);

			// Add the WHERE to the query
			$this->query->where($where);
		}

		// Only return 1
		$this->query->limit(1);

		// Get the query to run
		$query = (string)$this->query;

		// Execute the query
		$arr = $this->execute( $this->query );

		return $arr[0];
	}

	public function delete($where)
	{
		if(!is_array($where))
		{
			$where = 'id = ' .$where;
		}
		else
		{
			$whereStr = '';
			foreach( $where as $key => $value )
			{
				$whereStr .= ' AND ' .$key ." = '" .$value ."'";
			}
			$where = substr($whereStr, 5);
		}

		// Create the delete query
		$this->query->delete()->from($this->table)->where($where);

		// Execute the deletion
		$this->execute( $this->query );
	}

	/**
	 * Describe the table
	 * @return mixed
	 */
	public function describe()
	{
		//return $this->execute( 'DESCRIBE ' .$this->table );
		return $this->execute( 'SHOW FULL COLUMNS FROM ' .$this->table );
	}

	/**
	 * Describe the table
	 * Shows the same as describe, but has more info eg comments
	 * @return mixed
	 */
	public function describeExtra()
	{
		return $this->execute( 'SHOW FULL COLUMNS FROM ' .$this->table );
	}

}