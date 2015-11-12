<?php
/**
 * Created by PhpStorm.
 * User: mai714
 * Date: 22.09.2015
 * Time: 08:41
 */

include'includes/overall/header.php';

/**
 * seite um account daten zu aktualisieren
 */

$user= new User();

//wenn der user nicht eingeloggt ist, hat er auf dieser seite nichts verloren... daher redirect zu index
if(!$user->isLoggedIn()){
    Redirect::to('index.php');
}


if(Input::exists()){
    //überprüfen des tokens, um csrf zu verhindern ( cross-site request forgery)
    if(Token::check(Input::get('token'))){
        $validate=new Validate();

        //der input wird den im assoziativen array angegebenen regeln validiert
        $validation=$validate->check($_POST, array(
            'name'=>array(
                'required'=>true,
                'min'=>2,
                'max'=>50
            )
        ));

        //wenn die validation erfolgreich ist wird die information aktualisiert
        if($validation->passed()){
           try{
               $user->update(array(
                   'name'=>Input::get('name')
               ));

               //dammit der user ein feedback bekommt, wird eine message für den user gespeichert
               Session::flash('home', 'Your details have been updated');
               //weiterleiten zu index, dort wird die message angezeigt ( geflahsed) und anschliessend gelöscht
               Redirect::to('index.php');

           }catch (Exception $e){
               //falls beim updaten der informationen ein fehler passeirt wird dieser ausgegeben
               die($e->getMessage());
           }
        }else{
            //falls die validierung fehlschlägt, werden die errors ausgegeben
            foreach($validation->errors() as $error){
                echo $error, '<br>';
            }
        }
    }
}

?>

<div class="widget">
    <h2>Update Information</h2>
    <div class="inner">
        <form action="" method="post">
            <ul id="login">
                <li>
                    <label for="name"> Name </label><br>
                    <input type="text" name="name" id="name" value="<?php echo escape($user->data()->name); ?>">
                </li>
                <li>
                    <input type="hidden" name="token" value="<?php echo Token::generate(); ?>">
                    <input type="submit" value="Update">
                </li>
            </ul>
        </form>
    </div>
</div>

<?php include'includes/overall/footer.php';?>