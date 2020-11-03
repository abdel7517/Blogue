<?php
namespace Blogue;

use Twig\Environment;
use Twig\Loader\FilesystemLoader as Filesystem;
use Twig\TwigFunction;
use Blogue\Request;

class Controller{
    private $request;
    public function __construct()
    {
        $this->request = New Request;
    }

    public function render(string $template, ?array $options){
        $loader = new Filesystem('../src/templates');
        $twig = new Environment($loader);
        // $function = new TwigFunction('path', function (string $path, ?array $options) {
        //     $pathRoot = $this->request->getPath();
        //     $pathPublic = $pathRoot . '/projet_blogue/src/templates' . '/'. $path;
        //     return $pathPublic;
        // });
        // $twig->addFunction($function);
        $template = $twig->load($template);

        if(!empty($options))
        {
             echo $template->render($options);
            return;
        }
        echo $template->render();


        

    }
}