<?php
/**
 * Created by PhpStorm.
 * User: mai714
 * Date: 22.09.2015
 * Time: 08:49
 */


/**
 * Class Session
 * in dieser klasse wird de Session der jeweiligen user gemanaged
 *
 * ein session ist nötig, sobald es ein loging habt, dammit sich der user nicht auf jeder seite neu anmelden muss
 */
class Session{

    /**
     * funktion um zu überprüfen ob ein attribut einer session gesetzt wurde
     *
     * @param $name des zu überprüfenden keys im $_SESSION-array
     * @return bool true wenn die session existiert, false falls nicht
     */
    public static function exists($name){
        return (isset($_SESSION[$name])) ? true : false;
    }


    /**
     * funktion um ein attribut in SESSION zu setzen
     *
     * @param $name name des keys im $_SESSION-array
     * @param $value wert zum key
     * @return bool check ob es funktioniert hat
     */
    public static function put($name, $value){
        return $_SESSION[$name]=$value;
    }

    /**
     * funktion um ein attribut aus SESSION abzurufen
     *
     * @param $name key für den wert den man wünscht
     * @return mixed gibt den wert für den key zurück
     */
    public static function get($name){
        return $_SESSION[$name];
    }

    /**
     * funktion löscht den gewünschten teil im SESSION-array
     *
     * @param $name key im $_SESSION-array der gelöscht werden soll
     *
     *
     */
    public static  function delete($name){
        if(self::exists($name)){
            unset($_SESSION[$name]);
        }
    }


    /**
     * funktion ermöglicht es, eine nachricht auf einer seite nur einmal anzuzeigen, das heisst, wenn man die seite aktualisiert wird die meldung nicht mehr angzeigt
     *
     * @param $name name für den key im SESSION-array
     * @param string $string mitteilung
     * @return mixed string mit der mitteilung
     *
     *
     */
    public static function flash($name, $string=''){
        if(self::exists($name)){
            $session=self::get($name);
            self::delete($name);
            return $session;
        }else{
            self::put($name, $string);
        }


    }
}