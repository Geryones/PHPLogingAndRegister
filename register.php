<?php
/**
 * Formular für die Registrierung eines neuen Users
 */
include'includes/overall/header.php';


    if (Input::exists()) {

        if(Token::check(Input::get('token'))) {
            $validate = new Validate();

            /**
             * Hier defniere ich die regeln die auf ein feld angewendet werden
             * der key des assoziativen array muss den namen des eingabe feldes haben
             * hier werden die regeln nur geschrieben, überprüft werden sie in validate
             */
            $validation = $validate->check($_POST, array(
                'username' => array(
                    'name'=>'Username',
                    'required' => true,
                    'min' => 2,
                    'max' => 20,
                    'numbersOnly'=>false,
                    'unique' => 'users'
                ),
                'password' => array(
                    'name'=>'Password',
                    'required' => true,
                    'min' => 8,
                    'mediumSecurity'=>true //gross und kleinbuchstaben, minimum eine zahl
                ),
                'password_again' => array(
                    'name'=>'Password Repetition',
                    'required' => true,
                    'matches' => 'password'
                ),
                'name' => array(
                    'name'=>'Name',
                    'required' => true,
                    'min' => 2,
                    'max' => 50

                ),
            ));

            if ($validation->passed()) {
                $user= new User();

                /**
                 * falls die eingaben validiert werden können
                 * wird versucht den user in der datenbank zu speichern
                 */
                try{
                    $user->create(array(
                        'username'=>Input::get('username'),
                        'password'=>Hash::make(Input::get('password')),
                        'name'=>Input::get('name'),
                        'joined'=>date('Y-m-d H:i:s'),
                        'group'=>1
                    ));
                   // echo 'register gone good';
                    Session::flash('home', 'You have been registered and can now log in!');
                    Redirect::to('index.php');

                }catch (Exception $e){
                   Session::flash('failed', 'the registration failed');
                    die($e->getMessage());
                }
            } else {
                foreach ($validation->errors() as $error) {
                    echo $error, '<br>';
                }
            }
        }
    }

?>
<h1>Register</h1>
   <p>
        <form action="" method="post">
         <ul>
                <li>
                    <label for="username"> Username:</label><br>
                    <input type="text"  name="username" id="username" value="<?php echo escape(Input::get('username'));?>" autocomplete="off" >
                </li>
                <li>
                    <label for="password">Password:</label><br>
                    <input type="password" name="password" id="password" >
                </li>
                <li>
                    <label for="password_again">Confirm Password:</label><br>
                    <input type="password" name="password_again" id="password_again" >

                </li>
                <li>
                    <label for="name">Name:</label><br>
                    <input type="name" name="name" id="name" value="<?php echo escape(Input::get('name'));?>" >
                </li>
                <li>
                    <input type="hidden" name="token"   value="<?php echo Token::generate(); ?>">
                   <input type="submit" value="Register">
                </li>
            </ul>
        </form>
    </p>

<?php include'includes/overall/footer.php';?>