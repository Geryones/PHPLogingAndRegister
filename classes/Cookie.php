<?php
/**
 * Created by PhpStorm.
 * User: mai714
 * Date: 22.09.2015
 * Time: 08:47
 */

/**
 * Class Cookie
 * in dieser klasse wird das cookie verwaltet
 * wird gebraucht bei der funktion remember me ( dammit der user automatisch eingeloggt wird)
 */
class Cookie{

    /**
     * funktion die prüft ob ein cookie mit einem bestimmten name existiert
     *
     * @param $name name des cookies
     * @return bool status ob das cookie existiert
     */
    public static function exists($name){
        return (isset($_COOKIE[$name])) ? true: false;
    }

    /**
     * funktion um ein cookie abzurufen
     *
     * @param $name name des cookies welches wir möchten
     * @return mixed falls das cookie existiert, erhalten wir das cookie, sonst false
     */
    public static function get($name){
        return $_COOKIE[$name];
    }

    /**
     * funktion um ein cookie zu erstellen
     *
     * @param $name name des cookies
     * @param $value wert
     * @param $expiry ablaufdatum des cookies
     * @return bool status ob es funktioniert hat
     */
    public static function put($name, $value, $expiry){
        if(setcookie($name,$value, time()+$expiry,'/')){
            return true;
        }
        return false;
    }


    /**
     * funktion um ein cookie zu löschen
     *
     * @param $name name des cookies welches gelöscht werden soll
     *
     * man setzt beim cookie das ablaufdatum auf einen zeitpunkt der schon passé ist
     */
    public static function delete($name){
        self::put($name,'',time()-1);
    }


}