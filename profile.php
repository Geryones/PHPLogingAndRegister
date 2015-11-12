<?php
/**
 * Created by PhpStorm.
 * User: mai714
 * Date: 22.09.2015
 * Time: 08:41
 */

/**
 * auf dieser seit kann der user die informationen zu seinem profil betrachten
 * momentan ist es leider nocht möglich informationen von anderen users zu betrachten,
 * da der username wia get übermittelt wird
 * vlt mit _data-> lookup in datenbank
 */

include 'includes/overall/header.php';


if($username=Input::get('user')){

    //user wird anhand des usernames in der datenbank gesucht
    $user= new User($username);
    if(!$user->exists()){
        Redirect::to(404);
    }else{
        $data=$user->data();
    }

}else{
    Redirect::to('index.php');
}


?>

<h3><?php echo escape($data->username)?></h3>

<p>Full name: <?php echo escape($data->name)?> </p>

<?php




include 'includes/overall/footer.php';