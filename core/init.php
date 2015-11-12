<?php
/**
 * Created by PhpStorm.
 * User: mai714
 * Date: 22.09.2015
 * Time: 08:51
 */

/*
 * muss fast überall am anfang implementiert werden und wird daher im file header.php aufgerufen
 * hier werden die globalen einstellungen vorgenommen
 */
session_start();
session_regenerate_id();
error_reporting(0);
$GLOBALS['config']=array(
    /*
     * hier sind die einstellungen für die datenbank gespeichert
     * der name bezeichnet zugleich auch die art der datenbank
     * da mit pdo gearbeitet wird, kann man hier für einen anderen datenbank typen einfach einen neuen array einfügen
     */
    'mysql'=>array(
        'host'=>'127.0.0.1',
        'username'=>'root',
        'password'=>'',
        'db'=>'lr'
    ),
    /*
     * einstellungen für das cookie, wie name und lebensdauer
     * (remember me)
     */
    'remember'=>array(
        'cookie_name'=>'hash',
        'cookie_expiry'=>604800
    ),
    /*
     * einstellungen für die session
     * name und token
     */
    'session'=>array(
        'session_name'=>'user',
        'token_name'=>'token'
    ),
    /*
     * einstellungen für das speichern von bilder
     */
    'pictures'=>array(
        'dirOrginal'=>"../phpLogingAndRegister/files/pictures/",
        'dirThumbnail'=>"../phpLogingAndRegister/files/pictures/thumbnails/"
    )
);


/**
 * diese funktion ersetzt fast alle require_once
 * wenn man auf einer seite, wo init.php verwendet wird, einen auruf einer klasse macht wird diese automatisch
 *'required'--> aufruf DB = new DB(), dann wird in dieser funktion automatisch require_once 'classes/db.php'; ausgeführt
 * $class= gewünschte klasse
 */
spl_autoload_register(function($class){
    require_once 'classes/'.$class.'.php';
});


require_once 'functions/sanitize.php';

/*
 * hier wird geprüft ob ein cookie bereits vorhanden ist
 * ist es vorhanden, heisst das , dass der user remember me angewählt hat
 *
 * (bedenken: falls jemand sich zugriff auf meine db verschafft, kann er sich selber ein cookie mit den gespeicherten hashes
 * setzen. so verschafft er sich zugriff auf die userkonten, er kann zwar keine pws ändern, ist aber dennoch als user eingeloggt!!
 *
 * möglichkeiten:
 * im cookie nur einen uniquehash, unverschlüsselt setzen, und in der db den verschlüsselten, gesalzenen hash speichern, ähnlich wie beim pw
 * überprüfung der ip-addresse?
 * )
 *
 */
if( !Session::exists(Config::get('session/session_name'))&& Cookie::exists(Config::get('remember/cookie_name'))){

    //hash aus dem cookie wird abgerufen
    $hash=Cookie::get(Config::get('remember/cookie_name'));
    //hash wird in der datenbank gesucht
    $hasCheck= DB::getInstance()->get('users_session',array('hash','=',$hash));

    //falls er was findet
    if($hasCheck->count()){
        //nimm erstes ( hoffentlich auch einziges) resultat, nimm davon die zugehörige user id
        $user= new User($hasCheck->first()->user_id);
        //erstelle den user, anhand des gerade ermittelten primary key
        $user->login();
    }
}