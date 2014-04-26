<?php
/**
 * Table Builder
 * Add in an array and build a table.
 * If the array is a multidimensional array and has a thead row then this will be the header.
 * @author: Dave Slack <me@davidslack.co.uk>
 */
namespace Builders;
class Table
{
	private $table;
	private $data;
	private $thead;
	private $tbody;

	function __construct( $data )
	{
		// Get the data to use in the object
		$this->data = $data;

		// Check if we need to add actions and create
		if( isset( $data['actions'] ) && is_array( $data['actions'] ) )
			$this->actions();

		// Check if we have a table head and create
		if( isset( $data['thead'] ) && is_array( $data['thead'] ) )
			$this->tableHead();

		// If we don't have a body we can still try to create the table from the data
		if( !isset( $this->data['tbody'] ) )
			$this->data['tbody'] = $data;

		// Create the table body
		$this->tableBody();

		// Create the table
		$this->create();
	}

	/**
	 * Create the table
	 */
	private function create()
	{
		// Start
		$this->table = '<table';

		// Add an ID
		if( isset( $this->data['id'] ) )
			$this->table .= ' id="' .$this->data['id'] .'"';

		// Add classes
		if( isset( $this->data['classes'] ) )
			$this->table .= ' class="' .$this->data['classes'] .'"';

		// Finish the table
		$this->table .= '>' .$this->thead .$this->tbody .'</table>';
	}

	/**
	 * Create the head of the table
	 */
	private function tableHead()
	{
		foreach( $this->data['thead'] as $th )
			$this->thead .= "<th>$th</th>";

		$this->thead = '<thead><tr>' .$this->thead ."</tr></thead>";
	}

	/**
	 * Create the body of the table
	 */
	private function tableBody()
	{
		foreach( $this->data['tbody'] as $tbody )
		{
			$this->tbody .= '<tr>';

			foreach( $tbody as $td )
			{
				$this->tbody .= '<td>' .$td .'</td>';
			}

			$this->tbody .= '</tr>';
		}

		// Add the tbody tags
		$this->tbody = '<tbody>' .$this->tbody ."</tbody>";
	}

	/**
	 * Show or view the table
	 * @return string
	 */
	public function show()
	{
		return $this->table;
	}

	/**
	 * Take in the table data and add the actions (edit, delete, etc) as needed
	 */
	private function actions()
	{
		// So we can build the actions we need the uri
		$uri = $_SERVER['REQUEST_URI'];
		$uri = rtrim($uri,"/"); // If there is a trailing slash we remove it

		foreach($this->data['tbody'] as $key => $value)
		{
			$this->data['tbody'][$key]['action'] = '';

			foreach($this->data['actions'] as $type)
				$this->data['tbody'][$key]['action'] .= ' | <a href="' .$uri .'/' .$type .'/' .$value['id'] .'">' .$type .'</a>';

			$this->data['tbody'][$key]['action'] = substr($this->data['tbody'][$key]['action'], 3);
		}
	}
}