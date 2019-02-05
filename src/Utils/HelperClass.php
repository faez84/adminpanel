<?php

/**
 * Created by PhpStorm.
 * User: Benutzer
 * Date: 12/31/2018
 * Time: 12:42 AM
 */
namespace App\Utils;
use Spider\Models\AbstractModel;

class HelperClass
{
    /** @var  AbstractModel */
    public static $model;
    public static function getGroupValidation(): string
    {
        return self::$model->getGroupValidation();
    }

}