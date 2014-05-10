<?php
/**
 * Class User
 * Testing the system using the User
 * @author: Dave Slack <me@davidslack.co.uk>
 */
namespace Controllers;
use Builders\Table;
use Models\Admin;

class User extends System\Controller
{
	function __construct()
	{
		// Model to use with this controller
		$this->model = new Admin();

		// Default view stuff
		$this->data['title'] = 'Admin User';
		$this->data['content'] = "<p>This is the admin user page.</p>";
	}

	/**
	 * Add a user
	 * TODO: Finish the add user page using the form builder
	 * @param $params array 	These are the parameters passed in via the query string
	 */
	public function add( $params )
	{
		// Put together the content array for use with the Template
		$this->data['title'] = 'Admin User Add';
		$this->data['content'] = "<p>This is the user add page.</p>";

		// Call the view with the the data to add in
		$this->show( 'index', $this->data );
	}

	/**
	 * Delete a user by the ID
	 * TODO: Finish the delete admin user page
	 * @param $params array 	These are the parameters passed in via the query string
	 */
	public function delete($params)
	{
		$this->data['title'] = 'Delete admin user';
		$this->data['content'] = "<p>This is the delete user page.</p>";

		// The params should be an array and the first param should be the view ID
		$id = 0;
		if(is_array($params)) $id = $params[0];

		// Delete this id
		$this->model->delete($id);
		//$this->model->delete(array('id'=>$id));

		// Call the view with the the data to add in
		// TODO: Tell the user about the deletion then redirect
		$this->show( 'index', $this->data );
	}

	/**
	 * Edit a user
	 * TODO: Finish the edit user page using the form builder
	 * @param $params array 	These are the parameters passed in via the query string
	 */
	public function edit( $params )
	{
		// Create the content for the template
		$content = '<p>Edit the user</p>';

		if(is_array($params))
		foreach($params as $param)
		{
			$content .= $param .'<br>';
		}

		// Put together the content array for use with the Template
		$this->data['title'] = 'Admin User Edit';
		$this->data['content'] = $content;

		// Call the view with the the data to add in
		$this->show( 'index', $this->data );
	}

	/**
	 * View all the info on an Admin user
	 * @param $params array 	These are the parameters passed in via the query string
	 */
	public function view($params)
	{
		// Put together the content array for use with the Template
		$this->data['title'] = 'View user ';
		$this->data['content'] = '';

		// The params should be an array and the first param should be the view ID
		$id = 0;
		if(is_array($params)) $id = $params[0];

		// Get the user by the ID
		$user = $this->model->get($id);

		foreach($user as $property => $value)
			$this->data['content'] .= $property .': <strong>' .$value .'</strong><br>';

		// Call the view with the the data to add in
		$this->show( 'index', $this->data );
	}

	/**
	 * Show all the users in a table with the actions that can be performed
	 */
	public function index()
	{
		// Create the basic info to be used in the view
		$this->data['title'] = 'Admin Users';
		$this->headerTitle = 'Admin Users';
		$this->description = 'Admin Users - Lists all the users in the system that can login to the Admin';
		$this->keywords = 'Admin, Users';

		// Get an array of users back
		$users = $this->model->getAll('id, username');

		// Create a table to view the users
		$tableData = array();
		$tableData['thead'] = array( 'ID', 'Email', 'Actions' ); // AirConnect
		$tableData['tbody'] = $users;
		$tableData['classes'] = 'table datatable table-striped';
		$tableData['actions'] = array('delete','edit','view');
		$table = new Table( $tableData );

		// Add our new table to the content
		$this->data['content'] = $table->show();

		// Call the view with the the data to add in
		$this->show( 'index', $this->data );
	}
}