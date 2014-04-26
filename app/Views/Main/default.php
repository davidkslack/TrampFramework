<h1 class="page-header">Tramp Framework default page</h1>
<p>This is the default view. If you are seeing this you have not created a correct index view.</p>
<ul>To create a view:
	<li>create a folder in the views folder with the same name as the Controller</li>
	<li>Create a file called index.html.php in the new folder</li>
	<li>Add the script below to your Controller to overrider this page</li>
</ul>
<pre>
/**
 * The main function for the index
 **/
public function index()
{
	$this->data[\'title\'] = \'Default view\';
	$this->data[\'content\'] = \'Content here\';

	// Call the view with the the data to add in
	$this->show( \'index\', $this->data );
}
</pre>