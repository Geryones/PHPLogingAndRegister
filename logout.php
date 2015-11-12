<?php
/**
 * Created by PhpStorm.
 * User: mai714
 * Date: 23.09.2015
 * Time: 16:12
 */


include'includes/overall/header.php';

$user= new User();
$user->logout();

Redirect::to('index.php');

include'includes/overall/footer.php';