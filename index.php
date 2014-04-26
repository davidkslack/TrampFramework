<?php
/**
 * Tramp Framework
 * Basic MVC to start an app project
 * Index page will start the Bootstrap and start the app only
 * This is the only entry point into the app
 * @author: Dave Slack <me@davidslack.co.uk>
 */
// Define the HOME dir
define ('HOME', dirname(__FILE__));

// Bootstrap
require_once (HOME .'/app/Config/bootstrap.php');