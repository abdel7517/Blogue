<?php

namespace Blogue;

use Exception;

class Request
{

    public function get(string $methode, string $index = null)
    {
        if ($index !== null) {
            switch ($methode) {
                case "POST":
                    return $this->getPost($index);
                    break;
                case 'GET':
                    return $this->getGet($index);
            }
        };

        switch ($methode) {
            case "POST":
                if (!empty($_POST)) return $_POST;
                break;
            case 'GET':
                if (!empty($_GET)) return $_GET;
        }
    }

    public function getMethode(): string
    {
        if (!empty($_SERVER['REQUEST_METHOD'])) {
            return $_SERVER['REQUEST_METHOD'];
        }
        return '';
    }

    public function getPath(): string
    {
        return  $_SERVER['DOCUMENT_ROOT'];
    }
    
    public function getRequest(): string
    {
        return  $_SERVER['REQUEST_URI'];
    }

    public function getNameOfRootFolder()
    {
        $path = $_SERVER['REQUEST_URI'];
        $root = explode("/", $path);
        return $root[1];
    }

    public function newSession(string $index, $value){
        if(session_id())
        {

            $_SESSION[$index] = $value;

        }
        else{
            session_start();
            $_SESSION[$index] = $value;
        }
    }

    public function getSession(string $index){
        if(session_id())
        {
            if(!empty($_SESSION[$index])){
                return $_SESSION[$index];
            }else{
                return 'r';
            }

        }
        return 'no session';
    }
  



    private function getPost(string $index): string
    {
        if (!empty($_POST[$index])) {
            return $_POST[$index];
        }
        return '';
    }

    private function getGet(string $index): string
    {
        if (!empty($_GET[$index])) {
            return $_GET[$index];
        }
        return '';
    }
}
