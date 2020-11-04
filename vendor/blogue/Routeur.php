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

    private $request, $pathTemplate, $path, $rootFolder, $requestURI, $controllerPath;

    public function __construct()
    {
        $this->request = new Request;
        $this->path = $this->request->getPath();
        $this->requestURI = $this->request->getRequest();
        $this->rootFolder = $this->request->getNameOfRootFolder();
        $this->controllerPath =  $this->path . '/' .  $this->rootFolder  . '/src/Controller/';
        $this->pathTemplate = $this->path . '/' . $this->rootFolder . '/src/templates/';
        $this->getRoute();
    }


    public function getRoute()
    {
        $routesFile = $this->path . '/' . $this->rootFolder . '/config/routes.yaml';
        // check if file exist 
        if (file_exists($routesFile)) {
            $routes = Yaml::parseFile($this->path . '/' . $this->rootFolder . '/config/routes.yaml');
            if (!empty($routes)) {
                foreach ($routes as $route) {
                    if (preg_match($route['path'], $this->requestURI)) {
                        $this->getController($route);
                    }
                }
            }
            return;
        } else {

            throw new Exception('Le fichier routes.yaml dans le dossier config n\'a pas etait trouver ');
        }
        // no route found 
        $this->sendResponse('404.html');
    }

    private function getController(array $route)
    {
        $controller = $this->controllerPath;
        if (file_exists($controller)) {
            require_once($this->path . '/' . $this->rootFolder . '/src/Controller/' . $route['controller'] . '.php');
            $className = '\App' . '\\' . $route['controller'];
            if (class_exists($className)) {
                $controller = new $className;
                if ($controller instanceof Controller) {
                    // call to methode associate to the route 
                    $methode = $route['methode'];
                    // check if methode exist (methode_exist)
                    if (method_exists($className, $methode)) {
                        $controller->$methode();
                    } else {
                        throw new Exception('La méthode ' . $methode . ' n\'existe pas dans le class ' . $className . ' à la ligne ' . __LINE__);
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
    }


    private function sendResponse(string $filename)
    {
        header('Location:' . $this->pathTemplate . $filename);
        return;
    }
}
