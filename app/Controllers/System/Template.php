<?php
/**
 * Class Template
 * Basic templating for the system and heart of the Frontend
 * @author: Dave Slack <me@davidslack.co.uk>
 */
namespace Controllers\System;

class Template
{
	private $templateFile;
	private $templateDir;
	private $view;
	private $content;

	// Set the defaults (over ridden by language later)
	private $defaultTemplateHeaderTitle = 'Template header title';
	private $defaultTemplateDescription = 'Template - default description';
	private $defaultTemplateKeywords = 'template keywords';
	private $defaultTemplateBodyClass = 'template';

	function __construct($view, $content)
	{
		// Create the content array to use in the obj
		$this->content = $content;

		// Create the default vars
		$headerTitle = $this->createHeaderTitle();
		$description = $this->createDescription();
		$keywords = $this->createKeywords();
		$bodyClass = $this->createBodyClass();

		// Get the correct view to use
		$this->view = $view;

		// Put the template together
		$this->templateDir = HOME .DS .'app' .DS .'Templates' .DS .TEMPLATE_NAME .DS;
		$this->templateFile = $this->templateDir .TEMPLATE_PAGE;

		// Put the content array into the view
		ob_start();
		include $this->view;
		$content = ob_get_contents();
		ob_end_clean();

		// Output the Template
		include( $this->templateFile );
	}

	/**
	 * Check if we have a header title and return it if we do.
	 * Next check for a title and return that if we have it,
	 * else return a default
	 * @return string
	 */
	private function createHeaderTitle()
	{
		if(isset($this->content['headerTitle']))
			return $this->content['headerTitle'];
		elseif(isset($this->content['title']))
			return $this->content['title'];

		return $this->defaultTemplateHeaderTitle;
	}

	/**
	 * Check if we have a description and return it if we do.
	 * else return a default
	 * @return string
	 */
	private function createDescription()
	{
		if(isset($this->content['description']))
			return $this->content['description'];

		return $this->defaultTemplateDescription;
	}

	/**
	 * Check if we have keywords and return them if we do.
	 * else return a default
	 * @return string
	 */
	private function createKeywords()
	{
		if(isset($this->content['keywords']))
			return $this->content['keywords'];

		return $this->defaultTemplateKeywords;
	}

	/**
	 * Check if we have keywords and return them if we do.
	 * else return a default
	 * @return string
	 */
	private function createBodyClass()
	{
		if(isset($this->content['bodyClass']))
			return $this->content['bodyClass'];

		return $this->defaultTemplateBodyClass;
	}
}