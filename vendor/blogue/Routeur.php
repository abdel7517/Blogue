<?php

namespace Blogue;

use Blogue\Request;
use Blogue\Controller;
use App\FrontController;
use Exception;
use Symfony\Component\Yaml\Yaml;

class Routeur
{

    private $request, $pathTemplate;
    private $routes = array('home');

    public function __construct()
    {
        $this->request = new Request;
        $this->pathTemplate = '../src/templates/';
        $this->getController();
    }


    public function getController()
    {

        $path = $this->request->getPath();
        $routesFile = $path . '/projet_blogue/config/routes.yaml';
        $controllerPath = $path . '/projet_blogue/src/Controller/';
       // check if file exist 
       if(file_exists($routesFile))
       {
        $routes = Yaml::parseFile($path . '/projet_blogue/config/routes.yaml');
        if (!empty($routes)) {
            foreach ($routes as $route) {
                // if (preg_match('/\b' . $route['path'] . '\b/', $this->path)) {
                //call to controller 
                    if(file_exists($controllerPath . $route['controller'] . '.php'))
                    {
                        require_once('../src/controller/'. $route['controller'] . '.php');
                        if(class_exists('App'. DIRECTORY_SEPARATOR .$route['controller'])){
                            $className = 'App'. DIRECTORY_SEPARATOR .$route['controller'];
                            $controller = new $className;
                            if( $controller instanceof Controller  ) 
                            {
                                // call to methode associate to the route 
                                $methode = $route['methode'];
                                $controller->$methode();
                            }else{
                                throw new Exception('L\'objet $controller  n\'est pas une instance de la class Controller. ligne '. __LINE__);
                            }
                        }
                        else{
                            throw new Exception('la classe '.$route["controller"].' n\'existe pas. Ligne '. __LINE__);
                        }
                    
                    }
                    else
                    {
                        throw new Exception('le fichier  '. $route['controller'] . '.php n\'existe pas. Ligne '. __LINE__);
                    }
                // }
             
            }
        }
        return;
       }
       else{

            throw new Exception( getcwd().'Le fichier routes.yaml dans le dossier config n\'a pas etait trouver ');
            // basename($_SERVER['REQUEST_URI'])
       }




       
        // no route found 
        $this->sendResponse('404.html');
    }

    private function autoload(string $class){
    }


    private function sendResponse(string $filename)
    {
        header('Location:' .$this->pathTemplate. $filename);
        return;
    }
}
