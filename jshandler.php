<?php

if(isset($_GET['files']))
{
	$scripts = explode(",", $_GET['files']);
	header("Content-Type: text/javascript");
	foreach ($scripts as $script) {
		include('js/'.$script);
	}
}