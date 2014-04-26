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
				'type' => array(
					'type'=>'select',
					'label'=>'Type',
					'selected'=> 'warning',
					'validation'=>'error',
					'options'=>array(
						'error'=> 	'Error',
						'warning'=>	'Warning',
						'info'=> 	'Info'
					)
				),
				'type2' => array(
					'type'=>'radio',
					'label'=>'Type',
					'selected'=> 'error',
					'validation'=>'warning',
					'options'=>array(
						'error'=> 	'Error',
						'warning'=>	'Warning',
						'info'=> 	'Info'
					)
				),
				'type3' => array(
					'type'=>'checkbox',
					'label'=>'Type',
					'validation'=>'success',
					'selected'=> array('error','info'),
					'options'=>array(
						'error'=> 	'Error',
						'warning'=>	'Warning',
						'info'=> 	'Info'
					)
				),
				'type4' => array(
					'type'=>'multiple',
					'label'=>'Type',
					'selected'=> 'warning',
					'options'=>array(
						'error'=> 	'Error',
						'warning'=>	'Warning',
						'info'=> 	'Info'
					)
				),
				'description' => array(
					'type'=>'textarea',
					'label'=>'Description',
					'rows'=>6,
					'disabled'=>true,
					'value'=>'A nice default value'
				),
				'user_id' => array(
					'type'=>'number',
					'label'=>'User ID'
				),
				'created' => array(
					'type'=>'date',
					'label'=>'Created'
				),
				'a_button' => array(
					'type'=>'button',
					'label'=>'A button',
					'value'=>'This is a button'
				),
				'file' => array(
					'type'=>'file',
					'label'=>'A file'
				)
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