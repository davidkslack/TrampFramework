<?php
/**
 * Class Controller
 * This is the default controller and all other controllers should extend this one
 * @author: Dave Slack <me@davidslack.co.uk>
 */
namespace Controllers\System;

use Builders\Messages;

class Controller //extends Template
{
	private $viewFile;
	private $view = '';
	private $template;

	public $data = array();
	public $viewMessages = '';
	public $DBConnection;
	public $model;

	protected $form = array();

	/**
	 * The default template vars
	 */
	private function defaultTemplateVars()
	{
		$this->data['title'] = 'Default Title';
		$this->data['keywords'] = 'Default keywords';
		$this->data['description'] = 'Default - the default description';
		$this->data['bodyClass'] = 'default';
	}

	/**
	 * Index function
	 * This function should be overridden to show the basic page
	 */
	public function index()
	{
		// Make sure we use the default template vars
		$this->defaultTemplateVars();

		// Show the template with default data
		$this->show( NULL, $this->data );
	}

	/**
	 * Put the view together
	 */
	public function createView()
	{
		if($this->viewFile != NULL)
		{
			$className = explode( '\\', get_class($this) );
			$className = end( $className );
			$this->view = HOME .DS .'app' .DS .'Views' .DS .$className .DS .$this->viewFile .'.php';
		}
		else
			$this->view = HOME .DS .'app' .DS .'Views' .DS .'Main' .DS .'default.php';
	}

	/**
	 * Show the template
	 */
	public function show( $view=NULL, $content=NULL )
	{
		// Create messages to show in the view
		$messageBuilder = new Messages();
		$content['messages'] = $messageBuilder->showMessages();

		// The view we want to show
		$this->viewFile = $view;

		// Create the view
		$this->createView();

		// Create the template
		$this->template = new Template($this->view, $content);
	}

	/**
	 * Redirect the user
	 */
	public function redirect($to)
	{
		header('Location: ' .$to);
		exit;
	}

	/**
	 * View all the info on 1 thing
	 * @param $params array 	These are the parameters passed in via the query string
	 */
	public function view($params)
	{
		// The params should be an array and the first param should be the view ID
		$id = 0;
		if(is_array($params)) $id = $params[0];

		// Get the array by the ID
		$array = $this->model->get($id);

		foreach($array as $property => $value)
			$this->data['content'] .= $property .': <strong>' .$value .'</strong><br>';

		// Call the view with the the data to add in
		$this->show( 'index', $this->data );
	}

	/**
	 * Output any array as json
	 * @param array $data
	 */
	public function outputJson( $data = array())
	{
		header('Content-Type', 'application/json');
		print json_encode($data);
	}

	/**
	 * Output any array as json, but test for token and IP first
	 * @param array $params
	 * @param array $data
	 */
	public function outputSecureJson($params, $data = array())
	{
		// If the token and the referring IP match we are good to go
		if($this->testToken($params))
		{
			header('Content-Type', 'application/json');
			print json_encode($data);
		}
	}

	/**
	 * Test we have the correct token
	 * @param $params
	 * @return bool
	 * TODO: This should come from the DB and test the URI the request was made
	 */
	protected function testToken($params)
	{
		// These should be tested against the DB
		$apiToken = 'rog85dvnt8e9btv3rsaas';
		$referringIP = '127.0.0.1';

		// The token and refering IP match the ones found
		if(isset($params[0]) && $params[0] == $apiToken)
		{
			if($_SERVER['REMOTE_ADDR'] == $referringIP)
			{
				return true;
			}
		}

		// Error if anything is wrong
		$this->outputJson(array('error','Incorrect token or referring IP'));
		exit;
	}

	/**
	 * Create a default form
	 * If we have a model we can assume we will need to edit/add to the model
	 */
	protected function createDefaultForm()
	{
		// Test if there is a model
		if(is_object($this->model))
		{
			// Get the default table info
			$tableRowInfo = $this->model->describe();

			// Get the class name
			$className = explode( '\\', get_class($this) );
			$className = end( $className );

			// Basic info
			$this->form = array(
				'id' => $className .'Form',
				'classes' => 'validate form-horizontal'
			);

			// Loop through the table and create the from
			foreach($tableRowInfo as $rowInfo)
			{
				//$rowInfo
				$this->form['content'][$rowInfo['Field']] = array(
						'type' => 'text',
						'label' => ucfirst( str_replace('_', ' ', $rowInfo['Field']) )

				);
			}
		}
	}
} 