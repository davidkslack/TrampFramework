<?php
/**
 * Form Builder
 * Add in an array and build a form.
 * @author: Dave Slack <me@davidslack.co.uk>
 * Usage:
 *  	Create a new object of \Builders\Form\Form and add an array to the createForm method like below
 * 			$form = new Form( $formData );
 *
 * 		The key of the array is the name of the input so cannot contain spaces
 *
 * 		The array can contain:
 * 			id 			The CSS id of the form
 *  		classes 	CSV of the classes of the form (default to form-horizontal)
 * 			action 		The uri the form will post to (default to self)
 * 			method 		The method (POST or GET) the action will use (default to POST)
 *  		content 	An array containing each of the groups in the form (see below)
 *
 * 			The content array will hold an array of all the content the form can have in groups
 * 			type 		The type of from content this group will hold (default to text)
 * 							text 		select 		submit
 * 							multiple 	number 		email
 * 							button 		radio 		checkbox
 * 							textarea	file 		date
 * 			label 		Shown on the left of the input to name it. The user can click to select the input (default is empty)
 * 			selected 	If there are options the one selected
 * 			help 		Help text about this group
 * 			options 	On a select, radio or tickbox the options array are all the options to show
 * 			value 		On input boxes the default value
 *			validation 	An array holding the 'type' error, warning or success and 'message'
 * 				'type' will change the style of the group and message will show with the help
 * 			rules 		An array holding any rules for the form including required, min, max, pattern or any other html5
 *  			or jQuery validation rules
 *
 * 			NB. If we have a group with a type file then enctype="multipart/form-data" is added
 *
 */
namespace Builders\Form;
class Form
{
	private $form ='';
	private $method = 'POST';
	private $formData = array();
	private $formContent = '';
	private $formEnctype = '';
	private $groupPlaceHolder = '';
	private $groupPlainID = '';
	private $groupID = '';
	private $groupClasses = '';
	private $groupName = '';
	private $groupData = array();
	private $groupRows = 3;
	private $groupSelected = '';
	private $groupValidation = '';
	private $groupMultiple = '';
	private $groupDisabled = '';
	private $groupValue = '';
	private $groupHelp = '';
	private $groupRules = '';
	private $receivedData = array();

	function __construct($formData = NULL)
	{
		// Create the form
		if($formData != NULL)
			$this->createForm($formData);
	}

	/**
	 * Validate the form
	 */
	private function validate()
	{
		//var_dump($this->receivedData);
		//exit;
	}

	/**
	 * Get the data from the correct method and forget the wrong method
	 */
	private function getReceivedData()
	{
		if($this->method == 'GET' || $this->method == 'get')
		{
			if(!empty($_GET))
				$this->receivedData = $_GET;
		}
		elseif(!empty($_POST))
			$this->receivedData = $_POST;
	}

	/**
	 * Create the form
	 * @param array $formData
	 */
	public function createForm( $formData = array() )
	{
		// Get the data
		$this->getReceivedData();

		// If we have data back from the form
		if(!empty($this->receivedData))
		{
			// Validate the form
			$this->validate();
		}

		// Add the data to the object
		$this->formData = $formData['content'];

		// Create the vars to use in the view
		$id = (isset($formData['id'])) ? ' id="' .$formData['id'] .'"' : '';
		$classes = (isset($formData['classes'])) ? ' class="' .$formData['classes'] .'"' : ' class="form-horizontal"';
		$action  = (isset($formData['action'])) ? ' action="' .$formData['action'] .'"' : '';
		$method = (isset($formData['method'])) ? ' method="' .$formData['method'] .'"' : ' method="POST"';
		$formContent = $this->formContent();
		$enctype = $this->formEnctype; // If we have a file type we need to change the encoding

		$this->method = $method;

		// Create the form view
		ob_start();
		include 'Views/form.html.php';
		$this->form = ob_get_contents();
		ob_end_clean();
	}

	/**
	 * Create the form content to pass into the form
	 * @return string Form content
	 */
	private function formContent()
	{
		foreach($this->formData as $this->groupName => $this->groupData)
		{
			// Setup the vars for this group
			$this->groupPlaceHolder = isset($this->groupData['placeholder']) ? ' placeholder="' .$this->groupData['placeholder'] .'"' : '';
			$this->groupPlainID = isset($this->groupData['id']) ? $this->groupData['id'] : $this->groupName;
			$this->groupID = isset($this->groupData['id']) ? ' id="' .$this->groupData['id'] .'"' : 'id="' .$this->groupName .'"';
			$this->groupRows = isset($this->groupData['rows']) ? $this->groupData['rows'] : 3;
			$this->groupSelected = isset($this->groupData['selected']) ? $this->groupData['selected'] : null;
			$this->groupValidation = isset($this->groupData['validation']) ? $this->groupData['validation'] : '';
			$this->groupDisabled = isset($this->groupData['disabled']) ? ' disabled' : '';
			$this->groupHelp = isset($this->groupData['help']) ? '<span class="help-block">' .$this->groupData['help'] .'</span>' : '';
			if(isset($this->groupData['rules'])) $this->createRules($this->groupData['rules']);

			// Fill the value if we have one
			$this->groupValue = isset($this->groupData['value']) ? $this->groupData['value'] : '';

			// Depending on the type we need to add classes
			if($this->groupData['type'] == 'button')
				$this->groupClasses = isset($this->groupData['classes']) ? ' class="' .$this->groupData['classes'] .'"' : ' class="btn btn-default"';
			elseif($this->groupData['type'] == 'submit')
				$this->groupClasses = isset($this->groupData['classes']) ? ' class="' .$this->groupData['classes'] .'"' : ' class="btn btn-primary"';
			elseif($this->groupData['type'] == 'radio' || $this->groupData['type'] == 'checkbox')
				$this->groupClasses = isset($this->groupData['classes']) ? ' class="' .$this->groupData['classes'] .'"' : '';
			else
				$this->groupClasses = isset($this->groupData['classes']) ? ' class="' .$this->groupData['classes'] .'"' : ' class="form-control"';

			// Validation
			$this->groupValidation = isset($this->groupData['validation']) ? $this->groupData['validation'] : '';
			if(is_array($this->groupValidation) && $this->groupValidation['message']!='')
				$this->groupHelp .= '<span class="help-block">' .$this->groupValidation['message'] .'</span>';

			// Create the group
			$this->createGroup();
		}

		// Return the content as a string
		return $this->formContent;
	}

	/**
	 * Create a group containing the label, input and/or whatever else is needed
	 */
	private function createGroup()
	{
		// Validation
		$validationClass = '';
		if(is_array($this->groupValidation) && $this->groupValidation['type']!='')
			$validationClass = ' has-' .$this->groupValidation['type'];

		$this->formContent .= '<div class="form-group ' .$validationClass .'">';

		if(isset($this->groupData['label']) && $this->groupData['label']!='')
			$this->createLabel();

		$this->createInput();

		$this->formContent .= '</div>';
	}

	/**
	 * Create an input (textarea included)
	 */
	private function createInput()
	{
		// Start this group
		$this->formContent .= '<div class="col-sm-10">';

		// If there is not type set we set one
		$this->groupData['type'] = isset($this->groupData['type']) ? $this->groupData['type'] : 'text';

		switch($this->groupData['type'])
		{
			case 'submit':
				$this->createSubmit();
				break;
			case 'select':
				$this->createSelect();
				break;
			case 'multiple':
				$this->groupMultiple = 'multiple';
				$this->createSelect();
				break;
			case 'radio':
				$this->createRadio();
				break;
			case 'checkbox':
				$this->createCheckbox();
				break;
			case 'textarea':
				$this->createTextArea();
				break;
			case 'button':
				$this->createButton();
				break;
			case 'file':
				$this->formEnctype = ' enctype="multipart/form-data"';
				$this->createFile();
				break;
			default:
				$this->createDefaultType();
				break;
		}

		// End this group
		$this->formContent .= '</div>';
	}

	/**
	 * Create the rules string from the array
	 * @param $rules
	 */
	private function createRules($rules)
	{
		foreach($rules as $rule => $value)
		{
			$this->groupRules .=' ' .$rule .'="' .$value .'"';
		}
	}

	/**
	 * Create a submit button
	 */
	private function createSubmit()
	{
		$this->formContent .= '<button type="submit"' .$this->groupClasses .$this->groupID .$this->groupDisabled .'>' .$this->groupValue .'</button>' .$this->groupHelp;
	}

	/**
	 * Create a file
	 */
	private function createFile()
	{
		$this->formContent .= '<input type="file"' .$this->groupClasses .$this->groupID .$this->groupDisabled .'>' .$this->groupHelp;
	}

	/**
	 * Create a button
	 */
	private function createButton()
	{
		$this->formContent .= '<button type="button" ' .$this->groupClasses .$this->groupID .$this->groupDisabled .'>' .$this->groupValue .'</button>' .$this->groupHelp;
	}

	/**
	 * Create checkbox
	 */
	private function createCheckbox()
	{
		// Get the selected value from the received data
		if(!empty($this->receivedData) && isset($this->receivedData[$this->groupName]))
			$this->groupSelected = $this->receivedData[$this->groupName];

		foreach($this->groupData['options'] as $value => $option)
		{
			// If we have a selected use it
			$selected = '';
			if(isset($this->groupSelected) && in_array($value, $this->groupSelected))
				$selected = ' checked';

			$this->formContent .= '
			<label class="checkbox-inline">
				<input type="checkbox" name="' .$this->groupName .'[]" value="' .$value .'" ' .$this->groupClasses .$selected .$this->groupDisabled .'>' .$option .'
			</label>
			';
		}
		$this->formContent .= $this->groupHelp;
	}

	/**
	 * Create radio buttons
	 */
	private function createRadio()
	{
		// Get the selected value from the received data
		if(!empty($this->receivedData) && isset($this->receivedData[$this->groupName]))
			$this->groupSelected = $this->receivedData[$this->groupName];

		foreach($this->groupData['options'] as $value => $option)
		{
			// If we have a selected use it
			$selected = '';
			if($this->groupSelected == $value)
				$selected = ' checked';

			// Create the radio btn
			$this->formContent .= '
			<div class="radio">
				<label>
					<input type="radio" name="' .$this->groupName .'" value="' .$value .'" ' .$this->groupClasses .$selected .$this->groupDisabled .'>
					' .$option .'
				</label>
			</div>';
		}
		$this->formContent .= '</select>' .$this->groupHelp;
	}

	/**
	 * Create Text area
	 */
	private function createTextArea()
	{
		if(!empty($this->receivedData) && isset($this->receivedData[$this->groupName]))
			$this->groupValue = $this->receivedData[$this->groupName];

		$this->formContent .= '<textarea name="' .$this->groupName .'"' .$this->groupID .$this->groupClasses .$this->groupPlaceHolder .' rows="' .$this->groupRows .'"' .$this->groupRules .$this->groupDisabled .'>' .$this->groupValue .'</textarea>' .$this->groupHelp;
	}

	/**
	 * Create a form select
	 */
	private function createSelect()
	{
		// If we have a multiple select use it
		$multiple  = '';
		if($this->groupMultiple)
			$multiple = ' multiple';

		if(!empty($this->receivedData) && isset($this->receivedData[$this->groupName]))
			$this->groupSelected = $this->receivedData[$this->groupName];

		$this->formContent .= '<select' .$multiple .'  name="' .$this->groupName .'"' .$this->groupID .$this->groupClasses .$this->groupDisabled .'>';
		foreach($this->groupData['options'] as $value => $option)
		{
			// If we have no value use the option
			if($value == '')
				$value = $option;

			// If we have a selected use it
			$selected = '';
			if($this->groupSelected == $value)
				$selected = ' selected';

			$this->formContent .= '<option value="' .$value .'"' .$selected .'>' .$option .'</option>';
		}
		$this->formContent .= '</select>' .$this->groupHelp;
	}

	/**
	 * Create a default type
	 */
	private function createDefaultType()
	{
		// If we have a value then use it
		if(!empty($this->receivedData) && isset($this->receivedData[$this->groupName]))
			$this->groupValue = $this->receivedData[$this->groupName];

		$this->formContent .= '<input id="' .$this->groupName .'" type="' .$this->groupData['type'] .'" name="' .$this->groupName .'" value="' .$this->groupValue .'" ' .$this->groupID .$this->groupClasses .$this->groupPlaceHolder .$this->groupDisabled .'>' .$this->groupHelp;
	}

	/**
	 * Create a label
	 */
	private function createLabel()
	{
		$this->formContent .= '<label for="' .$this->groupPlainID .'" class="col-sm-2 control-label">' .$this->groupData['label'] .'</label>';
	}

	/**
	 * Output the form
	 * @return string
	 */
	public function outputForm()
	{
		return $this->form;
	}

	/**
	 * If we need a string
	 * @return string
	 */
	public function __toString() {
		return $this->outputForm();
	}
}