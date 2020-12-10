<?php

namespace Blogue;

use Blogue\Request;
use Blogue\Controller;
use Exception;
use Symfony\Component\Yaml\Yaml;

class Routeur
{

    private $request, $pathTemplate, $path, $rootFolder, $requestURI, $controllerPath, $controller, $routesFile;

    public function __construct()
    {
        $this->request = new Request;
        $this->controller = new Controller;
        $this->path = $this->request->getPath();
        $this->requestURI = $this->request->getRequest();
        $this->rootFolder = $this->request->getNameOfRootFolder();
        $this->routesFile =  Yaml::parseFile($this->path . '/' . $this->rootFolder . '/config/routes.yaml');
        $this->controllerPath =  $this->path . '/' .  $this->rootFolder  . '/src/Controller/';
        $this->pathTemplate = $this->path . '/' . $this->rootFolder . '/src/templates/';
        $this->getRoute();
    }


    public function getRoute()
    {
        $routesFile = $this->path . '/' . $this->rootFolder . '/config/routes.yaml';
        // check if file exist 
        if (file_exists($routesFile)) {
            $routes = $this->routesFile['routes'];
            if (!empty($routes)) {
                foreach ($routes as $nameOfRoute => $route) {
                    if (preg_match($route['path'], $this->requestURI)) {
                        $this->getController($route, $nameOfRoute);
                        return;
                    }
                }
                // no route found 
                http_response_code(404);
                $this->controller->render('404.html.twig');
            }
            return;
        } else {

            throw new Exception('Le fichier routes.yaml dans le dossier config n\'a pas etait trouver ');
        }
    }

    private function getController(array $route, string $nameOfRoute)
    {
        $controllerFile = $this->controllerPath . $route['controller'] . '.php';
        if ($this->checkIfFileExist($route, $controllerFile)) {
            require_once($controllerFile);

            $className = '\App' . '\\Controller\\' . $route['controller'];
            if ($this->checkIfClassExist($route, $className)) {

                $controller = new $className;
                if ($controller instanceof Controller) {
                    //check if user is loged
                    $firWall = new Firewall;
                    if ($firWall->userNeedToLog($nameOfRoute)) {
                        $this->callToLoginController();

                    } else {
                      $this->callToMethode($route, $className, $controller);
                    }
                } else {
                    throw new Exception('L\'objet $controller  n\'est pas une instance de la class Controller. ligne ' . __LINE__);
                }
            }
        }
    }



    private function checkIfFileExist(array $route, string $controllerPath)
    {
        if (file_exists($controllerPath)) {
            return true;
        } else {
            throw new Exception('le fichier  ' . $route['controller'] . '.php n\'existe pas. Ligne ' . __LINE__);
        }
    }

    private function checkIfClassExist(array $route, string $className)
    {
        if (class_exists($className)) {
            return true;
        } else {
            throw new Exception('la classe ' . $route["controller"] . ' n\'existe pas. Ligne ' . __LINE__);
        }
    }

    private function callToLoginController()
    {
        $loginControllerOnRouteFile = $this->routesFile['login'];
        $pathLoginController = $this->controllerPath . $loginControllerOnRouteFile['controller'] . '.php';
        if ($this->checkIfFileExist($loginControllerOnRouteFile, $pathLoginController)) {
            $className = '\App' . '\\Controller\\' . $loginControllerOnRouteFile['controller'];
            if ($this->checkIfClassExist($loginControllerOnRouteFile, $className)) {
                $loginController = new $className;
                $this->callToMethode($loginControllerOnRouteFile, $className, $loginController);

            }
        }
    }

    private function callToMethode($route, $className, $controller){
          // call to methode associate to the route 
          $methode = $route['methode'];
          // check if methode exist (methode_exist)
          if (method_exists($className, $methode)) {
              $controller->$methode();
          } else {
              throw new Exception('La méthode ' . $methode . ' n\'existe pas dans le class ' . $className . ' à la ligne ' . __LINE__);
          }
    }
}
