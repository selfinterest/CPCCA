<?php
error_reporting(-1);
ini_set('display_errors', 'On');
require_once("private/vendor/autoload.php");
session_start();

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
    for($x = 0; $x < 25; $x++){
        $documents[] = array("title" => "Document ".$x, "description" => "This is the description for document ".$x, "filename" => "document".$x.".pdf");
    }

    echo json_encode($documents);
});

$app->post("/api/login", function() use ($app){
    $email = isset($_POST["email"]) ? $_POST["email"] : null;
    $password = isset($_POST["password"]) ? $_POST["password"] : null;

    if(!$email || !$password){
        $app->flash("message", "Invalid login");
        $app->redirect("/login");
    } else {    //TODO: need to actually check the username and password
        $_SESSION["isAdmin"] = true;
        $app->redirect("/admin");
    }
});

$app->get("/login", function() use ($app){
    $app->render("login.php", array("headPath" => "../head.php"));
});

$app->get("/admin", function() use ($app){
    $isAdmin = isset($_SESSION["isAdmin"]) ? $_SESSION["isAdmin"] : false;
    if($isAdmin){
        $app->render("admin/index.php", array("headPath" => __DIR__ . "/private/templates/head.php"));
    } else {
        $app->redirect("/login");
    }
});

$app->get("(.*)", function() use ($app){
    //$app->render("/public/index.html");
    $app->render("main.html", array("headPath" => "/private/head.php"));
});

$app->run();