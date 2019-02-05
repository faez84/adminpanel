<?php
/**
 * Created by PhpStorm.
 * User: Benutzer
 * Date: 12/21/2018
 * Time: 5:08 PM
 */

namespace App\tests;


class MovieTest
{

    private $movie;
    public function testGetListFields()
    {
        return array_map(function ($fields) {return $fields['name'];}, $this->fields);
    }
}