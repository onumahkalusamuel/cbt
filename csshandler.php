<?php

if(isset($_GET['files']))
{
	$styles = explode(",", $_GET['files']);
	header("Content-Type: text/css");
	foreach ($styles as $style) {
		include('css/'.$style);
	}
}