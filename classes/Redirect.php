<?php
/**
 * Created by PhpStorm.
 * User: mai714
 * Date: 22.09.2015
 * Time: 08:49
 */

/**
 * Class Redirect
 * um den user auf eine andere seite weiterzuleiten
 */
class Redirect{

    /**
     * funktion um einen user weiterzuleiten
     *
     * @param null $location auf welche seit der user weitergeleitet werden soll
     */
    public static function to($location=null){
        if($location){

            /**
             * hier können diverse error meldungen implementiert werden
             */
            if(is_numeric($location)){
                    switch($location) {
                    case 404:
                        header('HTTP/1.0 404 Not Found');
                        include'includes/error/404.php';
                        exit();
                        break;
                }
            }
            header('Location: '.$location);
            exit();
        }
    }
}