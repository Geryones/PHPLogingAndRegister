<?php
    require_once 'core/init.php';

    if(Input::exists()){
       if(Token::check(Input::get('token'))){
           $validate = new Validate();

           $validation = $validate->check($_POST,array(
               'username'=>array('required'=>true),
               'password'=>array('required'=>true)
           ));

           if($validation->passed()){
               $user=new User();
               $login = $user->login(Input::get('username'),Input::get('password'));

               if($login){
                   Redirect::to('index.php');
               }else{
                   echo 'sry, login failed';
               }
           }else{
               foreach($validation->errors() as $error){
                   echo $error,'<br>';
               }
           }
       }
    }

?>

<div class="widget">
    <h2>Log in/Register</h2>
    <div class="inner">
        <form action="" method="post">
            <ul id="login">
                <li>
                    Username:
                    <input type="text" name="username" id="username" autocomplete="off">
                </li>
                <li>
                    Password:
                    <input type="password" name="password" id="password" autocomplete="off">
                </li>
                <li>
                    <input type="hidden" name="token" value="<?php echo Token::generate(); ?>">
                    <input type="submit" value="Log in">
                </li>
                <li>
                    <a href="register.php">Register</a>
                </li>
            </ul>
        </form>
    </div>
</div>