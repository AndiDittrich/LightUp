<?php 
// basic namespace
use de\andidittrich\lightup\LightUp;

// you should use your prefered autoloading method
require('Source/LightUp.php');
require('Source/LineTokenizer.php');
require('Source/Renderer.php');

// default md document to parse
$documentName = 'README.md';

// try to get markdown source document
if (isset($_GET['doc'])){
	$filename = 'Resources/'.preg_replace('/[^A-Za-z]/', '', $_GET['doc']).'.md';
	if (file_exists($filename)){
		$documentName = $filename;
	}
}

// some LightUp options
$options = array(
	'highlightingMode' => 'enlighterjs'	
);

// get the demo content
$demo1Raw = file_get_contents($documentName);

// render markdown as html
$pageContent = LightUp::render($demo1Raw, $options);

// and display it within the template-file
include('Resources/BootstrapTemplate.phtml');


?>