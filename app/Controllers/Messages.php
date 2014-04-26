<?php
/**
 * Class Messages
 * Controller for the messages
 * @author: Dave Slack <me@davidslack.co.uk>
 */

namespace Controllers;
use Builders\Form\Form;
use Builders\Table;

class Messages extends System\Controller
{
	function __construct()
	{
		// Model to use with this controller
		// NB: Must be before the parent constructor so we don't try to needlessly create a model
		// TODO: Move this to the parent controller so there is an automated model if it's found
		// TODO: Make sure to test $this->model exists first, then test a db table exists only then create the model
		$this->model = new \Models\Messages();

		parent::__construct();

		// Default view stuff
		$this->data['title'] = 'Messages';
		$this->data['content'] = "";
		$this->description = 'Messages - Lists all the messages in the system';
		$this->keywords = 'Messages';
	}

	/**
	 * The main function for the messages
	 */
	public function index()
	{
		// Get an array of messages back
		$messages = $this->model->getAll('id, description');

		// Create an empty table to add the messages server side datatable
		$tableData = array();
		$tableData['thead'] = array( 'ID', 'Description', 'Actions' );
		$tableData['tbody'] = $messages;
		//$tableData['tbody'] = array();
		$tableData['classes'] = 'table datatable table-striped';
		$tableData['actions'] = array('delete', 'edit', 'view');
		$tableData['id'] = 'messagesTable';
		$table = new Table( $tableData );

		// Add our new table to the content
		$this->data['content'] = $table->show();

		// Call the view with the the data to add in
		$this->show( 'index', $this->data );
	}

	/**
	 * Delete message function
	 * @param $params array 	Parameters passed in using the URL
	 */
	public function delete($params)
	{
		// The params should be an array and the first param should be the view ID
		if(is_array($params) && !empty($params))
		{
			// Get the ID
			$id = $params[0];

			// Make sure ID is a number
			if(is_numeric($id))
			{
				// Delete this id
				$this->model->delete($id);

				new \Builders\Messages(array('success', 'Message ' .$id .' has been deleted'));
			}
			else
			{
				new \Builders\Messages(array('error', "'" .$id ."' is not an id number so we could not delete anything"));
			}
		}
		else
			new \Builders\Messages(array('error', 'No ID sent so we do not know what message to delete.'));

		// Redirect back to the messages page
		$this->redirect('/admin/messages/');
	}

	public function add()
	{
		$this->data['title'] = 'Add messages';
		$this->data['content'] = "";

		$formData = array(
			'id' => 			'messagesForm',
			'classes' => 		'form-horizontal',
			'content' => array(
				'site' => 			array('type'=>'text','label'=>'Site','placeholder'=>'Choose the site'),
				'type' => 			array('type'=>'select','options'=>array('error','warning','success'), 'label'=>'Type'),
				'description' => 	array('type'=>'text','label'=>'Description'),
				'user_id' => 		array('type'=>'text','label'=>'User ID'),
				'role_id' => 		array('type'=>'text','label'=>'Role ID'),
				'created' => 		array('type'=>'date','label'=>'Created')
			),
		);

		// Create the form from the data
		$form = new Form();
		$form->createForm( $formData );

		// Add our new table to the content
		$this->data['content'] = (string)$form;

		// Call the view with the the data to add in
		$this->show( 'add', $this->data );

	}
} 