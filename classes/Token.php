<?php
/**
 * Created by PhpStorm.
 * User: mai714
 * Date: 22.09.2015
 * Time: 08:50
 */


/**
 * Class Token diese klasse erhöht die sicherheit der applikation
 * mit dem token kann Cross Site Request Forgery ( CSRF) verhindert / eingschränkt werden
 */
class Token{

    /**
     * funktion setzt eine unique-id in der session
     *
     * @return string mit md5 unique-ID
     *
     */
    public static function generate(){
        return Session::put(Config::get('session/token_name'),md5(uniqid()));
    }

    /**
     * funktion die überprüft ob das vom user verwendete token, mit dem token übereinstimmt, dass dem user in der session zugewiesen ist
     *
     * @param $token aktuelles token vom user
     * @return bool check ob es mit dem token in der session übereinstimmt
     *
     *
     */
    public static function check($token){
        $tokenName=Config::get('session/token_name');

        if(Session::exists($tokenName)&& $token=== Session::get($tokenName)){
            Session::delete($tokenName);
            return true;
        }
        return false;
    }
}