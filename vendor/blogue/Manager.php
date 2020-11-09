<?php
namespace Blogue;

use PDO;

class Manager
{
    protected function dbConnect()
    {
        $bdd = new PDO('mysql:host=localhost;dbname=blogue;charset=utf8', 'root', '', array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));
        return $bdd;
    }
}
