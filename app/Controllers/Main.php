<?php
/**
 * Class Main
 * Class used when we go to the home page
 * @author: Dave Slack <me@davidslack.co.uk>
 */
namespace Controllers;

class Main extends System\Controller
{
	/**
	 * The main function for the index
	 **/
	public function index()
	{
		// Call the view with the the data to add in
		$this->show( 'index', $this->data );
	}
}