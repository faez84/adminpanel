<?php
/**
 * Created by PhpStorm.
 * User: Benutzer
 * Date: 12/26/2018
 * Time: 9:43 PM
 */

namespace App\Utils\Models;


 class Sport
{

 public $r = 8;
 public $n = 7;

     public function __sleep()
     {
         return array('n');
     }

     public function __wakeup()
     {
         $this->r = 97;
     }
}