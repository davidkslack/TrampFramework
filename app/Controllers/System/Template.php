<?php
/**
 * Class Template
 * Basic templating for the system and heart of the Frontend
 * @author: Dave Slack <me@davidslack.co.uk>
 */
namespace Controllers\System;

class Template
{
	protected $headerTitle;
	protected $headerDescription;
	protected $headerKeywords;
	protected $bodyClass;
	protected $templateFile;
	protected $templateDir;

	function __construct()
	{
		// Get just the class name, not the full namespace
		$className = substr(strrchr(get_class( $this ), "\\"), 1);

		// Create the default template vars
		$this->headerTitle = $className;
		$this->description = "This is the " .$className ." description";
		$this->keywords = $className;
		$this->bodyClass = strtolower( $className );

		// Put the template together
		$this->templateDir = HOME .DS .'app' .DS .'Templates' .DS .TEMPLATE_NAME .DS;
		$this->templateFile = $this->templateDir .TEMPLATE_PAGE;
	}
}