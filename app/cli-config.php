<?php
use Doctrine\ORM\Tools\Console\ConsoleRunner;
require_once 'resource/bootstrap.php';

return ConsoleRunner::createHelperSet($TodoApp->db);