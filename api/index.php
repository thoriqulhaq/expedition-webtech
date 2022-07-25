<?php

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;

require 'vendor/autoload.php';
require 'config/db.php';

$app = new \Slim\App;

require 'routes/user.php';
require 'routes/item.php';
require 'routes/expedition_status.php';
require 'routes/courier.php';

$app->run();