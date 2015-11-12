<?php
/**
 * Created by PhpStorm.
 * User: mai714
 * Date: 22.09.2015
 * Time: 08:48
 */

/**
 * Class Hash
 * der Übersicht zu liebe habe ich diese 2 funktionen in eine eigene klasse gepackt
 */
class Hash{

    /**
     * funktion um einen string zu verschlüsseln, mit den kosten 9 und dem algorythmus bcrypt
     *
     * @param $string string der encryptetd werden soll
     * @return bool|string falls es fehler gibt, kommt false zurück, ansonsten kommt der encryptete $string zurück
     */
    public static function make($string){
        $options = [
            'cost' => 9
        ];
        return password_hash($string, PASSWORD_BCRYPT, $options);
    }

    /**funktion um eine unique id zu generieren
     *
     * @return bool|string false falls es ein problem gibt, ansonsten ein string mit einer unique id, die zuerst an die funktion make weitergegben wurde
     *
     * die unique id wird also mit einem salt versehen und anschliessend verschlüsselt, braucht man, wenn man beim login auf remember klickt.
     * der generierte hash+salt werden dann in der datenbank zusammen mit dem user gespeichert
     */
    public static function unique(){
        return self::make(uniqid());
    }
}