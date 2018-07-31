<?php
/*
* Example searching rule regexp '([a-z])' => 'news/view' replace path name to change controller
*/
return Array (
	'todo\/([0-9]+)$' => 'todo/index/$1',
	'app$' => 'error/index',
	'app\/.*' => 'error/index',
	'news\/([a-z]+)\/([0-9]+)$' => 'news/view/$2/$1',
	'news\/([0-9]+)$' => 'news/view/$1',
	'news$' => 'news/index',
);