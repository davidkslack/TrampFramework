<?php
/**
 * Configuration
 * All config options and settings are held here. Each dev should have a seperate copy of this file and only a 'template'
 * version should be held in the repo
 * @author: Dave Slack <me@davidslack.co.uk>
 */

/**
 * Configuration for: Error reporting
 * Useful to show every little problem during development, but only show hard errors in production
 * NB: Comment out for production
 */
error_reporting(E_ALL);
ini_set("display_errors", 1);

/**
 * Configuration for: Base URL
 * This is the base url of our app. if you go live with your app, put your full domain name here.
 * if you are using a (different) port, then put this in here, like http://mydomain:8888/subfolder/
 * NB: The trailing slash is important!
 * NB: Use a / to make this work on any page
 */
define('URL', '/');

/**
 * Define the directory separator depending on the OS
 */
define('DS', DIRECTORY_SEPARATOR);

/**
 * Define the Template
 */
define( 'TEMPLATE_NAME', 'Default' );
define( 'TEMPLATE_PAGE', 'page.html.php' );

/**
 * Define the paths
 */
// App home
define( 'APP_PATH', HOME .DS .'app' .DS );
define( 'CONTROLLER_PATH', APP_PATH .'Controllers' .DS );

/**
 * Date format
 */
define('DATEFORMAT','d/m/Y H:i');


/**
 * Configuration for: Database
 */
// Local
/*define ('DB_HOST',  '127.0.0.1:3306');
define ('DB_NAME',  'tramp-website');
define ('DB_USER',  'root');
define ('DB_PASS',  'qwerty123');*/

/*define ('DB_HOST',  '127.2.199.130:3306');
define ('DB_NAME',  'tramp-website');
define ('DB_USER',  'adminfUXasvR');
define ('DB_PASS',  'MGv7smH19vQz');*/

define ('DB_HOST',  '127.0.0.1:3306');
define ('DB_NAME',  'airconnect');
define ('DB_USER',  'root');
define ('DB_PASS',  'qwerty123');