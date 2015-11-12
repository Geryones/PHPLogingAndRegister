<?php
/**
 * Created by PhpStorm.
 * User: mai714
 * Date: 10.11.2015
 * Time: 09:51
 */


/**
 * Class Pagination
 * in dieser klasse wird die pagination geregelt
 */
class Pagination {


    private $_errors=false,
            $_table,
            $_origin;

    /**
     * Constructor für die klasse
     * @param $table welche tabelle in der datenbank verwendet werden soll
     * @param $origin auf welche seite die ergebnise angezeigt werden sollen ( für get parameter)
     *
     */
    public function __construct($table,$origin){
        $this->_table=$table;
        $this->_origin=$origin;
    }

    /**
     * Funktion um die gesammt grösse zu ermitteln
     * @param array $where wenn man nicht die ganze tabelle wünscht, kann man hier kriterien angeben ( feld, = > < <= >=, kriterium)
     * @return bool|int bei erfolg, gibt es die anzahl an zeilen zurück, ansonsten true für einen error
     */
    public function getTotalRows($where=array()){
        //wenn bei where keine angaben gemacht wurden
        if(count($where)===0) {
            return DB::getInstance()->actionAll('SELECT *', $this->_table)->count();
        }else if(count($where===3)){
            //mit einschränkenden kriterien
            return DB::getInstance()->action('SELECT *',$this->_table,$where)->count();
        }
        //falls where nicht 0 oder 3 elemente enthält
        return $this->_errors=true;
    }

    /**
     * funktion um die maximale anzahl seiten zu berechnen
     * @param null $pageRows anzahl beiträge pro seite
     * @return float|int anzahl der seiten die benötigt werden um alle ergebnisse darzustellen
     */
    public function lastPage($pageRows=null){
        //falls keine angaben gemacht werden, wird automatisch 10 ausgewählt
        if($pageRows===null){
            $pageRows=10;
        }
        //falls der wert kleiner als 1 wird, gib  1 zurück
        if(ceil($this->getTotalRows()/$pageRows)<1){

            //echo ' gibt 1 zurück<br>';
          return 1;
        }
        //gerundeter wert ( gegen oben), entspricht der anzahl seiten die es benötigt um alle resultate anzuzeigen
        return ceil($this->getTotalRows()/$pageRows);
    }

    /**
     * funktion um den inhalt, der dargestellt wird von der datenbank abzurufen
     * @param $pagenumber für welche seitennumber der inhalt abgerufen werden soll
     * @param $pageRows wie viele zeilen sollen abgerufen werden
     * @param array $where falls es einschränkungen gibt, werden diese hier angegeben( feld, = > < <= >=, kriterium)
     * @return bool|mixed bei erfolg wird ein objekt mit den resultaten zurück gegeben, ansonsten ein true für , ja es gab einen fehler ( where nicht gleich 0 oder 3)
     */
    public function getContent($pagenumber,$pageRows,$where=array()){
        //berechnung ab wo man die zeilen haben will
        $offset=($pagenumber-1)*$pageRows;
        if(count($where)===0){
            //abfrage ohne kriterien
            return DB::getInstance()->actionAll('SELECT *',$this->_table,$offset,$pageRows)->results();
        }else if(count($where===3)){
            //abfrage mit kriterien
            return DB::getInstance()->action('SELECT *',$this->_table,$where,$offset,$pageRows)->results();
        }
        //gab einen error
        return $this->_errors=true;
    }


    /**
     * funktion um die steuereinheit der pagination zu bauen
     * @param $actualPage aktuelle seite
     * @param $pageRows anzahl zeilen pro seite
     * @return string die ganze navigationseinheit für die pagination
     */
    public function createPaginationControls($actualPage,$pageRows){

        $paginationCtrls='';
        //falls es nicht nur 1 seite braucht
        if($this->lastPage($pageRows) != 1){

            /* First we check if we are on page one. If we are then we don't need a link to
               the previous page or the first page so we do nothing. If we aren't then we
               generate links to the first page, and to the previous page. */

            /*
             *hier wird überprüft ob man sich noch auf der ersten seite bedindet oder nicht
             * falls man auf der ersten seite ist, muss die erst seite nicht als link dargestellt werden, auch
             * das "previous" ist überflüssig
             * zudem bruacht es links keine weiteren links( zahlen die seiten repränsentieren)
             */
            if ($actualPage > 1) {

                //previous wird definiert
                $previous = $actualPage - 1;
                $paginationCtrls .= '<a href="'.$this->_origin.'?page='.$previous.'">Previous</a> &nbsp; &nbsp; ';

                //hier werden die links für die 4 vorhergehenden seiten generiert, natürlich nur wenn die seite grösser als 0 ist
                for($i = $actualPage-4; $i < $actualPage; $i++){
                    if($i > 0){
                        $paginationCtrls .= '<a href="'.$this->_origin.'?page='.$i.'">'.$i.'</a> &nbsp; ';
                    }
                }
            }
            // hier wird die aktuelle seite erstellt. diese braucht keinen link, da wir uns bereits auf dieser seite befinden
            $paginationCtrls .= ''.$actualPage.' &nbsp; ';

            // mit dieser forloop werden kontiunierlich links für die nachfollgenden seiten generiert, also rechts von der aktuellen seite
            for($i = $actualPage+1; $i <= $this->lastPage($pageRows); $i++){

                $paginationCtrls .= '<a href="'.$this->_origin.'?page='.$i.'">'.$i.'</a> &nbsp; ';
                //wenn man 4 seiten generiert hat und das ende nicht erreicht wurde, wird abgebrochen, da ich nicht mehr als 4 links anzeigen will
                if($i >= $actualPage+4){
                    break;
                }
            }
            //hier wird überprüft ob man nicht bereits auf der letzten seite angekommen ist
            //falls man auf der letzten seite ist, muss der next-link nicht mehr generiert werden
            if ($actualPage !=$this->lastPage($pageRows)) {
                //"next-link" wird generiert
                $next = $actualPage + 1;
                $paginationCtrls .= ' &nbsp; &nbsp; <a href="'.$this->_origin.'?page='.$next.'">Next</a> ';
            }
        }
        return $paginationCtrls;

    }






}