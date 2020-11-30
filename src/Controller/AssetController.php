<?php 
namespace App\Controller;

use Blogue\Controller;
use Blogue\Request;

class AssetController extends Controller{

  

    public function index(){
       $request = new Request;
       $request = $request->getRequest();
       header("Content-type: text/css; charset: UTF-8");

    }
  
 }
