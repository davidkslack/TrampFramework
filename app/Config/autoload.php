<?php
/**
 * Autoload
 * The auto-loading function, which will be called every time a file "is missing"
 * @author: Dave Slack <me@davidslack.co.uk>
 */
function autoload( $classToFind )
{
	$rootPath = HOME .DS;

	$classToFind = str_replace('/', DS, $classToFind);
	$classToFind = str_replace('\\', DS, $classToFind);

	require_once( $rootPath .'app' .DS .$classToFind .'.php' );
}

// If a class cannot be found we can autoload it
spl_autoload_register("autoload");