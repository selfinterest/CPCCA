<?php
error_reporting(-1);
ini_set('display_errors', 'On');
require_once("private/vendor/autoload.php");

/* Initialize ActiveRecord */
ActiveRecord\Config::initialize(function($cfg)
{
    $cfg->set_model_directory('private/models');
    $cfg->set_connections(array(
        'development' => 'sqlite://private/database.db'));

    $cfg->set_default_connection('development');
});

/* Initialize Slim */
//$haml = new \Slim\Extras\Views\Haml();
//$haml::$hamlDirectory = "private/vendor/mthaml/mthaml";
//$haml::$hamlTemplatesDirectory = "private/templates/";
//$haml::$hamlCacheDirectory = "private/cache";

$app = new \Slim\Slim(array(
    "debug" => true,
    'templates.path' => 'private/templates',
    //"view" => $haml
));

$app->get("/api/template/:template", function($template) use ($app){
    $app->render($template);
});

$app->get("/api/documents", function() use ($app){
    $documents = array();
    for($x = 0; $x < 10; $x++){
        $documents[] = array("title" => "Document ".$x, "description" => "This is the description for document ".$x);
    }

    echo json_encode($documents);
});

$app->get("(.*)", function() use ($app){
    //$app->render("/public/index.html");
    require("public/index.html");
});

$app->run();