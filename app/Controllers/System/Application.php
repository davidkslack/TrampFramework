<?php
/**
 * Class Application
 * The heart of the App
 * Class will route the App to the controller, will use the method of the second parameter of the URL and add other
 * parameters of the URL to vars
 * @author: Dave Slack <me@davidslack.co.uk>
 */
namespace Controllers\System;

class Application
{
	private $route_controller;
	private $route_action;
	private $route_parameters = array();
	private $route = array();

	public function __construct()
	{
		// Get the route
		$this->routeUrl();

		// Test the user should see this page

		// If there is a controller then we should use it, else use main
		if (!$this->route_controller)
			$this->route_controller = 'Main';

		// Create a new object from the route controller
		$controller = 'Controllers\\' .$this->route_controller;
		$this->route_controller = new $controller();

		// If we have no action, use the index action
		if( !$this->route_action )
			$this->route_action = 'index';
		// If we have an action, but no method to go with it
		elseif( !method_exists( $this->route_controller, $this->route_action ) )
		{
			// Our action must be a parameter, so we add it to out parameters array
			array_unshift($this->route_parameters, $this->route_action );

			// Use index as our action
			$this->route_action = 'index';
		}

		// Do the route
		$this->route_controller->{$this->route_action}( $this->route_parameters );
	}

	/**
	 * Gets and splits the URL
	 */
	private function routeUrl()
	{
		// If we have a route then use it
		if( isset( $_GET['route'] ) )
		{
			// Split sanitizes and trimmed URL
			$route = rtrim($_GET['route'], '/');
			$route = filter_var($route, FILTER_SANITIZE_URL);
			$route = html_entity_decode($route);
			$route = explode('/', $route);
			$this->route = $route;

			// If the first part of the rout is 'admin' we are in the admin system
			if(isset($this->route[0]) && $this->route[0] == 'admin')
			{
				// Strip the first part of the route as it is admin
				array_shift($this->route);

				// Route to the correct constructor
				$this->route();

			}
			if(isset($this->route[0]) && $this->route[0] == 'api')
			{
				// Route to the correct constructor
				$this->route();
			}
			else // We are not in the admin or api and must deal with the frontend
			{
				// We are on the frontend (not admin) and can deal with this
			}
		}
		else
		{
			// We are on the home page and can deal with this. Default is admin home
		}
	}

	private function route()
	{
		// Put route parts into correct vars
		$this->route_controller = (isset($this->route[0]) ? ucfirst( $this->route[0] ) : null);
		$this->route_action = (isset($this->route[1]) ? $this->route[1] : null);

		// Get the rest of the parameters from the route
		if( isset( $this->route[2] ) )
		{
			// Add all the parameters to the array
			$this->route_parameters = $this->route;

			// Unset the first 2 parameters as they are used for the controller and action
			array_shift($this->route_parameters);
			array_shift($this->route_parameters);
		}
	}
}