<?php
/*
* Example searching rule regexp '([a-z])' => 'news/view' replace path name to change controller
*/
return Array (
	'todo\/([0-9]+)$' => 'todo/index/$1',
	'app$' => 'error/index',
	'app\/.*' => 'error/index',
);