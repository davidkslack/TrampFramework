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
		// Create the template obj
		$this->template = new Template();

		// Get the correct view to use with the template
		$this->viewFile = $view;

		// Create messages
		$messageBuilder = new Messages();
		$this->viewMessages = $messageBuilder->showMessages();

		// Create the view
		$this->createView();

		// Put the content array into the view
		ob_start();
		include $this->view;
		$content = ob_get_contents();
		ob_end_clean();

		// Output the Template
		include( $this->template->templateFile );
	}

	/**
	 * Index function
	 * This function should be overridden to show the basic page
	 */
	public function index()
	{
		$this->data['title'] = 'Default view';
		$this->description = 'Messages - Lists all the messages in the system';
		$this->keywords = 'Messages';

		$this->show( NULL, $this->data );
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
	public function testToken($params)
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
} 