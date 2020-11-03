<?php

namespace Blogue;

class Request
{


    public function __construct()
    {
    }

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
                if(!empty($_POST)) return $_POST;
                break;
            case 'GET':
                if(!empty($_GET)) return $_GET;
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
