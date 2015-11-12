<?php

include'includes/overall/header.php';

/**
 * auf dieser seit kann der user sein passwort ändern
 */

$user= new User();
//wenn der user nicht eingeloggt ist, hat er hier nicht verloren, daher weiterleiten auf index
if(!$user->isLoggedIn()){
    Redirect::to('index.php');
}

if(Input::exists()){
    //token beim user muss mit token auf sever übereinstimmen
    if(Token::check(Input::get('token'))){
        $validate = new Validate();

        //die neuen passwörter werden validiert
        $validation = $validate->check($_POST,array(
            'password_current'=>array(
                'required'=>true
            ),
            'password_new'=>array(
                'required'=>true,
                'min'=>6
            ),
            'password_new_again'=>array(
                'required'=>true,
                'min'=>6,
                'matches'=>'password_new'
            )
        ));
        //validierung war erfolgreich
        if($validation->passed()){
            //altes password mit dem password in der datenbank verglichen
            if(password_verify(Input::get('password_current'), $user->data()->password)){

                //wenn auch das stimmt, kann das neue passwort in die datenbank gespeichert werden
                $user->update(array(
                    'password'=>Hash::make(Input::get('password_new'))
                ));
                //der user wird auf index weitergeleitet, dort wird die message angezeigt, dass sein pw aktualisiert wurde
                Session::flash('home','Your password has been changed');
                Redirect::to('index.php');
            }else{
                //falls es zu einem problem beim aktualisieren der db kommt, wird eine meldung ausgegeben
                echo 'Your current password is wrong';

            }

        }else{
            //falls die validierung nicht erfolgreich war, werden die errors ausgegeben
            foreach($validation->errors() as $error){
                echo $error, '<br>';
            }
        }
    }

}


?>

    <div class="widget">
        <h2>Change Password</h2>
        <div class="inner">
            <form action="" method="post">
                <ul id="login">
                    <li>
                        Current Password:<br>
                        <input type="password" name="password_current" id="password_current" autocomplete="off">
                    </li>
                    <li>
                        New Password:<br>
                        <input type="password" name="password_new" id="password_new" autocomplete="off">
                    </li>
                    <li>
                        Repeat New Password:<br>
                        <input type="password" name="password_new_again" id="password_new_again" autocomplete="off">
                    </li>
                    <li>
                        <input type="hidden" name="token" value="<?php echo Token::generate(); ?>">
                        <input type="submit" value="Change">
                    </li>
                    <li>
                        <a href="register.php">Register</a>
                    </li>
                </ul>
            </form>
        </div>
    </div>

<?php include'includes/overall/footer.php';?>