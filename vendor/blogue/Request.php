<?php

namespace Blogue;

use Exception;

class Request
{

  

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
                return '';
            }

        }
        return '';
    }
  
    public function sessionDestroy(){
        unset($_SESSION['user']);
        return session_destroy();
    }



    public function getPost(string $index): string
    {
        if (!empty($_POST[$index])) {
            return $_POST[$index];
        }
        return '';
    }

    public function getGet(string $index): string
    {
        if (!empty($_GET[$index])) {
            return $_GET[$index];
        }
        return '';
    }
}
