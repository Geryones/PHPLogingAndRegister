<?php
include'includes/overall/header.php';
/**
 * die hauptseite und einstiegspunkt
 *
 */
?>

        <h1>Home</h1>
        <p>
            <?php
            // falls eine message existiert, wird diese geflashed
            if(Session::exists('home')){
                echo Session::flash('home');
            }
            if(Session::exists('picUpload')){
                echo Session::flash('picUpload');
            }



            $user = new User();


            if($user->isLoggedIn()){
                ?>
                <p>Hello <a href="profile.php?user=<?php echo escape($user->data()->username); ?>"> <?php echo escape($user->data()->username); ?> !</a>

                <ul>
                    <li> <a href="logout.php"> Log out</a> </li>
                    <li> <a href="update.php"> Update</a> </li>
                    <li> <a href="changepassword.php"> change password</a> </li>
                </ul>


            <?php
            }else{
                echo '<p> You need to  <a href="login.php"> log in </a> or <a href="register.php"> register </a> </p>';
            }
            ?>
        </p>



<?php
if($user->hasPermission('Admin')){
    echo'<p> You have admin rights</p>';

}
include'includes/overall/footer.php';
?>

