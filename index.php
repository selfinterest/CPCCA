<?php
error_reporting(-1);
ini_set('display_errors', 'On');
require_once("private/vendor/autoload.php");
session_start();

$headPath = __DIR__ . "/private/templates/head.php";

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

$app->post("/api/admin/upload", function() use ($app){
    $file = $_FILES["file"];
    try {
        $file["name"] = preg_replace('/[\. ](?=.*\.)/', '', $file["name"]);
        move_uploaded_file($file["tmp_name"], __DIR__ . "/public/files/".$file["name"]);
        echo json_encode($file);
    } catch (Exception $e){
        echo json_encode($e);
    }
});

$app->get("/api/admin/files", function() use ($app){
    $handle = opendir(__DIR__ . "/public/files");
    $files = array();
    while (false !== ($entry = readdir($handle))) {
        if($entry != ".." && $entry != ".") $files[] = $entry;
    }
    closedir($handle);
    echo json_encode($files);
});

$app->get("/api/admin/file/:name", function() use ($app){
    $file = array("title" => "Some title");
    echo json_encode($file);
});

$app->get("/api/admin/template/:template", function($template) use($app){
    $app->render("/admin/".$template);
});

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

$app->get("/login", function() use ($app, $headPath){
    $app->render("login.php", array("headPath" => $headPath));
});

$app->get("/admin(\/?)(.*)", function() use ($app, $headPath){
    $isAdmin = isset($_SESSION["isAdmin"]) ? $_SESSION["isAdmin"] : false;
    if($isAdmin){
        $app->render("admin/index.php", array("headPath" => $headPath));
    } else {
        $app->redirect("/login");
    }
});

$app->get("(.*)", function() use ($app, $headPath){
    //$app->render("/public/index.html");
    $app->render("main.php", array("headPath" => $headPath));
});

$app->run();