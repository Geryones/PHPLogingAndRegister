<?php
/**
 * Created by PhpStorm.
 * User: mai714
 * Date: 22.09.2015
 * Time: 08:48
 */

/**
 * Class Input
 * Überprüft ob es einen Input gibt und
 * dem user die registrierung erleichtert in dem es die werte speichert und wieder einfüllt falls
 * das passwort oder der username nicht die bedingungen erfüllt. so muss er nicht nochmal alles ausfüllen
 */
class Input
{
    /**
     * funktion um zu überprüfen ob ein input existiert
     *
     * @param string $type checkt ob input mit post oder get übermittelt wird
     * @return bool wenn es einen input gibt
     */
    public static function exists($type = 'post')
    {
        switch ($type) {

            case 'post':
                return (!empty($_POST)) ? true : false; //wenn post nicht empty, return(?) true sonst (:) false
                break;
            case 'get':
                return (!empty($_GET)) ? true : false;
                break;
            default:
                return false;
                break;
        }
    }

    /**
     * funktion um einen bestimmten input abzurufen
     *
     * @param $item gewünschtes inputfeld
     * @return string inhalt von $item
     */
    public static function get($item){
        if(isset($_POST[$item])){
            return $_POST[$item];
        }else if(isset($_GET[$item])){
            return $_GET[$item];
        }
        return '';
    }


}