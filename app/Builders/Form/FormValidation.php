<?php
/**
 * Validate a form rule
 * Will take in a rule with the value of the rule eg min=>3 , validate it against a passed in value to test and return
 * true or false depending on if the rule passes
 */
namespace Builders\Form;
class FormValidation
{
	private $rule;
	private $ruleValue;
	private $testValue;
	private $returnData = array();

	/**
	 * @param $rule string		Rule to test against
	 * @param $ruleValue  		Value of the rule to test against
	 * @param $testValue 		What to test
	 * @return array 			Empty array if passed, type, message and valid=false if failed
	 */
	public function rules($rule, $ruleValue, $testValue)
	{
		// Add the vars
		$this->rule = $rule;
		$this->ruleValue = $ruleValue;
		$this->testValue = $testValue;
		$this->returnData = array();

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
			case 'minlength':
				$this->validateMinChar($testValue, $ruleValue);
				break;
			case 'maxlength':
				$this->validateMaxChar($testValue, $ruleValue);
				break;
			case 'alphanumeric':
				$this->validateAlphanumeric($testValue);
				break;
			case 'alphanumericExtras':
				$this->validateAlphanumericExtras($testValue);
				break;
			case 'number':
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

		// Return the error data or if a pass return true
		return $this->returnData;
	}

	/**
	 * Error if the required rule is added and there is no value
	 */
	private function validateRequired()
	{
		if($this->ruleValue == '')
		{
			$this->returnData['type'] ='error';
			$this->returnData['message'] = 'This field is required';
			$this->returnData['valid'] = false;
		}
	}

	/**
	 * Error if the value is less than the min rule
	 */
	private function validateMin()
	{
		// We have a number
		if(is_numeric($this->testValue))
		{
			if($this->testValue < $this->ruleValue)
			{
				$this->returnData['type'] ='error';
				$this->returnData['message'] = 'This value is too low';
				$this->returnData['valid'] = false;
			}
		}
		else
		{
			$this->returnData['type']='error';
			$this->returnData['message'] = 'This value must be a number';
			$this->returnData['valid'] = false;
		}
	}

	/**
	 * Error if the value is more than the max rule
	 */
	private function validateMax()
	{
		// We have a number
		if(is_numeric($this->testValue))
		{
			if($this->testValue > $this->ruleValue)
			{
				$this->returnData['type']='error';
				$this->returnData['message'] = 'This value is too high';
				$this->returnData['valid'] = false;
			}
		}
		else
		{
			$this->returnData['type']='error';
			$this->returnData['message'] = 'This value must be a number';
			$this->returnData['valid'] = false;
		}
	}

	/**
	 * Error if the value is less than the min rule
	 */
	private function validateMinChar()
	{
		if(strlen($this->testValue) < $this->ruleValue)
		{
			$this->returnData['type']='error';
			$this->returnData['message'] = 'This value needs more characters';
			$this->returnData['valid'] = false;
		}
	}

	/**
	 * Error if the value is more than the max rule
	 */
	private function validateMaxChar()
	{
		if(strlen($this->testValue) > $this->ruleValue)
		{
			$this->returnData['type']='error';
			$this->returnData['message'] = 'This value needs less characters';
			$this->returnData['valid'] = false;
		}
	}

	/**
	 * Validate the value is alphanumeric
	 */
	private function validateAlphanumeric()
	{
		if (!ctype_alnum($this->testValue))
		{
			$this->returnData['type']='error';
			$this->returnData['message'] = 'The field does not consist of all letters or digits.';
			$this->returnData['valid'] = false;
		}
	}

	/**
	 * Validate the value is a Alphanumeric, underscores or dashes
	 */
	private function validateAlphanumericExtras()
	{
		if (preg_match('/^[\w#-]+$/', $this->testValue) != 1)
		{
			$this->returnData['type']='error';
			$this->returnData['message'] = 'The field does not consist of all letters or digits.';
			$this->returnData['valid'] = false;
		}
	}

	/**
	 * Validate the value is a number
	 */
	private function validateNumber()
	{
		if(!is_numeric($this->testValue))
		{
			$this->returnData['type']='error';
			$this->returnData['message'] = 'The field does not consist of all numbers.';
			$this->returnData['valid'] = false;
		}
	}

	/**
	 * Validate the value is letters only
	 */
	private function validateLetters()
	{
		if(!ctype_alpha($this->testValue))
		{
			$this->returnData['type']='error';
			$this->returnData['message'] = 'The field does not consist of all letters.';
			$this->returnData['valid'] = false;
		}
	}

	/**
	 * Validate the value falls before the rule date to pass
	 * NB. If UK date change the / to - eg 26/02/2014 changed to 26-02-2014
	 */
	private function validateBeforeDate()
	{
		// Change to timestamp using the default format
		$testDate = new \DateTime($this->testValue);
		$ruleDate = new \DateTime($this->ruleValue);

		// Compare the dates
		if($testDate > $ruleDate)
		{
			$this->returnData['type']='error';
			$this->returnData['message'] = 'This date is to far into the future, it should be before ' .$this->ruleValue;
			$this->returnData['valid'] = false;
		}
	}

	/**
	 * Validate the value falls after the rule date to pass
	 */
	private function validateAfterDate()
	{
		// Change to timestamp using the default format
		$testDate = new \DateTime($this->testValue);
		$ruleDate = new \DateTime($this->ruleValue);

		// Compare the dates
		if($testDate < $ruleDate)
		{
			$this->returnData['type']='error';
			$this->returnData['message'] = 'This date is to far into the past, it should be after ' .$this->ruleValue;
			$this->returnData['valid'] = false;
		}
	}
}