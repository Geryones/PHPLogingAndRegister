<?php
include 'includes/overall/header.php';

/**
 * Auf dieser Seite kann der user Bilder hochladen
 * später eventuell auch andere Dateiformate
 *
 */

?>

    <h1>This is upload</h1>
<?php
$user = new User();
//Wenn ein Bild / datei ausgesucht wurde
if(Input::exists()){
   // echo 'check.. input exists <br>';
    $validate= new Validate();

    //bild wird validiert
    $validate->checkFile($_FILES,array(
        'fileUpload'=>array(
            'name'=>'Picture',
            'type'=>'picture',
            'required'=>true,
            'maxFileSize'=>3072*1000,//erlaubt 3MB grosse bilder
            'maxWidth'=>4000,
            'maxHeight'=>4000,
            'minWidth'=>400,
            'minHeight'=>400
        )
    ));

    $validate->check($_POST,array(
        'description'=>array(
            'name'=>'Picture Description',
            'required'=>true,
            'min'=>5,
            'max'=>1000
        )
    ));

    //falls validierung erfolgreich war
    if($validate->passed()&&$validate->filePassed()){
        $saver = new SaveFile();



        //echo 'file upload validation passed<br>';


        //echo ' dir erstellt <br>';

        //wem gehört das bild?
        $owner=$user->data()->id;

       // echo 'owner ermittelt '.$owner.'<br>';

        //bild wird gespeichert
        if($saver->savePicture($_FILES,'fileUpload',$owner,Input::get('description'))){
            //Session::flash('picUpload','Your picture was successfully saved');
            //Redirect::to('index.php');
            echo 'upload successful<br>';
        }else{
            echo' there was an error.. we are sorry <br>';
        }





    }else {
        foreach ($validate->errors() as $error) {

            echo $error, '<br>';
        }
    }







}
?>

<?php

if($user->isLoggedIn()) {
?>
    <form enctype="multipart/form-data" action="" method="post">
        <input type="file" name="fileUpload" required="required"  /><br><br>
        <textarea name="description" cols="40" rows="10" required="required"></textarea><br><br>
        <input type="submit" name="upload" value="Upload" />
    </form>

<?php
}else{
    echo '<p> You need to  <a href="login.php"> log in </a> or <a href="register.php"> register</a> before you can use this functionality </p>';
}







include 'includes/overall/footer.php';
    ?>
