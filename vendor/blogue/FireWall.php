<?php

namespace Blogue;

use Symfony\Component\Yaml\Yaml;

class Firewall
{
    private $routesFile, $request;

    public function __construct()
    {
        $this->request = new Request;
        $this->path = $this->request->getPath();
        $this->rootFolder = $this->request->getNameOfRootFolder();
        $this->routesFile =  Yaml::parseFile($this->path . '/' . $this->rootFolder . '/config/routes.yaml');
    }

    public function userNeedToLog(string $nameOfRoute)
    {
        $routesNeedPassWord = $this->routesFile['firewall'];
        foreach ($routesNeedPassWord as $routeNeedPassWord) {
            if ($routeNeedPassWord['route'] == $nameOfRoute) {
                // check if the user are loged 
                $user = $this->request->getSession('user');
                if ($user !== '') {
                    // if loged he have the good role ?
                    if($user['role'] == $routeNeedPassWord['role'] ){
                        return false;
                    }
                }

                return true;
            }
        }
        return false;
    }
}
