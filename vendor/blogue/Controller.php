<?php
namespace Blogue;

use Blogue\Request;
use Twig\Environment;
use Twig\TwigFunction;
use Twig\Loader\FilesystemLoader as Filesystem;

class Controller{
   
    public function render(string $template, array $options = null){
        // no use attribut for $rooteFolder,$_SERVER['DOCUMENT_ROOT']  
        // because when this function call from template he can't acces to her 
        $path = $_SERVER['REQUEST_URI'];
        $root = explode("/", $path);
        $rooteFolder = $root[1];

        $loader = new Filesystem($_SERVER['DOCUMENT_ROOT'] . '/'. $rooteFolder .'/src/templates');
        $twig = new Environment($loader);
        $function = new TwigFunction('path', function (string $path, array $options = null) {
            $request = $_SERVER['REQUEST_URI'];
            $root = explode("/", $request);
            $rooteFolder = $root[1];

            if(!empty($options))
            {
                $pathPublic = DIRECTORY_SEPARATOR . $rooteFolder. DIRECTORY_SEPARATOR."public/". $path. '/'. $options['id'];
            }
            else{
                $pathPublic = DIRECTORY_SEPARATOR . $rooteFolder. DIRECTORY_SEPARATOR."public/".$path;

            }
            return $pathPublic;
        });
        $twig->addFunction($function);
        $template = $twig->load($template);

        if(!empty($options))
        {
             echo $template->render($options);
            return;
        }
        echo $template->render();


        

    }
}