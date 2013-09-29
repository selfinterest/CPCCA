<?php
/**
 * Created by JetBrains PhpStorm.
 * User: terrence
 * Date: 9/29/13
 * Time: 1:47 PM
 * To change this template use File | Settings | File Templates.
 */

class AuthenticationMiddleware extends Slim\Middleware {
    //There are two kinds of URL that must be authenticated. Any url that includes "/admin" must be authenticated.


    public function call(){
        /** @var Slim\Slim $app */
        $app = $this->app;
        $req = $app->request();
        $res = $app->response();
        $path = $req->getPath();

        //Quick and dirty. If "path" includes "/admin", test for an authenticated user.
        if(strpos($path, "/api/admin") === false){
            $this->next->call();
        } else {
            $isAdmin = isset($_SESSION["isAdmin"]) ? $_SESSION["isAdmin"] : false;
            if($isAdmin){
                $this->next->call();
            } else {
                $res->status(403);
                echo "Forbidden";
                //$app->halt(403, "Forbidden");
            }
        }
    }
}