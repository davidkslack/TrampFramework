<?php
/**
 * Class Messages
 * Builder for the messages
 * To use: new \Builders\Messages(array('success', 'It was a success!!'));
 * We can use 4 types of message: success, info, warning or error (all lower case)
 * @author: Dave Slack <me@davidslack.co.uk>
 */
namespace Builders;
class Messages
{
	private $message;
	private $model;

	/**
	 * Construct the obj
	 */
	public function __construct( $message = array() )
	{
		// Start the session if its not started
		if(session_id() == '')
			session_start();

		// If we have our message
		if(!empty($message))
			$this->addMessage($message);
	}

	public function addMessage($message)
	{
		// Add the message into the obj
		$this->message = $message;

		// Check if we have this message in the messages
		if(!$this->checkMessage())
		{
			// Add the new messages to the session
			$this->addMessagesToSession();
		}
	}

	/**
	 * Check if the message exists in the session before we add it (stop duplicates)
	 * @return bool 	return true if we already have this message
	 */
	private function checkMessage()
	{
		if (isset($_SESSION['messages']) && is_array($_SESSION['messages']) && in_array($this->message, $_SESSION['messages']))
			return true;

		return false;
	}

	/**
	 * Add the message to the session
	 */
	private function addMessagesToSession()
	{
		if( !isset($_SESSION['messages']) )
			$_SESSION['messages'] = array();

		array_push( $_SESSION['messages'], $this->message );
	}

	/**
	 * Show all the messages collected
	 * @return string 	All the messages in the correct divs
	 */
	public function showMessages()
	{
		$messageStr = '';

		if( isset( $_SESSION['messages'] ) && is_array( $_SESSION['messages'] ) )
		{
			foreach( $_SESSION['messages'] as $message )
			{
				// Get the type of message
				$type = $message[0];

				// Some of the types show the wrong classes, change them here
				if($type == 'error')
					$type = 'danger';

				// Build the message
				// TODO: Should be a view?
				$messageStr .= '
				<div class="alert alert-' .$type .' fade in">
					<button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
					' .$message[1] .'
				</div>';
			}

			// Add the message to the DB
			$this->addToDB();
		}

		// Now we have the messages stored and on screen we can destroy them
		$this->destroyMessageSession();

		return $messageStr;
	}

	/**
	 * Reset the message session var
	 */
	private function destroyMessageSession()
	{
		$_SESSION['messages'] = array();
		unset($_SESSION['messages']);
	}

	/**
	 * Add the messages to the DB
	 */
	public function addToDB()
	{
		// We should use the messages model to add the message to the DB
		$this->model = new \Models\Messages();

		// Get the user ID
		$userID = 0;
		if( isset( $_SESSION[ 'admin' ][ 'id' ] ) )
			$userID = $_SESSION[ 'admin' ][ 'id' ];

		// Get the user role
		$adminId = 999;
		if( isset( $_SESSION[ 'admin' ][ 'adminId' ] ) )
			$adminId = $_SESSION[ 'admin' ][ 'adminId' ];

		// Get the site ID
		$siteID = null;
		if( isset( $_SESSION[ 'admin' ][ 'site_id' ] ) )
			$siteID = $_SESSION[ 'admin' ][ 'site_id' ];

		if( is_array( $_SESSION['messages'] ) )
		foreach( $_SESSION['messages'] as $message )
		{
			// Put the data together
			$data = array(
				'type' 			=> $message[0],
				'description' 	=> $message[1],
				'user_id' 		=> $userID,
				'role_id' 		=> $adminId,
				'site' 			=> $siteID
			);

			// Add the new message
			$this->model->add($data);
		}

	}
}