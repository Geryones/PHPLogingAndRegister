<?php
/**
 * Created by PhpStorm.
 * User: mai714
 * Date: 22.09.2015
 * Time: 08:51
 */

/**
 * Class Validate wird verwendet um eingabem nach vordefinierten regeln zu validieren
 * falls ein input nicht den kriterien entsprich kann man eine error meldung schreiben.
 * falls es mehere error gibt werden alle zusammen am ende ausgegeben
 */
class Validate{

    private $_passed=false,
            $_passedFile=false,
            $_errors=array(),
            $_db=null;

    /**
     * constructer der die instanz des datenbank-wrappers abruft
     */
    public function __construct(){
        $this->_db= DB::getInstance();
    }

    /**
     * funktion um einen string zu validieren
     *
     * @param $source globales array ( post oder get)
     * @param array $items array(assoziativ) mit den regeln für die inputfelder, keys= name des inputfeldes
     * @return $this true falls keine erorrs, sonst array mit erros
     */
    public function check($source, $items=array()){

        /**
         * drill down auf die werte  der regeln ( zb maxlength=8; 8 = wert der regel maxLength)
         *                                      (zb. maxLength=8; 8 = rule_value of the rule called maxLength)
         */
        //drill down auf die erste ebene mit den namen der inputfelder
        foreach($items as $item=>$rules){
            $itemName=$rules['name'];
            foreach($rules as $rule=>$rule_value){
                /**
                 * value ist der user input
                 */
                $value=trim($source[$item]);
                $item=escape($item);

                if($rule==='required' && empty($value) &&$rule_value){
                    $this->addError("{$itemName} is required");
                }else if(!empty($value)){
                    /**
                     * switch statement für alle regeln
                     * individuelle errornachricht falls etwas nicht den anforderungen entspricht
                     */
                    switch($rule){
                        case 'min':
                            if(strlen($value)<$rule_value){
                                $this->addError("{$itemName} must be a minimum of {$rule_value} characters");
                            }
                            break;
                        case 'max':
                            if(strlen($value)>$rule_value){
                                $this->addError("{$itemName} must be a maximum of {$rule_value} characters");
                            }
                            break;
                        case 'mediumSecurity':
                            if($rule_value){
                                if (!preg_match_all('$\S*(?=\S*[a-z])(?=\S*[A-Z])(?=\S*[\d])\S*$',  $value)) {
                                    $this->addError("{$itemName} must contain at least 1 lower Case, 1 Uper Case and 1 Number");
                                }
                            }

                            /**
                                Explaining $\S*(?=\S{8,})(?=\S*[a-z])(?=\S*[A-Z])(?=\S*[\d])(?=\S*[\W])\S*$
                                $ = beginning of string
                                \S* = any set of characters
                                (?=\S{8,}) = of at least length 8
                                (?=\S*[a-z]) = containing at least one lowercase letter
                                (?=\S*[A-Z]) = and at least one uppercase letter
                                (?=\S*[\d]) = and at least one number
                                (?=\S*[\W]) = and at least a special character (non-word characters) underscore doesnt count
                                $ = end of the string
                            **/
                            break;
                        case 'numbersOnly':
                            if(!$rule_value){
                                if (!preg_match('/[a-zA-Z]/',  $value)) {
                                    $this->addError("{$itemName} must contain at least 1 letter");
                                }
                            }
                            break;

                        case 'matches':
                            if($value != $source[$rule_value]){
                                $this->addError("{$rule_value} must match {$itemName}");
                            }
                            break;
                        /**
                         * eintrag wird auf seine einmaligkeit überprüft
                         * welche tabelle das überprüft werden soll wird als regelwert in register.php angegeben ( für username  ist es users)
                         *
                         */
                        case 'unique':
                            $check=$this->_db->get($rule_value,array($item,'=',$value));
                            if($check->count()){
                                $this->addError("{$itemName} already exists");
                            }
                            break;

                    }
                }
            }

        }
        if(empty($this->_errors)){
            $this->_passed=true;
        }

        return $this;
    }

    /**
     * funktion um eine bilddatei zu validieren
     *
     * @param $source globales array $_POST
     * @param array $items assozitives array mit den Validierungs-regeln/-werten
     * @return $this true falls es keine Error gibt, sonst Array mit den Errormeldungen
     */
    public function checkFile($source,$items=array()){

        //echo 'reached validation<br>';




         //drill down auf die erste ebene, welche den namen des inputfeldes enthält
        foreach($items as $item=>$rules){
            if($source[$item]['error']!=0) {
                $this->addError("Fehler beim Upload des Bildes, es stehen keine weiteren Informationen zur Verfügung");
            }else{
                $itemName = $rules['name'];


                foreach ($rules as $rule => $ruleValue) {

                    //um item im $_FILES anzusprechen
                    $theFile = $source[$item];

                    //wenn ein feld 'required' ist und leer, muss es nicht komplet validiert werden
                    if ($rule === 'required' && empty($theFile) && $ruleValue) {
                        $this->addError("{$itemName} is required");
                    } else if (!empty($theFile)) {
                        //Eigenschaften des Bildes
                        $fileType=exif_imagetype($theFile['tmp_name']);
                        list($width, $height,$attr)=getimagesize($theFile['tmp_name']);
                        switch ($rule) {

                            case 'maxFileSize':
                                if(filesize($theFile['tmp_name'])>$ruleValue){
                                    $this->addError("{$itemName} is too big");

                                }
                                break;
                            case 'type':
                                if($ruleValue==='picture') {

                                    if ($fileType != IMAGETYPE_JPEG && $fileType != IMAGETYPE_PNG && $fileType != IMAGETYPE_GIF) {
                                        $this->addError("this file Type is not allowed, allowed are: jpeg, jpg, png and gif");
                                    }
                                }//noch nicht implementiert, man könnte noch andere typen adden
                                break;
                            case 'maxHeight':
                                if($height>$ruleValue){
                                    $this->addError("The {$itemName} is to High XD");
                                }
                                break;
                            case 'maxWidth':
                                if($width>$ruleValue){
                                    $this->addError("The {$itemName}'s Width is to big");
                                }
                                break;
                            case 'minHeight':
                                if($height<$ruleValue){
                                    $this->addError("the {$itemName} is not High enough XD");
                                }
                                break;
                            case 'minWidth':
                                if($width<$ruleValue){
                                    $this->addError("the {$itemName}'s Width is not big enough");
                                }
                                break;
                        }
                    }
                }
            }

        }
       // echo 'survived all foreach<br>';
        if(empty($this->_errors)){
            //echo'all went goooooood<br>';
            $this->_passedFile=true;
        }
        return $this;
    }


    /**
     * funktion um einen error dem error-array hinzuzufügen
     *
     * @param $error errornachricht
     *
     */
    private function addError($error){
        $this->_errors[]=$error;
    }

    /**
     * funktion ruft error-array ab
     *
     * @return array mit allen errors
     *
     */
    public function errors(){
        return $this->_errors;
    }

    /**
     * funktion setzt flag für die validierung
     *
     * @return bool status der validierung
     *
     */
    public function passed(){
        return $this->_passed;
    }
    public function filePassed(){
        return $this->_passedFile;
    }

    /**
     * funktion um die error direkt nebem den eingabe felder zu präsentieren
     *
     * @param $type inputfield name
     * @return null errormeldung falls vorhanden
     *
     *
     *
     * !! NOCH NICHT IMPLEMENTIERT!!
     */
    public function fieldError($type)
    {
        $fieldError = null;
        foreach ($this->_errors as $error)
        {
            $word = explode(' ', $error ,-2);
            if($word[0] === $type)
            {  $fieldError = $error;   }
        }
        return $fieldError;
    }
}