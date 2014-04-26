<?php
/**
 * Bootstrap
 * This is the file that loads everything up and should be the first (and only) file called from index
 * @author: Dave Slack <me@davidslack.co.uk>
 */
// Ensure we have session
if(session_id() === ""){session_start();}

// Include the config settings
require_once ( HOME .'/app/Config/config.php' );

// The auto-loader to load classes automatically
require_once( HOME .'/app/Config/autoload.php');

// Start our application
$app = new Controllers\System\Application();

// Close session to speed up the concurrent connections
session_write_close();