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
	private $formID;
	private $formClasses;
	private $formActon;
	private $formMethod;
	private $formContent;

	public function createForm( $formData = array() )
	{
		// Add the data to the object
		$this->formData = $formData['content'];

		// Create the vars to use in the view
		$id = (isset($formData['id'])) ? ' id="' .$formData['id'] .'"' : '';
		$classes = (isset($formData['classes'])) ? ' class="' .$formData['classes'] .'"' : '';
		$action  = (isset($formData['action'])) ? ' action="' .$formData['action'] .'"' : '';
		$method = (isset($formData['method'])) ? ' method="' .$formData['method'] .'"' : '';
		$formContent = $this->formContent();

		// Create the form view
		ob_start();
		include 'Views/form.html.php';
		$this->form = ob_get_contents();
		ob_end_clean();
	}

	private function formContent()
	{
		$content = '';
		foreach($this->formData as $key => $data)
		{
			$placeholder = isset($data['placeholder']) ? 'placeholder="' .$data['placeholder'] .'"' : '';
			$id = isset($data['id']) ? 'id="' .$data['id'] .'"' : '';
			$classes = isset($data['classes']) ? 'class="' .$data['classes'] .'"' : '';

			$content .= '<div class="form-group">';

			if($data['label']!='')
				$content .= '<label for="inputEmail3" class="col-sm-2 control-label">' .$data['label'] .'</label>';

			$content .= '<div class="col-sm-10">';

			if($data['type']=='select')
			{
				$content .= '<select class="form-control">';

				foreach($data['options'] as $option)
				{
					$content .= '<option>' .$option .'</option>';
				}

				$content .= '</select>';
			}
			else
				$content .= '<input type="' .$data['type'] .'" class="form-control"' .$id .$classes .$placeholder .' >';

			$content .= '</div></div>';
		}

		return $content;
	}

	public function outputForm()
	{
		return $this->form;
	}

	public function __toString() {
		return $this->outputForm();
	}
}