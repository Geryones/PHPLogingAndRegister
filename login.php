<?php

include'includes/overall/header.php';


/**
 * seit auf der sich der user einloggen kann
 */

if(Input::exists()){
    //token von user wird mit dem token auf dem server verglichen
    if(Token::check(Input::get('token'))){
        $validate = new Validate();

        //es wird geprüft ob man ein username und pw eingegeben hat
        $validation = $validate->check($_POST,array(
            'username'=>array('required'=>true),
            'password'=>array('required'=>true)
        ));

        //wenn beides eingegeben wurde
        if($validation->passed()){
            $user=new User();
            /**
             * falls der user auswählt, dass sich die seite an ihn erinnern soll wird diese information weitergegeben
             *
             */
            $remember = (Input::get('remember')==='on')? true : false;
            //in der datenbank wird nachgeschaut ob die kombination von username und password existiert
            $login = $user->login(Input::get('username'),Input::get('password'),$remember);

            //wenn der login erfolgreich ist
            if($login){
                //weiterleiten zu index
                Redirect::to('index.php');
            }else{
                //login failed.. user wird informiert
                echo 'sry, login failed';
            }
        }else{
            //falls man vergisst ein pw oder username anzugeben
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
                    Username:<br>
                    <input type="text" name="username" id="username" autocomplete="off">
                </li>
                <li>
                    Password:<br>
                    <input type="password" name="password" id="password" autocomplete="off">
                </li>
                <li>
                    <label for="remember">
                        <input type="checkbox" name="remember" id="remember">Remember me
                    </label>
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

<?php include'includes/overall/footer.php';?>