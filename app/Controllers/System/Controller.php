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
	 * Default to be overridden
	 */
	public function add()
	{
		// Make sure we use the default template vars
		$this->defaultTemplateVars();

		// Edit the template vars
		$this->data['title'] = 'Add';
		$this->data['content'] = 'Add the default add form here';

		// Show the template with default data
		$this->show( NULL, $this->data );
	}

	/**
	 * Default to be overridden
	 * @param $params array 	These are the parameters passed in via the query string
	 */
	public function edit($params)
	{
		// Make sure we use the default template vars
		$this->defaultTemplateVars();

		// Edit the template vars
		$this->data['title'] = 'Edit';
		$this->data['content'] = 'Add the default edit form here';

		// Show the template with default data
		$this->show( 'index', $this->data );
	}

	/**
	 * Default to be overridden
	 * @param $params array 	These are the parameters passed in via the query string
	 */
	public function delete($params)
	{
		// Make sure we use the default template vars
		$this->defaultTemplateVars();

		// Edit the template vars
		$this->data['title'] = 'Delete';
		$this->data['content'] = "Add the 'Are you sure?' here'";

		// Show the template with default data
		$this->show( NULL, $this->data );
	}

	/**
	 * Default to be overridden
	 * @param $params array 	These are the parameters passed in via the query string
	 */
	public function view($params)
	{
		// Make sure we use the default template vars
		$this->defaultTemplateVars();

		// Edit the template vars
		$this->data['title'] = 'View';

		// The params should be an array and the first param should be the view ID
		$id = 0;
		if(is_array($params)) $id = $params[0];

		// Get the array by the ID
		$array = $this->model->get($id);

		foreach($array as $property => $value)
			$this->data['content'] .= $property .': <strong>' .$value .'</strong><br>';

		// Show the template with default data
		$this->show( 'index', $this->data );
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
	 * TODO: Move into own class??
	 */
	protected function createDefaultForm()
	{
		// Test if there is a model
		if(is_object($this->model))
		{
			// Get the default table info
			//$tableRowInfo = $this->model->describe(); // Faster / less info
			$tableRowInfo = $this->model->describeExtra(); // Slower / more info

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
				// Help
				$help = '';

				// If there is a message we should add it to the help
				if(isset($rowInfo['Comment']) && $rowInfo['Comment'] != '')
					$help .= $rowInfo['Comment'] .'. ';

				// Hide the ID field if there is one
				if($rowInfo['Field'] == 'id' && $rowInfo['Key'] == 'PRI' &&  $rowInfo['Extra'] == 'auto_increment' )
				{
					// Basic row in the form
					$this->form['content'][$rowInfo['Field']] = array(
						'type' => 'hidden',
						'value' => 'NULL'
					);
				}
				// If we have an int, then create a number row
				elseif( strpos($rowInfo['Type'], 'int') !== false )
				{
					$this->form['content'][$rowInfo['Field']] = array(
						'type' => 'number',
						'label' => ucfirst( str_replace('_', ' ', $rowInfo['Field']) ),
					);

					// If this field must be a number then add the rule for numbers only
					$this->form['content'][$rowInfo['Field']]['rules']['numbers'] = 'true';

					$help .= 'A number only should be used. ';
				}
				// If we have a text field, then create a textarea row
				elseif( strpos($rowInfo['Type'], 'text') !== false )
				{
					$this->form['content'][$rowInfo['Field']] = array(
						'type' => 'textarea',
						'label' => ucfirst( str_replace('_', ' ', $rowInfo['Field']) )
					);
				}
				// If we have a timestamp field, then create a datetime row with default as today
				elseif( strpos($rowInfo['Type'], 'timestamp') !== false )
				{
					$this->form['content'][$rowInfo['Field']] = array(
						'type'=>'datetime',
						'label' => ucfirst( str_replace('_', ' ', $rowInfo['Field']) ),
						'classes'=>'datetime form-control',
					);

					$help .= 'A date and/or time should be used. ';
				}
				else
				{
					// Basic row in the form
					$this->form['content'][$rowInfo['Field']] = array(
						'type' => 'text',
						'label' => ucfirst( str_replace('_', ' ', $rowInfo['Field']) )
					);
				}

				// Add required if needed
				if($rowInfo['Null'] == 'NO')
				{
					// Required
					$this->form['content'][$rowInfo['Field']]['rules']['required'] = 'required';

					// Add the minlength
					$this->form['content'][$rowInfo['Field']]['rules']['minlength'] = '1';

					// Add a star to the label
					if(isset($this->form['content'][$rowInfo['Field']]['label']))
						$this->form['content'][$rowInfo['Field']]['label'] .= ' *';

					$help .= 'This field is required. ';
				}

				// Add the max if needed
				if(strpos($rowInfo['Type'], '('))
				{
					// Get the number from the field
					$number =  filter_var($rowInfo['Type'], FILTER_SANITIZE_NUMBER_INT);

					// Add the maxlength
					$this->form['content'][$rowInfo['Field']]['rules']['maxlength'] = $number;

					$help .= 'Must be a MAX of ' .$number .'. ';
				}

				// Add the default if needed
				if(isset($rowInfo['Default']))
				{
					if($rowInfo['Default'] == 'CURRENT_TIMESTAMP')
					{
						// Add the date
						$this->form['content'][$rowInfo['Field']]['value'] = date(DATEFORMAT);
						$this->form['content'][$rowInfo['Field']]['placeholder'] = date(DATEFORMAT);

						$help .= 'A default of todays date (' .date(DATEFORMAT) .') can be used. ';
					}
					else
					{
						// Add the default as the value
						$this->form['content'][$rowInfo['Field']]['value'] = $rowInfo['Default'];
						$this->form['content'][$rowInfo['Field']]['placeholder'] = $rowInfo['Default'];

						$help .= "A default of '" .$rowInfo['Default'] ."' can be used. ";
					}
				}

				// Add help if needed
				if($help != '' && $this->form['content'][$rowInfo['Field']]['type'] != 'hidden')
					$this->form['content'][$rowInfo['Field']]['help'] = $help;
			}

			// Add the submit button
			$this->form['content']['submit'] = array(
				'type'=>'submit',
				'label'=>'',
				'value'=>'Save'
			);

		}
	}
} 