<?php 
// basic namespace
use de\andidittrich\lightup\LightUp;

// you should use your prefered autoloading method
require('Source/LightUp.php');
require('Source/LineTokenizer.php');
require('Source/Renderer.php');

// get the demo content
$demo1Raw = file_get_contents('Resources/Quickstart.md');

// render markdown as html
$pageContent = LightUp::render($demo1Raw);

// and display it within the template-file
include('Resources/BootstrapTemplate.phtml');


?>