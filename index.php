<?php
error_reporting(-1);
ini_set('display_errors', 'On');
require_once("private/vendor/autoload.php");
require("private/vendor/PasswordHash.php");

use RedBean_Facade as R;

$development = array("connection" => 'mysql:host=localhost;dbname=antisemitism',
        "username" => 'antis3mite', "password" => 'antis3mite');
$which = $development;      //change on deployment

R::setup($which["connection"], $which["username"], $which["password"]);

session_start();

$headPath = __DIR__ . "/private/templates/head.php";

$filePath = __DIR__ . "/private/files";
/*
$user = R::dispense("user");
$user->email = "parliamentaryterrence@gmail.com";
$user->password = "stup3dxy";

$hasher = new PasswordHash(8, false);
$user->password = $hasher->HashPassword($user->password);
R::store($user);*/
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
$app->post("/api/admin/upload", function() use ($app, $filePath){
    $file = $_FILES["file"];
    try {
        $file["name"] = preg_replace('/[\. ](?=.*\.)/', '', $file["name"]);
        move_uploaded_file($file["tmp_name"], $filePath . "/" . $file["name"]);
        echo json_encode($file);
    } catch (Exception $e){
        echo json_encode($e);
    }
});

//Get the list of files
function pluck($key, $data) {
    return array_reduce($data, function($result, $array) use($key) {
        isset($array[$key]) && $result[] = $array[$key];
        return $result;
    }, array());
}
$app->get("/api/admin/files", function() use ($app, $filePath){

    $handle = opendir($filePath);
    $files = array();
    while (false !== ($entry = readdir($handle))) {
        if($entry != ".." && $entry != ".") $files[] = $entry;
    }
    closedir($handle);
    //Now cross reference
    $ar = array_fill(0, count($files), "filename = ?");
    $str = join(" OR ", $ar);
    $documents = R::getAll("SELECT filename FROM document WHERE ".$str, $files );
    $documents = pluck("filename", $documents);
    $result = array();

    $mapper = function($value) use ($documents){
        if(in_array($value, $documents)){
            $db = true;
        } else {
            $db = false;
        }
        return array("name" => $value, "db" => $db);
    };

    $files = array_map($mapper, $files);

    //echo json_encode($documents);
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
    $documents = R::findAll("document", " ORDER BY `order` ASC");
    echo json_encode(R::exportAll($documents));
});

$app->get("/api/files/:name", function($name) use ($app, $filePath){
    $fullPath = $filePath . "/" . $name;
    if(file_exists($fullPath)){
        $res = $app->response();
        $res['Content-Description'] = 'File Transfer';
        $res['Content-Type'] = 'application/octet-stream';
        $res['Content-Disposition'] ='attachment; filename=' . basename($fullPath);
        $res['Content-Transfer-Encoding'] = 'binary';
        $res['Expires'] = '0';
        $res['Cache-Control'] = 'must-revalidate';
        $res['Pragma'] = 'public';
        $res['Content-Length'] = filesize($fullPath);
        readfile($fullPath);
    } else {
        $res = $app->response();
        $res->status(404);
        echo "File not found.";
    }
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
    } else if (strlen($password) > 72) {
        $app->flash("message", "Invalid login");
        $app->redirect("/login");
    } else {
        $hasher = new PasswordHash(8, false);
        $stored_hash = "*";
        $user = R::findOne("user", " email = ?", array($email));
        if(!$user){
            $app->flash("message", "Invalid login");
            $app->redirect("/login");
        } else {
            $stored_hash = $user->password;
            $check = $hasher->CheckPassword($password, $stored_hash);
            if($check){
                $_SESSION["isAdmin"] = true;
                $app->redirect("/admin");
            } else {
                $app->flash("message", "Invalid login");
                $app->redirect("/login");
            }
        }

    }
});


$app->get("/login", function() use ($app, $headPath){
    $app->render("login.php", array("headPath" => $headPath));
});

$app->post("/api/logout", function() use($app){
    unset($_SESSION["isAdmin"]);
    echo "Logged out";
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