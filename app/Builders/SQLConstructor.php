<?php
/**
 * Query Builder
 * The mySQL part of the query builder. For other DB types this page should be copied and replicated
 * First created by D Haarbrink in 2010 and updated by Dave Slack in 2014
 * @author: D Haarbrink <dhaarbrink@gmail.com>
 * @author: Dave Slack <me@davidslack.co.uk>
 * @link: https://code.google.com/p/php-query-builder/wiki/BasicUsage
 */
namespace Builders;

class SQLConstructor
{
    protected $qb;

	/**
	 * @param QueryBuilder $qb
	 * @return string
	 */
	public function create(QueryBuilder $qb)
    {
        $this->qb = $qb;
        switch($qb->getType())
        {
			case QueryBuilder::TYPE_SELECT:
				return $this->createSelect();
				break;
			case QueryBuilder::TYPE_DELETE:
				return $this->createDelete();
				break;
			case QueryBuilder::TYPE_INSERT:
				return $this->createInsert();
				break;
        }

		return false;
    }

	protected function createDelete()
	{
		$sql = 'DELETE ';
		$sql .= "\n FROM ";
		$sql .= $this->getColumns($this->qb->getTables());
		$sql .= "\n WHERE ";
		$sql .= (string)$this->qb->getWhere();
		return $sql;
	}

	protected function createInsert()
	{
		$sql = 'INSERT INTO ';
		$sql .= $this->getColumns($this->qb->getTables());
		$sql .= " (";
		$sql .= $this->getColumns($this->qb->getSelectColumns());
		$sql .= ") ";
		$sql .= "\n VALUES (";
		$sql .= $this->getValues($this->qb->getValues());
		$sql .= ") ";
		return $sql;
	}

	/**
	 * @return string
	 */
	protected function createSelect()
    {
        $sql = 'SELECT ';
        $sql .= $this->getColumns($this->qb->getSelectColumns());
        $sql .= "\n FROM ";
        $sql .= $this->getColumns($this->qb->getTables());
        if(null !== $joins = $this->qb->getJoins())
        {
            foreach($joins as $join)
            {
                $sql .= "\n JOIN ";
                $sql .= $this->getColumns($join['tables']);
                $sql .= " {$join['type']} ";
                $sql .= $join['expression'];
            }
        }
        if(null !== $where = $this->qb->getWhere())
        {
            $sql .= "\n WHERE ";
            $sql .= (string)$where;
        }
        if(null !== $group = $this->qb->getGroups())
        {
            $sql .= "\n GROUP BY ";
            $sql .= $this->getColumns($group);
        }
        if(null !== $orders = $this->qb->getOrders())
        {
            $sql .= "\n ORDER BY ";
            $sql .= $this->getColumns($orders);
        }
        if(null !== $limit = $this->qb->getLimit())
        {
            $sql .= "\n LIMIT $limit";
        }
        
        return $sql;
    }

	/**
	 * @param $columns
	 * @return string
	 */
	protected function getColumns($columns)
    {
        $clean = array();
        foreach($columns as $alias => $column)
        {
            if(!is_int($alias))
            {
                $clean[] = $this->prepareColumn($column) . " as $alias";
            }
            else
            {
                if((!is_scalar($column)) || (is_scalar($column) && $column == '*'))
                {
                    $clean[] = $this->prepareClean($column);
                }
                else 
                {
                    $clean[] = $this->prepareColumn($column);
                }
            }
        }
        return implode(',', $clean);
    }

	protected function getValues($values)
	{
		$clean = array();
		foreach($values as $value)
		{
			// TODO: Add some validation to stop slashes and html/script in the DB?
			$value = addslashes($value);
			$clean[] = "'$value'";
		}
		return implode(',', $clean);
	}

	/**
	 * @param $column
	 * @return string
	 */
	protected function prepareColumn($column)
    {
        return "" . str_replace('.', '.', $column) . "";
    }

	/**
	 * @param $column
	 * @return mixed
	 */
	protected function prepareClean($column)
    {
        return $column;
    }
}