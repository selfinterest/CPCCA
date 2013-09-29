<?php
error_reporting(-1);
ini_set('display_errors', 'On');
require_once("private/vendor/autoload.php");

use RedBean_Facade as R;

$development = array("connection" => 'mysql:host=localhost;dbname=antisemitism',
        "username" => 'antis3mite', "password" => 'antis3mite');
$which = $development;      //change on deployment

R::setup($which["connection"], $which["username"], $which["password"]);

session_start();

$headPath = __DIR__ . "/private/templates/head.php";

/* Initialize ActiveRecord */
/*ActiveRecord\Config::initialize(function($cfg)
{
    $cfg->set_model_directory('private/models');
    $cfg->set_connections(array(
        'development' => 'sqlite://private/db/database.db'));

    $cfg->set_default_connection('development');
});*/

/* Initialize Slim */
//$haml = new \Slim\Extras\Views\Haml();
//$haml::$hamlDirectory = "private/vendor/mthaml/mthaml";
//$haml::$hamlTemplatesDirectory = "private/templates/";
//$haml::$hamlCacheDirectory = "private/cache";

require("private/authentication.php");

$app = new \Slim\Slim(array(
    "debug" => true,
    'templates.path' => 'private/templates',
    //"view" => $haml
));


$app->add(new AuthenticationMiddleware());

//Uploads a file
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

//Get the list of files
$app->get("/api/admin/files", function() use ($app){
    $handle = opendir(__DIR__ . "/public/files");
    $files = array();
    while (false !== ($entry = readdir($handle))) {
        if($entry != ".." && $entry != ".") $files[] = $entry;
    }
    closedir($handle);
    echo json_encode($files);
});

//Gets information for a single document
$app->get("/api/admin/file/:name", function($name) use ($app){
   $document = R::findOne("document", " filename = ?", array($name));
   if($document) {
       $document = $document->export();
       $document["order"] = (int) $document["order"];
       echo json_encode($document);
   } else {
       echo null;
   }

});

//Posts information for a file
$app->post("/api/admin/file/:name", function($name) use ($app){
    $req = $app->request();
    $body = json_decode($req->getBody());
    if(!isset($body->id)){                //new document
        $document = R::dispense('document');
    } else {                                //old document
        $document = R::load('document', $body->id);
    }

    $document->import($body);
    $id = R::store($document);
    echo json_encode($document->export());

});

//Gets an admin template
$app->get("/api/admin/template/:template", function($template) use($app){
    $app->render("/admin/".$template);
});

//Gets a normal template
$app->get("/api/template/:template", function($template) use ($app){
    $app->render($template);
});

//Gets all the documents
$app->get("/api/documents", function() use ($app){
    /*$documents = array();
    for($x = 0; $x < 25; $x++){
        $documents[] = array("title" => "Document ".$x, "description" => "This is the description for document ".$x, "filename" => "document".$x.".pdf");
    }*/
    $documents = R::getAll("select * from document");
    echo json_encode($documents);
});

/*$app->get("/api/document/filename/:name", function($name) use ($app){
    $document = R::findOne("Document", " filename = ?", array($name));
    if(!$document) $document = null;
    json_encode($document);
});*/

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