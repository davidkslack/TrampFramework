<?php
/**
 *
 */
namespace Builders\Form;
class FormValidation
{
	function __construct($rules, $testValue)
	{
		// Go through the rules
		foreach($rules as $rule => $ruleValue)
		{
			// Make sure we tet the correct rules
			switch($rule)
			{
				case 'required':
					$this->validateRequired();
					break;
				case 'min':
					$this->validateMin($testValue, $ruleValue);
					break;
				case 'max':
					$this->validateMax($testValue, $ruleValue);
					break;
				case 'alphanumeric':
					$this->validateAlphanumeric($testValue);
					break;
				case 'numbers':
					$this->validateNumber($testValue);
					break;
				case 'letters':
					$this->validateLetters($testValue);
					break;
				case 'before-date':
					$this->validateBeforeDate($testValue, $ruleValue);
					break;
				case 'after-date':
					$this->validateAfterDate($testValue, $ruleValue);
					break;
			}
		}
	}

	/**
	 * Error if the required rule is added and there is no value
	 */
	private function validateRequired()
	{
		if($this->receivedData[$this->groupName] == '')
		{
			$this->groupValidation['type']='error';
			$this->groupValidation['message'] = 'This field is required';
			$this->valid = false;
		}
	}

	/**
	 * Error if the value is less than the min rule
	 */
	private function validateMin($testValue, $ruleValue)
	{
		// We have a number
		if(is_numeric($testValue))
		{
			if($this->receivedData[$this->groupName] < $ruleValue)
			{
				$this->groupValidation['type']='error';
				$this->groupValidation['message'] = 'This value is too low';
				$this->valid = false;
			}
		}
		// No number
		elseif(strlen($this->receivedData[$this->groupName]) < $ruleValue)
		{
			$this->groupValidation['type']='error';
			$this->groupValidation['message'] = 'This value needs more characters';
			$this->valid = false;
		}
	}

	/**
	 * Error if the value is more than the max rule
	 */
	private function validateMax($testValue, $ruleValue)
	{
		// We have a number
		if(is_numeric($testValue))
		{
			if($this->receivedData[$this->groupName] > $ruleValue)
			{
				$this->groupValidation['type']='error';
				$this->groupValidation['message'] = 'This value is too high';
				$this->valid = false;
			}
		}
		elseif(strlen($testValue) > $ruleValue)
		{
			$this->groupValidation['type']='error';
			$this->groupValidation['message'] = 'This value needs less characters';
			$this->valid = false;
		}
	}

	/**
	 * Validate the value is alphanumeric
	 */
	private function validateAlphanumeric($testValue)
	{
		if (!ctype_alnum($testValue))
		{
			$this->groupValidation['type']='error';
			$this->groupValidation['message'] = 'The field does not consist of all letters or digits.';
			$this->valid = false;
		}
	}

	/**
	 * Validate the value is a number
	 */
	private function validateNumber($ruleValue)
	{

	}

	/**
	 * Validate the value is letters only
	 */
	private function validateLetters($ruleValue)
	{

	}

	/**
	 * Validate the value falls before the rule date
	 */
	private function validateBeforeDate($testValue, $ruleValue)
	{

	}

	/**
	 * Validate the value falls after the rule date
	 */
	private function validateAfterDate($testValue, $ruleValue)
	{

	}
}