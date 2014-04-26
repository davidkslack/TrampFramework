<?php
/**
 * Form Builder
 * Add in an array and build a form.
 * @author: Dave Slack <me@davidslack.co.uk>
 */
namespace Builders\Form;
class Form
{
	private $form ='';
	private $formData = array();
	private $formContent = '';
	private $groupPlaceHolder = '';
	private $groupPlainID = '';
	private $groupID = '';
	private $groupClasses = '';
	private $groupName = '';
	private $groupData = array();
	private $groupRows = 3;

	/**
	 * Create the form
	 * @param array $formData
	 */
	public function createForm( $formData = array() )
	{
		// Add the data to the object
		$this->formData = $formData['content'];

		// Create the vars to use in the view
		$id = (isset($formData['id'])) ? ' id="' .$formData['id'] .'"' : '';
		$classes = (isset($formData['classes'])) ? ' class="' .$formData['classes'] .'"' : ' class="form-horizontal"';
		$action  = (isset($formData['action'])) ? ' action="' .$formData['action'] .'"' : '';
		$method = (isset($formData['method'])) ? ' method="' .$formData['method'] .'"' : '';
		$formContent = $this->formContent();

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

			// Add the Bootstrap class if we don't add anything
			if(isset($this->groupData['classes']))
				$this->groupClasses = ' class="' .$this->groupData['classes'] .'"';
			elseif($this->groupData['type'] != 'radio')
				$this->groupClasses = ' class="form-control"';
			else
				$this->groupClasses = '';

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
		$this->formContent .= '<div class="form-group">';

		if($this->groupData['label']!='')
			$this->createLabel();

		$this->createInput();

		$this->formContent .= '</div>';
	}

	/**
	 * Create an input (textarea included)
	 */
	private function createInput()
	{
		$this->formContent .= '<div class="col-sm-10">';

		switch($this->groupData['type'])
		{
			case 'select':
				$this->createSelect();
				break;
			case 'radio':
				$this->createRadio();
				break;
			case 'textarea':
				$rows = 3;
				$this->createTextArea($rows);
				break;
			default:
				$this->createDefaultType();
				break;
		}

		$this->formContent .= '</div>';
	}

	private function createRadio()
	{
		foreach($this->groupData['options'] as $value => $option)
		{
			$this->formContent .= '
			<div class="radio">
				<label>
					<input type="radio" name="' .$this->groupName .'" value="' .$value .'" ' .$this->groupID .$this->groupClasses .' checked>
					' .$option .'
				</label>
			</div>';
		}


	}

	private function createTextArea($rows)
	{
		$this->formContent .= '<textarea name="' .$this->groupName .'"' .$this->groupID .$this->groupClasses .$this->groupPlaceHolder .' rows="' .$this->groupRows .'"></textarea>';
	}

	/**
	 * Create a form select
	 */
	private function createSelect()
	{
		$this->formContent .= '<select name="' .$this->groupName .'"' .$this->groupID .$this->groupClasses .$this->groupPlaceHolder .'>';
		foreach($this->groupData['options'] as $value => $option)
		{
			if($value == '')
				$value = $option;
			$this->formContent .= '<option value="' .$value .'">' .$option .'</option>';
		}
		$this->formContent .= '</select>';
	}

	/**
	 * Create a default type
	 */
	private function createDefaultType()
	{
		$this->formContent .= '<input id="' .$this->groupName .'" type="' .$this->groupData['type'] .'"' .$this->groupID .$this->groupClasses .$this->groupPlaceHolder .'>';
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