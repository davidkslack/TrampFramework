<?php
/**
 * Class Api
 * Used for the API
 * The first parameter must be the token
 * @author: Dave Slack <me@davidslack.co.uk>
 */
namespace Controllers;

use Models\Messages;

class Api extends System\Controller
{
	function __construct()
	{
		parent::__construct();
	}

	/**
	 * Show the first 100 messages as json
	 * @param $params array
	 */
	public function messages($params)
	{
		$messages = new Messages();
		$messages = $messages->getAll('id,description');

		//$this->outputJson($messages); // Basic out put
		$this->outputSecureJson($params, $messages); // This will test for the token and IP first
	}
}