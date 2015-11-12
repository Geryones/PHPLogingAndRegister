<?php
/**
 * Created by PhpStorm.
 * User: mai714
 * Date: 22.09.2015
 * Time: 08:47
 */

/**
 * Class Config
 *mit dieser klasse wird der zugriff auf die globale variable config erleichtert
 *der zugriff auf die einzelnen elemente wird wie bei einer ordner struktur gmeacht,
 *anstatt wie bei einem array
 */
class Config{

    /**
     * funktion um die gesetzen konfigurationseinstellungen abzurufen
     *
     * @param null $path pfad für das gewünschte attribut
     * @return string $config das gwünschte attribut des globalen config-arrays
     * @return bool falls der pfad nicht gesetzt wurde wird false zurückgegeben
     *
     * ich gehe davon aus, dass nur attribute gefragt werden die auch existieren, daher überprüfe ich den pfad nicht auf seine richtigkeit
     */
    public static function get($path=null){
        if($path){
            $config=$GLOBALS['config'];
            $path=explode('/',$path);
            //wenn es gesetzt ist, wird $config ersetzt, so kommt man zum nächsten lvl und erhält am ende den gesuchten wert
            foreach($path as $bit){
                if(isset($config[$bit])){
                    $config=$config[$bit];
                }
            }
            return $config;
        }
        return false;
    }
}