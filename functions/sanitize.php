<?php
/**
 * Created by PhpStorm.
 * User: mai714
 * Date: 22.09.2015
 * Time: 08:52
 */

function escape($string){
    return htmlentities($string, ENT_QUOTES, 'UTF-8');
}