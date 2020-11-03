<?php 
use Blogue\Request;
use Blogue\Routeur;

$path = $_SERVER['REQUEST_URI'];
$root = explode("/", $path);
$autoloader  = $_SERVER['DOCUMENT_ROOT']. '/'.  $root[1] . '/vendor/autoload.php';
require_once($autoloader);



$routeur = new Routeur();
