<?php
/**
 * Class Controller
 * This is the default controller and all other controllers should extend this one
 * @author: Dave Slack <me@davidslack.co.uk>
 */
namespace Controllers\System;

use Builders\Messages;
use Builders\Form\Form;

class Controller
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
		$this->data['title'] = $this->t('Default Title');
		$this->data['keywords'] = $this->t('Default keywords');
		$this->data['description'] = $this->t('Default - the default description');
		$this->data['bodyClass'] = $this->t('default');
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
		// If we have no title we should add one
		if(!isset($this->data['title']))
		{
			// Make sure we use the default template vars
			$this->defaultTemplateVars();
			$this->data['title'] = $this->t('Add');
		}
		else
			$this->data['title'] .= $this->t(' - Add');

		// If we have a modal set we can use the add form
		if(isset($this->model))
		{
			// Create the default form array (fount in $this->form)
			$this->createDefaultForm();

			// Create the form from the form data
			$form = new Form( $this->form );

			// Add our new form to the view content
			$this->data['content'] = (string)$form;

			// If the form has been sent and passes all the validation
			if($form->valid == true)
			{
				// Save the form data to the database
				$this->model->add($form->receivedData);

				// Tell the user all is fine
				new Messages(array('success', $this->t('Form was saved.')));
			}

			// Show the template with default data
			$this->show( 'index', $this->data );
		}
		else
		{
			$this->data['content'] = $this->t('Please add a model so we can add the default form.');

			// Show the template with default data
			$this->show( 'index', $this->data );
		}
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
		$this->data['title'] = $this->t('Edit');
		$this->data['content'] = $this->t('Add the default edit form here');

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
		$this->data['title'] = $this->t('Delete');
		$this->data['content'] = $this->t("Add the 'Are you sure?' here'");

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
		$this->data['title'] = $this->t('View');

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
	 * Output any array as json
	 * @param array $data
	 */
	protected function outputJson( $data = array())
	{
		header('Content-Type', 'application/json');
		print json_encode($data);
	}

	/**
	 * Output any array as json, but test for token and IP first
	 * @param array $params
	 * @param array $data
	 */
	protected function outputSecureJson($params, $data = array())
	{
		// If the token and the referring IP match we are good to go
		if($this->testToken($params))
		{
			header('Content-Type', 'application/json');
			print json_encode($data);
		}
	}

	/**
	 * Redirect the user
	 */
	protected function redirect($to)
	{
		header('Location: ' .$to);
		exit;
	}

	/**
	 * Put the view together
	 */
	protected function createView()
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
	protected function show( $view=NULL, $content=NULL )
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
		$this->outputJson(array('error',$this->t('Incorrect token or referring IP')));
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

			//var_dump($tableRowInfo);
			//exit;

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
						'value' => ''
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
					$this->form['content'][$rowInfo['Field']]['rules']['number'] = 'true';

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

					$help .= $this->t('A date and/or time should be used. ');
				}
				// If we have an enum field, then create a datetime row with a select list
				elseif( strpos($rowInfo['Type'], 'enum') !== false )
				{
					// Get the options from the enum
					$options = trim( $rowInfo['Type'], ")");
					$options = trim( $options, "enum(");
					$options = explode(',', $options);

					// Go through the options and format them correctly
					foreach($options as $key => $value)
					{
						$newKey = substr($value, 1, -1);
						$newTitle = ucfirst($newKey);
						$options[$newKey] = $newTitle;

						// Unset the old option
						unset($options[$key]);
					}

					// Create the select list
					$this->form['content'][$rowInfo['Field']] = array(
						'type'=>'select',
						'label' => ucfirst( str_replace('_', ' ', $rowInfo['Field']) ),
						'options'=>$options
					);

					$help .= $this->t('A date and/or time should be used. ');
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
				if($rowInfo['Null'] == 'NO' && $rowInfo['Extra'] != 'auto_increment')
				{
					// Required
					$this->form['content'][$rowInfo['Field']]['rules']['required'] = 'required';

					// Add the minlength
					$this->form['content'][$rowInfo['Field']]['rules']['minlength'] = '1';

					// Add a star to the label
					if(isset($this->form['content'][$rowInfo['Field']]['label']))
						$this->form['content'][$rowInfo['Field']]['label'] .= ' *';

					$help .= $this->t('This field is required. ');
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

						$help .= $this->t('A default of todays date (' .date(DATEFORMAT) .') can be used. ');
					}
					else
					{
						// Add the default as the value
						$this->form['content'][$rowInfo['Field']]['value'] = $rowInfo['Default'];
						$this->form['content'][$rowInfo['Field']]['placeholder'] = $rowInfo['Default'];

						$help .= $this->t("A default of '" .$rowInfo['Default'] ."' can be used. ");
					}
				}

				// Add help if needed
				if($help != '' && $this->form['content'][$rowInfo['Field']]['type'] != 'hidden')
					$this->form['content'][$rowInfo['Field']]['help'] = $help;

				// Add a value if we need to (will override the default)
				// TODO: Must add request and cleanup before we do this
			}

			// Add the submit button
			$this->form['content']['submit'] = array(
				'type'=>'submit',
				'label'=>'',
				'value'=>$this->t('Save')
			);
		}
	}


	/**
	 * TODO: Translation function
	 * Function will take in a string and test the language
	 * If the language is EN then the string is returned
	 * If the language is not EN then a lookup is made against the string and the language
	 * and the translated string is returned
	 * @param $string
	 * @return mixed
	 */
	protected function t( $string )
	{
		return $string;
	}
} 