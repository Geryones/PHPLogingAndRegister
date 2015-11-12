<?php
/**
 * Created by PhpStorm.
 * User: mai714
 * Date: 29.10.2015
 * Time: 10:44
 */


/**
 * Class SaveFile wird verwendet um Files zu speichern
 * bevor ein file gespeichert wird, muss es umbenannt werden, wird auch hier gemacht
 */
class SaveFile {

    private $_user;

    
    public function __construct(){
        $this->_user=new User();
    }

    /**
     * Funktion um ein bild zu speichern
     *
     * @param $files assoziatives Array mit den bilddaten ( $_FILES)
     * @param $field  name des fileupload feldes
     * @param $owner besitzer
     * @param $description beschreibung zum bild
     * @return bool status-flag
     */
    public function savePicture($files, $field,$owner,$description)
    {
       // echo 'still alife <br>';
        //der bild name
        $fileName=escape($files[$field]['name']);

        //neuer name = eine-id und der alte name
        $newFileName = uniqid().'_'.$fileName;
        //der temporär name
        $tmpName = $files[$field]['tmp_name'];

        //daten zum bild
        list($width,$height,$type,$attr)=getimagesize($tmpName);

        //das bild wird umbenannt und verschoben
        if(!move_uploaded_file($tmpName, Config::get('pictures/dirOrginal').$newFileName)){
            return false;
        }

        //die angaben zum bild werden in der datenbank gespeichert
        if(!DB::getInstance()->insert('picture',array(
                'name'=>$newFileName,
                'caption'=>'alt="'.$fileName.'"//'.escape($description), //alt-text mit // getrennt um später mit explode alt und beschreibung trennen zu können
                'owner'=>$owner,
                'width'=>$width,
                'height'=>$height
            ))){
            return false;
        }
        if(!$this->createThumnail($newFileName)){
            echo 'failed to create thumbnail<br>';
        }
        return true;

    }


    /**
     * funktion um ein thumbnail von einem bild zu erstellen, speichern und in der datenbank einzutragen
     * @param $orginalFilename name des orginal bildes
     * @return bool statusmeldung
     */
    public function createThumnail($orginalFilename){
        //masse für das thumbnail werden definiert
        $thumbWidht=75;
        $thumbHeight=50;
        //speicherort wird aus dem globalen array ausgelesen
        $thumbdir=Config::get('pictures/dirThumbnail');
        //da der name des orginals bereits unique ist, hänge ich einfach noch thumb_ davor
        $thumbname='thumb_'.$orginalFilename;

        //liste mit allen relevanten eigenschaften des orginals
        list($orginalWidth,$orginalHeight,$orginalType,$orginalAttr)=getimagesize(Config::get('pictures/dirOrginal').$orginalFilename);



        //wenn breite grösser ist, wird die breite = max thumbWidth und die höhe im verhältnis berechnet
        if($orginalWidth>$orginalHeight){
            $newWidth=$thumbWidht;
            $newHeight=intval($orginalHeight*$newWidth/$orginalWidth);
        }else{
            //hier ist das bild hochformat, das heisst die breite wird anhand der höhe berechnet
            $newHeight=$thumbHeight;
            $newWidth=intval($orginalWidth*$newHeight/$orginalHeight);
        }
        //hier werden die schwarzen ränder berechnet ( falls das bild nicht den proportionen des thumbs entspricht, dammit die proportionen des bildes erhalten bleiben
        $randX=intval(($thumbWidht-$newWidth)/2);//ränder links und rechts
        $randY=intval(($thumbHeight-$newHeight)/2); //ränder oben und unten

        //für unterschiedliche imagetypen braucht es unterschiedliche funktionen
        switch($orginalType){
            case 1:
                $imageType='ImageGIF';
                $imageCreateFrom='ImageCreateFromGIF';
                break;
            case 2:
                $imageType='ImageJPEG';
                $imageCreateFrom='ImageCreateFromJPEG';
                break;
            case 3:
                $imageType='ImagePNG';
                $imageCreateFrom='ImageCreateFromPNG';
                break;
        }

        //falls es einen entsprechenden typ gab trifft diese bedingung zu
        if($imageType){
            $orginalImage=$imageCreateFrom(Config::get('pictures/dirOrginal').$orginalFilename);
            $newImage=imagecreatetruecolor($thumbWidht,$thumbHeight);
            imagecopyresized($newImage,$orginalImage,$randX,$randY,0,0,$newWidth,$newHeight,$orginalWidth,$orginalHeight);
            $imageType($newImage,$thumbdir.$thumbname);
        }

        //mit dem namen des orginals ermittle ich noch seine id, die id schreibe ich beim thumbnail dazu, dammit ich weiss zu welchem bild es gehört
        //da alle thumbnails gleich gross sind, verzichte ich darauf, die grössen und so abzuspeichern
        $orginalImageID=DB::getInstance()->get('picture',array('name','=',$orginalFilename))->first()->id;

        //thumbnail wird zusammen mit id von parentPicture abgespeichert
        if(!DB::getInstance()->insert('thumbnail',array(
            'name'=>$thumbname,
            'parentPicture'=>$orginalImageID
        ))){
            //wenn es nicht funktioniert gibt es false zurück
            return false;
        }
        //erfolg
        return true;

    }

}