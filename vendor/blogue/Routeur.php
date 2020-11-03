<?php

namespace Blogue;

use Blogue\Request;
use Blogue\Controller;
use App\FrontController;
use App\HomeController;
use Exception;
use Symfony\Component\Yaml\Yaml;

class Routeur
{

    private $request, $pathTemplate;

    public function __construct()
    {
        $this->request = new Request;
        $this->pathTemplate = '../src/templates/';
        $this->getController();
    }


    public function getController()
    {

        $rootFolder = $this->request->getNameOfRootFolder();
        $path = $this->request->getPath();
        $routesFile = $path . '/' . $rootFolder . '/config/routes.yaml';

        $controllerPath = $path . '/' .  $rootFolder  . '/src/Controller/';

        // check if file exist 
        if (file_exists($routesFile)) {
            $routes = Yaml::parseFile($path . '/' . $rootFolder . '/config/routes.yaml');
            if (!empty($routes)) {
                foreach ($routes as $route) {
                    // if (preg_match('/\b' . $route['path'] . '\b/', $this->path)) {
                    //call to controller 
                    if (file_exists($controllerPath . $route['controller'] . '.php')) {
                        require_once( $path . '/' . $rootFolder . '/src/Controller/' . $route['controller'] . '.php');
                        $className ='\App' . '\\' . $route['controller'];
                        if (class_exists($className))
                        {
                            $controller = new $className;
                            if ($controller instanceof Controller) {
                                // call to methode associate to the route 
                                $methode = $route['methode'];
                                // check if methode exist (methode_exist)
                                if(method_exists($className, $methode)){
                                    $controller->$methode();
                                }
                                else{
                                    throw new Exception('La méthode '. $methode . ' n\'existe pas dans le class '. $className . ' à la ligne ' . __LINE__);
                                }
                            } else {
                                throw new Exception('L\'objet $controller  n\'est pas une instance de la class Controller. ligne ' . __LINE__);
                            }
                        } else {
                            throw new Exception('la classe ' . $route["controller"] . ' n\'existe pas. Ligne ' . __LINE__);
                        }
                    } else {
                        throw new Exception('le fichier  ' . $route['controller'] . '.php n\'existe pas. Ligne ' . __LINE__);
                    }
                    // }

                }
            }
            return;
        } else {

            throw new Exception('Le fichier routes.yaml dans le dossier config n\'a pas etait trouver ');
        }





        // no route found 
        $this->sendResponse('404.html');
    }

    private function autoload(string $class)
    {
    }


    private function sendResponse(string $filename)
    {
        header('Location:' . $this->pathTemplate . $filename);
        return;
    }
}
