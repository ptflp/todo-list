<?php
$options =  array('extension' => '.html');
$TodoApp->mustache = new Mustache_Engine(array(
						'loader' => new Mustache_Loader_FilesystemLoader(dirname(__FILE__) . '/../view',$options),
					));