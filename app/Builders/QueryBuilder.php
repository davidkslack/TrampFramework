<?php
/**
 * Query Builder
 * Builds querys for use in the models.
 * First created by D Haarbrink in 2010 and updated by Dave Slack in 2014
 * @author: D Haarbrink <dhaarbrink@gmail.com>
 * @author: Dave Slack <me@davidslack.co.uk>
 * @link: https://code.google.com/p/php-query-builder/wiki/BasicUsage
 */
namespace Builders;

/**
 * Class QueryBuilder
 */
class QueryBuilder
{
	const TYPE_SELECT = 'select';
	const TYPE_DELETE = 'delete';
	const TYPE_INSERT = 'insert';
    protected $type;
    protected $select_columns = array();
    protected $tables = array();
    protected $where;
    protected $limit;
    protected $joins;
    protected $groups;
    protected $orders;
    protected $params;
	protected $values;

	/**
	 * @return QueryBuilder
	 */
	public static function create()
	{
		return new self();
	}

	/**
	 * We need the columns to insert to and the values to insert
	 * @param $array
	 * @return $this
	 */
	public function insert($array)
	{
		$columns = array();
		$values = array();

		foreach($array as $key => $value)
		{
			$columns[] = $key;
			$values[] = $value;
		}

		$this->type = self::TYPE_INSERT;
		$this->addSelects($columns);
		$this->setValues($values);
		return $this;
	}

	public function delete()
	{
		$this->type = self::TYPE_DELETE;
		return $this;
	}

	/**
	 * @param $columns
	 * @return $this
	 */
	public function select($columns)
    {
        $this->type = self::TYPE_SELECT;
        $this->addSelects($columns);
        return $this;
    }

	/**
	 * @param $tables
	 * @return $this
	 */
	public function from($tables)
    {
        $this->addFroms($tables);
        return $this;
    }

	/**
	 * @param $tables
	 * @return $this
	 */
	public function into($tables)
	{
		$this->addFroms($tables);
		return $this;
	}

	/**
	 * @param $columns
	 * @return $this
	 */
	public function addSelects($columns)
    {
        $this->select_columns = array_merge($this->select_columns, $this->getAliasedNames($columns));
        return $this;
    }

	/**
	 * @param $tables
	 * @return $this
	 */
	public function addFroms($tables)
    {
        $this->tables = array_merge($this->tables, $this->getAliasedNames($tables));
        return $this;
    }

	/**
	 * @param $alias
	 * @param $table
	 */
	public function setTable($alias, $table)
    {
        $this->tables[$alias] = $table;
    }

	/**
	 * @param $expr
	 * @return $this
	 */
	public function where($expr)
    {
        $this->where = (trim($expr) !== '' ? $expr : null);
        return $this;
    }

	/**
	 * @param $tables
	 * @param $join_type
	 * @param $join_expression
	 * @return $this
	 */
	public function join($tables, $join_type, $join_expression)
    {
        $this->joins = array();
        return $this->addJoin($tables, $join_type, $join_expression);
    }

	/**
	 * @param $tables
	 * @param $join_type
	 * @param $join_expression
	 * @return $this
	 */
	public function addJoin($tables, $join_type, $join_expression)
    {
        $tables = $this->getAliasedNames($tables);
        $this->joins[] = array('tables' => $tables, 'type' => $join_type, 'expression' => $join_expression);
        return $this;
    }

	/**
	 * @return $this
	 */
	public function limit()
    {
        $args = func_get_args();
        if(count($args) == 1)
        {
            $this->limit = $args[0];
        }
        else
        {
            $this->limit = "{$args[0]},{$args[1]}";
        }
        return $this;
    }

	/**
	 * @param $list
	 * @return $this
	 */
	public function group($list)
    {
        $this->groups = $this->getAliasedNames($list);
        return $this;
    }

	/**
	 * @param $list
	 * @return $this
	 */
	public function order($list)
    {
        $this->orders = $this->getAliasedNames($list);
        return $this;
    }

	/**
	 * Make sure we have an array, not a comma separated list
	 * @param $inputs
	 * @return array
	 */
	protected function getAliasedNames($inputs)
    {
        $clean = array();
        if(!is_array($inputs))
        {
            $inputs = explode(',', $inputs);
            foreach($inputs as $input)
            {
                $input = trim($input);
                $clean = array_merge($clean, $this->separateAlias($input));
            }
        }
        else
        {
            $clean = $inputs;
        }
        return $clean;
    }

	/**
	 * @param $name
	 * @return array
	 */
	protected function separateAlias($name)
    {
        if(strpos($name, ' as ') !== false) // has an 'as' keyword in it
        {
            list($colname, $alias) = explode(' as ', $name, 2);
            return array($alias => $colname);
        }
        elseif(stripos($name, ' ') !== false) // has a space in it
        {
            list($colname, $alias) = explode(' ', $name);
            return array($alias => $colname);
        }
        else
        {
            $colname = $name;
            return array($colname);
        }
    }

	/**
	 * @return mixed
	 */
	public function __toString()
    {
        return $this->getQuery();
    }

	/**
	 * @return mixed
	 */
	public function getQuery()
    {
        $sc = new SQLConstructor();
        return $sc->create($this);
    }

	/**
	 * @return mixed
	 */
	public function getType()
    {
        return $this->type;
    }

	/**
	 * @return array
	 */
	public function getSelectColumns()
    {
        return $this->select_columns;
    }

	/**
	 * @return array
	 */
	public function getTables()
    {
        return $this->tables;
    }

	/**
	 * @return mixed
	 */
	public function getWhere()
    {
        return $this->where;
    }

	/**
	 * @return mixed
	 */
	public function getLimit()
    {
        return $this->limit;
    }

	/**
	 * @return mixed
	 */
	public function getJoins()
    {
        return $this->joins;
    }

	/**
	 * @return mixed
	 */
	public function getGroups()
    {
        return $this->groups;
    }

	/**
	 * @return mixed
	 */
	public function getOrders()
    {
        return $this->orders;
    }

	/**
	 * @param $params
	 * @return $this
	 */
	public function setParams($params)
    {
        $this->params = $params;
        return $this;
    }

	/**
	 * @return mixed
	 */
	public function getParams()
    {
        return $this->params;
    }

	/**
	 * @param mixed $values
	 */
	public function setValues($values)
	{
		$this->values = $values;
	}

	/**
	 * @return mixed
	 */
	public function getValues()
	{
		return $this->values;
	}

	/**
	 * @param $column
	 * @return string
	 */
	public static function quote($column)
    {
        return "`" . str_replace('.', '`.`', $column) . "`";
    }
}

