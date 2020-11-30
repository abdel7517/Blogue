<?php

namespace Blogue;

use Blogue\Request;
use Twig\Environment;
use Twig\TwigFunction;
use Twig\Loader\FilesystemLoader as Filesystem;

class Controller
{
    private $pathToReturn = '';
   

    public function getParametersUrl()
    {
        $path = $_SERVER['REQUEST_URI'];
        $root = explode("/", $path);
        $keyRootFolder = array_search('public', $root);
        // destroy all folder name before public and the name of route 
        for($i = 0; $i <= ($keyRootFolder+1); $i ++){
            unset($root[$i]);
        }
        $ordonedArray = array();
        foreach($root as $folderName){
            $ordonedArray[] = $folderName;
        }
        return $ordonedArray;
    }
    public function render(string $template, array $options = null)
    {
        // no use attribut for $rooteFolder,$_SERVER['DOCUMENT_ROOT']  
        // because when this function call from template he can't acces to her 
        $path = $_SERVER['REQUEST_URI'];
        $root = explode("/", $path);
        $rooteFolder = $root[1];
        $loged = "";
      
            if(!empty($_SESSION['user'])){
                $loged = true;
            }

        

        $loader = new Filesystem($_SERVER['DOCUMENT_ROOT'] . '/' . $rooteFolder . '/src/templates');
        $twig = new Environment($loader);
        $twig->addGlobal('loged', $loged);
        $pathFunction = new TwigFunction('path', function (string $path, array $options = null) {
            $request = $_SERVER['REQUEST_URI'];
            $root = explode("/", $request);
            $rooteFolder = $root[1];
            $this->pathToReturn =  DIRECTORY_SEPARATOR . $rooteFolder . DIRECTORY_SEPARATOR . "public/" . $path;
            if (!empty($options)) {
                foreach ($options as $option) {
                    $this->pathToReturn = $this->pathToReturn . '/' . $option;
                }
            }
            return $this->pathToReturn;
        });
        $assetFunction = new TwigFunction('asset', function (string $path){
            $request = $_SERVER['REQUEST_URI'];
            $root = explode("/", $request);
            $rooteFolder = $root[1];
            return $this->pathToReturn =  DIRECTORY_SEPARATOR . $rooteFolder . DIRECTORY_SEPARATOR . "public/asset/" . $path;
        });
        $twig->addFunction($pathFunction);
        $twig->addFunction($assetFunction);
        $template = $twig->load($template);

        if (!empty($options)) {
            echo $template->render($options);
            return;
        }
        echo $template->render();
        return;
    }
}
