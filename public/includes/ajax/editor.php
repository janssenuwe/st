<?php //
error_reporting(E_ALL);

ini_set('display_errors', 1);


include_once("editorN.php");
exit;

//
//
//
//global $link;
//
//// U. Janssen,  Feb 2nd, 2018: code below does not seem to be used here.
//// see editorN for user/pwd if you need it
////$link = mysqli_connect('<db_server>','<user>','<pwd>','<dbname>');
//$link->set_charset("utf8");
//header('Content-Type: text/html; charset=UTF-8');
//mb_internal_encoding('UTF-8');
//putenv("LANG=UTF-8");
//setlocale(LC_ALL, 'de_DE@euro', 'de_DE', 'de', 'ge', 'UTF-8');
//
//
//
///**Fuehrt SQL Abfragen aus
// *
// * @param string $sql - SQL-Abfrage
// * @param function $link - Datenbankverbindung
// * @return array - Array mit Ergebnissen aus Datenbank
// */
//function query($sql, $link) {
//    mysqli_set_charset($link, 'UTF8');
////  gibt die gesuchten Werte aus der Datenbank zurück
//    $result = $link->query($sql) or die(mysqli_error($link));
//
//    if (!$result) {
//        return array();
//    }
//
//    $resultArray = array();
//    while($row = mysqli_fetch_array($result)) {
//        $resultArray[] = $row;
//    }
//
//    return $resultArray;
//}
//
//function rip_tags($string) {
//
//    // ----- remove HTML TAGs -----
//    $string = preg_replace ('/<[^>]*>/', ' ', $string);
//
//    // ----- remove control characters -----
//    $string = str_replace("\r", '', $string);    // --- replace with empty space
//    $string = str_replace("\n", ' ', $string);   // --- replace with space
//    $string = str_replace("\t", ' ', $string);   // --- replace with space
//
//    // ----- remove multiple spaces -----
//    $string = trim(preg_replace('/ {2,}/', ' ', $string));
//
//    return $string;
//
//}
//
//function explode_by_array($delim, $input) {
//    $unidelim = $delim[0];
//    $step_01 = str_replace($delim, $unidelim, $input); //Extra step to create a uniform value
//    return explode($unidelim, $step_01);
//}
//
//switch ($_POST['action']) {
//
////  Action 'checkSatzlaengeIE' wurde vom Javascript Dokument aufgerufen
//    case 'checkSatzlaengeIE':
////      Ruft die Funktion zur Ueberpruefung der Satzlaenge auf und speichert den Rueckgabewert in $new_string
//        $new_string = checkSaetze($_POST['text']);
//
////      Schreibt den String zurueck zum Javascript_dokument, um den alten Inhalt des Editors zu ersetzen
////      utf8_encode wird benoetigt um Sonderzeichen in IE darzustellen
//        echo utf8_encode($new_string);
//        break;
////  Action 'checkSatzlaengeIE11' wurde vom Javascript Dokument aufgerufen
//    case 'checkSatzlaengeIE11':
////      Ruft die Funktion zur Ueberpruefung der Satzlaenge auf und speichert den Rueckgabewert in $new_string
//        $new_string = checkSaetze($_POST['text']);
//
////      Schreibt den String zurueck zum Javascript_dokument, um den alten Inhalt des Editors zu ersetzen
//        echo $new_string;
//        break;
//
//    //  Action 'checkSatzlaenge' wurde vom Javascript Dokument aufgerufen
//    case 'checkSatzlaenge':
//
////      Ruft die Funktion zur Ueberpruefung der Satzlaenge auf und speichert den Rueckgabewert in $new_string
//        $new_string = checkSaetze($_POST['text']);
////      $new_string = checkSatzlaengeTest($_POST['text']);
////      Schreibt den String zurueck zum Javascript_dokument, um den alten Inhalt des Editors zu ersetzen
//        echo $new_string;
//        break;
//
////  Action 'checkWortlaengeIE' wurde vom Javascript Dokument aufgerufen
//    case 'checkWortlaengeIE':
////      Ruft die Funktion zur Ueberpruefung der Wortlange auf und speichert den Rueckgabewert in $new_string
//        $new_string = checkWortlaenge($_POST['text']);
//
////      Schreibt den String zurueck zum Javascript Dokument, um den alten Inhalt des Editors zu ersetzen
////      utf8_encode wird benoetigt um Sonderzeichen in IE darzustellen
//        echo utf8_encode($new_string);
//        break;
//
////  Action 'checkWortlaengeIE11' wurde vom Javascript Dokument aufgerufen
//    case 'checkWortlaengeIE11':
////      Ruft die Funktion zur Ueberpruefung der Wortlange auf und speichert den Rueckgabewert in $new_string
//        $new_string = checkWortlaenge($_POST['text']);
//
////      Schreibt den String zurueck zum Javascript Dokument, um den alten Inhalt des Editors zu ersetzen
//        echo $new_string;
//        break;
//
//    //  Action 'checkWortlaenge' wurde vom Javascript Dokument aufgerufen
//    case 'checkWortlaenge':
//
////      Ruft die Funktion zur Ueberpruefung der Wortlange auf und speichert den Rueckgabewert in $new_string
//        $new_string = checkWortlaenge($_POST['text']);
//
////      Schreibt den String zurueck zum Javascript Dokument, um den alten Inhalt des Editors zu ersetzen
//        echo $new_string;
//        break;
//
////  Action 'checkFloskelIE' wurde vom Javascript Dokument aufgerufen
//    case 'checkFloskelIE':
////      Ruft die Funktion zur Ueberpruefung der Flokseln im Edtitor auf und speichert den Rueckgabewert in $new_string
//        $new_string = checkFlosTextAngl($_POST['text'], 'floskel');
//
////      Schreibt den String zurueck zum Javascript Dokument, um den alten Inhalt des Editors zu ersetzen
////      utf8_encode wird benoetigt um Sonderzeichen in IE darzustellen
//        echo utf8_encode($new_string);
//        break;
//
//    //  Action 'checkFloskelIE11' wurde vom Javascript Dokument aufgerufen
//    case 'checkFloskelIE11':
////      Ruft die Funktion zur Ueberpruefung der Flokseln im Edtitor auf und speichert den Rueckgabewert in $new_string
//        $new_string = checkFlosTextAngl($_POST['text'], 'floskel');
//
////      Schreibt den String zurueck zum Javascript Dokument, um den alten Inhalt des Editors zu ersetzen
//        echo $new_string;
//        break;
//
//    //  Action 'checkFloskel' wurde vom Javascript Dokument aufgerufen
//    case 'checkFloskel':
////      Ruft die Funktion zur Ueberpruefung der Flokseln im Edtitor auf und speichert den Rueckgabewert in $new_string
//        $new_string = checkFlosTextAngl($_POST['text'], 'floskel');
////      $new_string = checkFloTexAngTest($_POST['text'], 'floskel');
////      Schreibt den String zurueck zum Javascript Dokument, um den alten Inhalt des Editors zu ersetzen
//        echo $new_string;
//        break;
//
////  Action 'checkNegativeWoerterIE' wurde vom Javascript Dokument aufgerufen
//    case 'checkNegativeWoerterIE':
////      Ruft die Funktion zur Ueberpruefung der Flokseln im Edtitor auf und speichert den Rueckgabewert in $new_string
//        $new_string = checkFlosTextAngl($_POST['text'], 'negativewoerter');
//
////      Schreibt den String zurueck zum Javascript Dokument, um den alten Inhalt des Editors zu ersetzen
////      utf8_encode wird benoetigt um Sonderzeichen in IE darzustellen
//        echo utf8_encode($new_string);
//        break;
//
//    //  Action 'checkNegativeWoerterIE11' wurde vom Javascript Dokument aufgerufen
//    case 'checkNegativeWoerterIE11':
////      Ruft die Funktion zur Ueberpruefung der Flokseln im Edtitor auf und speichert den Rueckgabewert in $new_string
//        $new_string = checkFlosTextAngl($_POST['text'], 'negativewoerter');
//
////      Schreibt den String zurueck zum Javascript Dokument, um den alten Inhalt des Editors zu ersetzen
//        echo $new_string;
//        break;
//
//    //  Action 'checkNegativeWoerter' wurde vom Javascript Dokument aufgerufen
//    case 'checkNegativeWoerter':
//
////      Ruft die Funktion zur Ueberpruefung der Flokseln im Edtitor auf und speichert den Rueckgabewert in $new_string
////        $new_string = checkNegativeWoerter($_POST['text']);
//
//        $new_string = checkFlosTextAngl($_POST['text'], 'negativewoerter');
//
//
////      Schreibt den String zurueck zum Javascript Dokument, um den alten Inhalt des Editors zu ersetzen
//        echo $new_string;
//        break;
//
////  Action 'checkTextverstaendnisIE' wurde vom Javascript Dokument aufgerufen
//    case 'checkTextverstaendnisIE':
////      Ruft die Funktion zur Ueberpruefung der Verständlichkeit des Textes im Editor und speichert den Rueckgabewert in $new_string
//        $new_string = checkTextverstaendnis($_POST['text']);
//
////      Schreibt den String zurueck zum Javascript Dokument, um den alten Inhalt des Editors
////      utf8_encode wird benoetigt um Sonderzeichen in IE darzustellen
//        echo utf8_encode($new_string);
//        break;
//
//    //  Action 'checkTextverstaendnisIE11' wurde vom Javascript Dokument aufgerufen
//    case 'checkTextverstaendnisIE11':
////      Ruft die Funktion zur Ueberpruefung der Verständlichkeit des Textes im Editor und speichert den Rueckgabewert in $new_string
//        $new_string = checkTextverstaendnis($_POST['text']);
//
////      Schreibt den String zurueck zum Javascript Dokument, um den alten Inhalt des Editors
//        echo $new_string;
//        break;
//
////  Action 'checkTextverstaendnis' wurde vom Javascript Dokument aufgerufen
//    case 'checkTextverstaendnis':
//
////      Ruft die Funktion zur Ueberpruefung der Verstaendlichkeit des Textes im Editor und speichert den Rueckgabewert in $new_string
////      $new_string = checkStringsInInhalt($_POST['text'], 'textverstaendnis');
//
//        //$_POST['text'] = htmlspecialchars($_POST['text']);
//        $new_string = checkTextverstaendnis($_POST['text']);
//        //$new_string = htmlspecialchars_decode($new_string);
//
////      Schreibt den String zurueck zum Javascript Dokument, um den alten Inhalt des Editors
//        echo $new_string;
//        break;
//
////  Action 'checkAnglizismenIE' wurde vom Javascript Dokument aufgerufen
//    case 'checkAnglizismenIE':
////      Ruft die Funktion zur Ueberpruefung der Anglizismen im Editor und speichert den Rueckgabewert in $new_string
//        $new_string = checkFlosTextAngl($_POST['text'], 'anglizismus');
//
////      Schreibt den String zurueck zum Javascript Dokument, um den alten Inhalt des Editors
////      utf8_encode wird benoetigt um Sonderzeichen in IE darzustellen
//        echo utf8_encode($new_string);
//        break;
//
//    //  Action 'checkAnglizismenIE11' wurde vom Javascript Dokument aufgerufen
//    case 'checkAnglizismenIE11':
////      Ruft die Funktion zur Ueberpruefung der Anglizismen im Editor und speichert den Rueckgabewert in $new_string
//        $new_string = checkFlosTextAngl($_POST['text'], 'anglizismus');
//
////      Schreibt den String zurueck zum Javascript Dokument, um den alten Inhalt des Editors
//        echo $new_string;
//        break;
//
//    //  Action 'checkAnglizismen' wurde vom Javascript Dokument aufgerufen
//    case 'checkAnglizismen':
////      Ruft die Funktion zur Ueberpruefung der Anglizismen im Editor und speichert den Rueckgabewert in $new_string
////      $new_string = checkStringsInInhalt($_POST['text'], 'anglizismen');
//        $new_string = checkFlosTextAngl($_POST['text'], 'anglizismus');
////      Schreibt den String zurueck zum Javascript Dokument, um den alten Inhalt des Editors
//        echo $new_string;
//        break;
//
////  Action 'checkNominalstilIE' wurde vom Javascript Dokument aufgerufen
//    case 'checkNominalstilIE':
////      Ruft die Funktion zur Ueberpruefung des Nominalstils im Editor und speichert den Rueckgabewert in $new_string
//        $new_string = checkNominalstil($_POST['text']);
//
////      Schreibt den String zurueck zum Javascript Dokument, um den alten Inhalt des Editors
////      utf8_encode wird benoetigt um Sonderzeichen in IE darzustellen
//        echo utf8_encode($new_string);
//        break;
//
//    //  Action 'checkNominalstilIE' wurde vom Javascript Dokument aufgerufen
//    case 'checkNominalstilIE1111':
////      Ruft die Funktion zur Ueberpruefung des Nominalstils im Editor und speichert den Rueckgabewert in $new_string
//        $new_string = checkNominalstil($_POST['text']);
//
////      Schreibt den String zurueck zum Javascript Dokument, um den alten Inhalt des Editors
//        echo $new_string;
//        break;
//
//    //  Action 'checkNominalstil' wurde vom Javascript Dokument aufgerufen
//    case 'checkNominalstil':
////      Ruft die Funktion zur Ueberpruefung des Nominalstils im Editor und speichert den Rueckgabewert in $new_string
//        $new_string = checkNominalstil($_POST['text']);
//
////      Schreibt den String zurueck zum Javascript Dokument, um den alten Inhalt des Editors
//        echo $new_string;
//        break;
//
////  Action 'checkAktivPassivIE' wurde von Javascript Dokument aufgerufen
//    case 'checkAktivPassivIE':
////      Ruft die Funktion zur Ueberpruefung der Nutzung von Aktiv und Passiv im Editor und speichert den Rueckgabewert in $new_string
//        $new_string = checkPassivAktiv($_POST['text']);
//
////      Schreibt den String zurueck zum Javascript Dokument, um den alten Inhalt des Editors
////      utf8_encode wird benoetigt um Sonderzeichen in IE darzustellen
//        echo utf8_encode($new_string);
//        break;
//
////  Action 'checkAktivPassivIE11' wurde von Javascript Dokument aufgerufen
//    case 'checkAktivPassivIE11':
////      Ruft die Funktion zur Ueberpruefung der Nutzung von Aktiv und Passiv im Editor und speichert den Rueckgabewert in $new_string
//        $new_string = checkPassivAktiv($_POST['text']);
//
////      Schreibt den String zurueck zum Javascript Dokument, um den alten Inhalt des Editors
//        echo $new_string;
//        break;
//
////  Action 'checkAktivPassiv' wurde von Javascript Dokument aufgerufen
//    case 'checkAktivPassiv':
////      Ruft die Funktion zur Ueberprüfung der Nutzung von Aktiv und Passiv im Editor und speichert den Rueckgabewert in $new_string
//        $new_string = checkPassivAktiv($_POST['text']);
//
////      Schreibt den String zurueck zum Javascript Dokument, um den alten Inhalt des Editors
//        echo $new_string;
//        break;
////  Action 'checkWiederholungIE' wurde von Javascript Dokument aufgerufen
//    case 'checkWiederholungIE':
////      Ruft die Funktion zur Ueberpruefung der Nutzung von Aktiv und Passiv im Editor und speichert den Rueckgabewert in $new_string
//        $new_string = checkWiederholung($_POST['text']);
////      Schreibt den String zurueck zum Javascript Dokument, um den alten Inhalt des Editors
////      utf8_encode wird benoetigt um Sonderzeichen in IE darzustellen
//        echo utf8_encode($new_string);
//        break;
////  Action 'checkWiederholungIE11' wurde von Javascript Dokument aufgerufen
//    case 'checkWiederholungIE11':
////      Ruft die Funktion zur Ueberpruefung der Nutzung von Aktiv und Passiv im Editor und speichert den Rueckgabewert in $new_string
//        $new_string = checkWiederholung($_POST['text']);
////      Schreibt den String zurueck zum Javascript Dokument, um den alten Inhalt des Editors
//        echo $new_string;
//        break;
////  Action 'checkWiederholung' wurde von Javascript Dokument aufgerufen
//    case 'checkWiederholung':
//
////      Ruft die Funktion zur Ueberprüfung der Nutzung von Aktiv und Passiv im Editor und speichert den Rueckgabewert in $new_string
//        $new_string = checkWiederholung($_POST['text']);
////      Schreibt den String zurueck zum Javascript Dokument, um den alten Inhalt des Editors
//        echo $new_string;
//        break;
//
////  Wenn eine falsche Funktion gewaehlt wurde, passiert nichts
//    default:
//        break;
//}
//
///**Sucht im Inhalt des Texteditors anhand eines Regex-Ausdrucks nach Nominalstilforumlierungen
// *
// * @global function $link - Datenbankverbindung
// * @param string $inhalt - Zu pruefender Text
// * @return string - Uebergebener Text mit Markierungen
// */
//function checkNominalstil($inhalt){
//
//    global $link;
//
//    $new_string = $inhalt;
////  Bestimmt, ob eine Nominalformulierung auf der Whitelist steht
//    $contained = false;
////  Span zum markieren der Forumlierungen
//    $span_zum_markieren = "<span class='nominalstil'>";
////  Selektiert alle Wörter aus der Whitelist
//    $sql_regex_begriffe = query("SELECT name FROM editor_nominalstil", $link);
////  Bestimmt die Endungen eines Nominalausdrucks
//    $endungen = array(
//        'keit',
//        'heit',
//        'ung',
//        'tum',
//        'KEIT',
//        'HEIT',
//        'TUM',
//        'UNG',
//        'keiten',
//        'ungen',
//        'heiten',
//        'KEITEN',
//        'UNGEN',
//        'HEITEN'
//    );
//
////  Sucht im Editor nach Strings die sich aus [beliebiges Zeichen(kein Buchstabe)]+beliebige_Folge_von_Buchstaben+Endung+[beliebiges Zeichen(kein Buchstabe)] zusammensetzen lassen
//    $regex_zu_suchen = '/[^0-9a-zA-Z\x80-\xFF]([0-9a-zA-Z\x80-\xFF](<b>)*(<\/b>)*(<i>)*(<\/i>)*(<u>)*(<\/u>)*)+(ung|ungen|heit|heiten|keit|keiten|tum|UNG|UNGEN|HEIT|HEITEN|KEIT|KEITEN|TUM)[^0-9a-zA-Z\x80-\xFF]/';
////  Sucht den regulaeren Ausdruck im Editor und speichert die Treffer in $treffer
//    preg_match_all($regex_zu_suchen, $new_string, $treffer);
////  Sucht den gleich regulären Ausdruck wie oben, aber ohne Sonderzeichen vor bzw hinter dem Wort (Wird nur gebraucht, falls am Anfang oder am Ende eine Nominalstilformulierung ist
//    preg_match_all('/\b[0-9a-zA-Z\x80-\xFF]+(ung|heit|keit|tum|UNG|HEIT|KEIT|TUM|keiten|ungen|heiten|KEITEN|UNGEN|HEITEN)\b/', $new_string, $index);
//
//
////  Prueft, ob ein Ausdruck gefunden wurde
//    if($treffer[0] !== ''){
////      Iteriert durch die Treffer und markiert diese jeweils
//        for($i = 0; $i < count($treffer[0]); $i++){
////          Iteriert druch die WhitelistWörter
//            foreach($sql_regex_begriffe as $wort){
////              Prueft, ob das Wort im Editor ungleich den Wörter aus der Whitelist ist
//                if($wort['name'] === ucfirst($treffer[0][$i][1]).substr($treffer[0][$i],2, -1) || $wort['name'] === lcfirst($treffer[0][$i][1]).substr($treffer[0][$i],2, -1)){
////                  Setzt die Variable zur Gleichheit auf true
//                    $contained = true;
//                }
//            }
////          Wenn das Wort nicht in der Whitelist ist, wird es markiert
//            if($contained === false){
//                   $new_string = str_replace($treffer[0][$i], $treffer[0][$i][0].$span_zum_markieren.substr($treffer[0][$i], 1, -1)."</span>".$treffer[0][$i][strlen($treffer[0][$i]) - 1], $new_string);
//            }
////          Variable wird fuer den naechsten Treffer wieder auf false gesetzt
//            $contained = false;
//        }
//    }
////  Iteriert durch die Treffer aus der 2. Suche
//    foreach($index[0] as $wort){
////      Iteriert durch die Whitelist
//        foreach($sql_regex_begriffe as $sql_nominal){
////          Preuft, ob der Treffer in der Whitelist steht
//            if(in_array($wort, $endungen) || ucfirst($sql_nominal['name']) === $wort || lcfirst($sql_nominal['name']) === $wort){
//                $contained = true;
//            }
//        }
////      Wenn der Treffer nicht auf der Whitelist steht wird seine Position im Array gesucht
//        if($contained === false){
////          Wenn der Treffer am Anfang steht, wird er markiert
//            if(strpos($new_string, $wort) === 0){
//                $new_string = $span_zum_markieren.$wort.'</span>'.substr($new_string, strlen($wort));
//            }
////          Wenn der Treffer am Ende steht wird er markiert
//            if(strrpos($new_string, $wort) === (strlen($new_string) - strlen($wort))){
//                $new_string = substr($new_string,0, -(strlen($wort))).$span_zum_markieren.$wort.'</span>';
//            }
//        }
////      Setzt die Variable fuer den naechsten Treffer wieder auf false
//        $contained = false;
//
//    }
////  Gibt den neuen Inhalt mit Markierungen zurueck
//    return $new_string;
//}
//
//
///**Prueft, ob im Editor Floskeln/Anglizismen enthalten sind
// *
// * @global function $link - Datenbankverbindung
// * @param string $inhalt - Zu pruefender Text
// * @param string $action - Bestimmt nach welchem Kriterium gesucht werden soll ('floskel', 'anglizismus')
// * @return string - Uebergebener Text mit eingefuegten Markierungen
// */
//
//function checkFlosTextAngl($inhalt, $action){
//    header('Content-Type: text/html; charset=utf-8');
//    global $link;
//    $link->set_charset("latin1");
//
//    $new_string = $inhalt;
////  String, der am Ende mit eingefügten Markierungen, zurückgegeben wird
//    //$new_string = $inhalt;
////  Entfernt alle Tags, um reinen Text zum Selektieren aus der Datenbank zu bekommen
//    $inhalt_ohne_tags = rip_tags($inhalt);
//
////  Zaehlt die Anzahl der eingefuegten Markierungen
//    $laenge_markierungen = 0;
////  Array, das alle vorkommenden Woerter des Editors speichert
//    $woerter = array();
////  Speichert den Text fuer das Popover
//    $tooltip_title = '';
//    $test_string = '';
////  Teilt den Inhalt in einzelne Wörter auf und speichert sie in das Array 'woerter'
//    $woerter = preg_split('/[^0-9a-zA-Z\x80-\xFF]/', $inhalt_ohne_tags);
//
////  Loescht alle Doppelungen im Array
//    $woerter = array_unique($woerter);
////  loescht 'leere Zeichen'aus dem Array
//    $woerter = array_filter($woerter, create_function('$a','return preg_match("#\S#", $a);'));
////  Fuegt die einzelnen Woerter wieder zu einem String zusammen und setzt ein '|' zwischen die einzelnen Woerter
////  $reg_inhalt = implode('|', $woerter);
//    $reg_inhalt = "";
//    foreach($woerter as $wort)
//        $reg_inhalt .= mysqli_real_escape_string($link, utf8_decode($wort)).($wort != end($woerter) ? "','" : "");
//
//    if($action === 'floskel') //  Sucht Floskeln
//        $sql_begriffe = query("SELECT DISTINCT `name` FROM `editor_floskeln` WHERE `name` IN ('$reg_inhalt');", $link);
//
//    else if($action === 'anglizismus') //  Sucht Anglizismen
//        $sql_begriffe = query("SELECT DISTINCT name FROM editor_anglizismen WHERE `name` IN ('$reg_inhalt');", $link);
//
//    else if($action === 'negativewoerter') //  Sucht Negative Woerter
//        $sql_begriffe = query("SELECT DISTINCT name FROM editor_negativewoerter WHERE `name` IN ('$reg_inhalt');", $link);
//
///*
////  Selektiert alle Woerter aus der Datenbank die mit dem jeweils gegebenen Wort anfangen
////  Sucht Floskeln
//    if($action === 'floskel'){
//        $sql_begriffe = query("SELECT DISTINCT name FROM editor_floskeln WHERE name REGEXP '^(".mysqli_real_escape_string($link, utf8_decode($reg_inhalt)).")'", $link);
//    }
////  Sucht Anglizismen
//    if($action === 'anglizismus'){
//        $sql_begriffe = query("SELECT DISTINCT name FROM editor_anglizismen WHERE name REGEXP '^(".mysqli_real_escape_string($link, $reg_inhalt).")'", $link);
//    }
////  Sucht Negative Woerter
//    if($action === 'negativewoerter'){
//        $sql_begriffe = query("SELECT DISTINCT name FROM editor_negativewoerter WHERE name REGEXP '^(".mysqli_real_escape_string($link, $reg_inhalt).")'", $link);
//    }
//*/
//    foreach($sql_begriffe as $key=>$value)
//        $sql_begriffe[$key]['name'] = utf8_encode($value['name']);
//
////  Prueft, ob Woerter aus der Datenbank selektiert wurden
//    if(count($sql_begriffe) != 0){
////      Iteriert durch die gefundenen Woerter
//        for($j = 0; $j < count($sql_begriffe); $j++){
//
////          Prueft, ob sich das Wort an irgendeiner Stelle im Editor befindet
//            if(mb_stripos($new_string, $sql_begriffe[$j]['name']) !== false){
//
//
////              Sucht alle Woerter im Editor die sich folgendermassen zusammensetzen lassen: [Anfangsbuchstabe_klein_oder_gross]+[Wort]
//                preg_match_all('/(?<!class=")(?<!id=")\b'.$sql_begriffe[$j]['name'].'\b/i', $new_string, $index, PREG_OFFSET_CAPTURE);
//
////              Iteriert durch die Treffer der Floskeln/Anglizismen/Textverstaendniswoerter im Editor
//                foreach($index[0] as $index_treffer){
//
////                  Prueft durch den Index des Treffers, ob sich der Treffer am Anfang oder am Ende vom Editor befindet
//                    if($index_treffer[1] != 0 && $index_treffer[1] != (strlen($new_string) - 1 - strlen($index_treffer[0]))){
////                      Selektiert die Ersatzwoerter aus der Datenbank
////                      Fuer Floskeln
//                        if($action === 'floskel'){
//                            $sql_ersatz = query("SELECT ersatz FROM editor_floskeln WHERE name = '".mysqli_real_escape_string($link, $sql_begriffe[$j]['name'])."'", $link);
//                        }
////                      Fuer Anglizismen
//                        if($action === 'anglizismus'){
//                            $sql_ersatz = query("SELECT ersatz FROM editor_anglizismen WHERE name = '".mysqli_real_escape_string($link, $sql_begriffe[$j]['name'])."'", $link);
//                        }
////                      Fuer Negative Woerter
//                        if($action === 'negativewoerter'){
//                            $sql_ersatz = query("SELECT ersatz FROM editor_negativewoerter WHERE name = '".mysqli_real_escape_string($link, $sql_begriffe[$j]['name'])."'", $link);
//                        }
////                      Setzt den Tooltip zusammen, um ihn beim Popover richtig anzuzeigen
//                        foreach($sql_ersatz as $wort_ersatz){
//                            $tooltip_title = $tooltip_title.'<p>'.$wort_ersatz['ersatz'].'</p>';
//                        }
//                        $tooltip_title = str_replace(',', '</p><p>', $tooltip_title);
//
////                      Prueft, ob der Tooltip fuer das Popover Ersatzweorter enthaelt
//                        if(strlen($tooltip_title) !== 0 && strlen($tooltip_title) !== 7 && strlen($tooltip_title) !== 14){
////                          Span, der bestimmt wie die Markierung aussieht
//                            $span_zum_markieren = "<span title='Probieren Sie doch mal einen von diesen Begriffen' class='has_ersatz'>";
//                        }
////                      Prueft, ob der Tooltip fuer das Popover leer ist
//                        if(strlen($tooltip_title) === 0 || strlen($tooltip_title) === 7 || strlen($tooltip_title) === 14){
////                          Markierung fuer FLoskeln
//                            if($action == 'floskel')
//                                $span_zum_markieren = "<span class='floskeln'>";
////                          Markierung fuer Anglizismen
//                            if($action == 'anglizismus')
//                                $span_zum_markieren = "<span class='anglizismen'>";
////                          Markierung fuer Negative Woerter
//                            if($action == 'negativewoerter')
//                                $span_zum_markieren = "<span class='negative_woerter'>";
//                        }
////                      Fuegt den Index des gefundenen String, an die oben bestimmte Markierung
//                        $new_string = substr($new_string, 0, $index_treffer[1] + $laenge_markierungen).
//                                      $span_zum_markieren.$index_treffer[0].
//                                      '</span>'.
//                                      substr($new_string, $index_treffer[1] + $laenge_markierungen + strlen($index_treffer[0]));
//
////                      Addiert die Laenge einer Markierung zum Zaehler der Markierungen
//
//                        $laenge_markierungen = $laenge_markierungen + strlen($span_zum_markieren) + strlen('</span>');
//                    }
////                  Leert den Tooltip, damit bei der naechsten Markierung neue Werte eingefuegt werden
//                    $tooltip_title = '';
////                  Prueft durch den Index des Treffers, ob der Treffer am Anfang des Editors steht
//                    if($index_treffer[1] === 0){
//
////                      Selektiert die Ersatzwoerter aus der Datenbank
////                      Fuer Floskeln
//                        if($action === 'floskel'){
//                            $sql_ersatz = query("SELECT ersatz FROM editor_floskeln WHERE name = '".mysqli_real_escape_string($link, $sql_begriffe[$j]['name'])."'", $link);
//                        }
////                      Fuer Anglizismen
//                        if($action === 'anglizismus'){
//                            $sql_ersatz = query("SELECT ersatz FROM editor_anglizismen WHERE name = '".mysqli_real_escape_string($link, $sql_begriffe[$j]['name'])."'", $link);
//                        }
////                      Fuer Negative Woerter
//                        if($action === 'negativewoerter'){
//                            $sql_ersatz = query("SELECT ersatz FROM editor_negativewoerter WHERE name = '".mysqli_real_escape_string($link, $sql_begriffe[$j]['name'])."'", $link);
//                        }
////                      Setzt den Tooltip zusammen, um ihn beim Popover richtig anzuzeigen
//                        foreach($sql_ersatz as $wort_ersatz){
//                            $tooltip_title = $tooltip_title.'<p>'.$wort_ersatz['ersatz'].'</p>';
//                        }
//                        $tooltip_title = str_replace(',', '</p><p>', $tooltip_title);
////                      Wenn der Tooltip Werte enthaelt, wird eine Markierung benutzt die den Tooltip anzeigen kann
//                        if(strlen($tooltip_title) !== 0 && strlen($tooltip_title) !== 7 && strlen($tooltip_title) !== 14){
////                          Span, der bestimmt wie die Markierung aussieht
//                            $span_zum_markieren = "<span title='Probieren Sie doch mal einen von diesen Begriffen' class='has_ersatz'>";
//                        }
////                      Wenn der Tooltip leer ist, wird eine Markierung benutzt die keine Tooltips anzeigt
//                        if(strlen($tooltip_title) === 0 || strlen($tooltip_title) === 7 || strlen($tooltip_title) === 14){
////                              Markierung fuer FLoskeln
//                            if($action == 'floskel')
//                                $span_zum_markieren = "<span class='floskel'>";
////                              Markierung fuer Anglizismen
//                            if($action == 'anglizismus')
//                                $span_zum_markieren = "<span class='anglizismen'>";
////                              Markierung fuer Negative Woerter
//                            if($action == 'negativewoerter')
//                                $span_zum_markieren = "<span class='negative_woerter'>";
//                        }
////                      Markiert das erste Wort des Editors
//                        $new_string = $span_zum_markieren.$index_treffer[0].'</span>'.substr($new_string, strlen($index_treffer[0]));
////                      Addiert die Laenge einer Markierung zum Zaehler der Markierungen
//                        $laenge_markierungen = $laenge_markierungen + strlen($span_zum_markieren) + strlen('</span>');
//                    }
////                  Leert den Tooltip, damit bei der naechsten Markierung neue Werte eingefuegt werden
//                    $tooltip_title = '';
////                  Prueft, ob der Treffer am Ende des Editors steht
//
//                    if($index_treffer[1] === (strlen($new_string) - 1 - strlen($index_treffer[0]))){
////                      Selektiert die Ersatzwoerter aus der Datenbank
////                      Fuer Floskeln
//                        if($action === 'floskel'){
//                            $sql_ersatz = query("SELECT ersatz FROM editor_floskeln WHERE name = '".mysqli_real_escape_string($link, $sql_begriffe[$j]['name'])."'", $link);
//                        }
////                      Fuer Anglizismen
//                        if($action === 'anglizismus'){
//                            $sql_ersatz = query("SELECT ersatz FROM editor_anglizismen WHERE name = '".mysqli_real_escape_string($link, $sql_begriffe[$j]['name'])."'", $link);
//                        }
////                      Fuer Anglizismen
//                        if($action === 'negativewoerter'){
//                            $sql_ersatz = query("SELECT ersatz FROM editor_negativewoerter WHERE name = '".mysqli_real_escape_string($link, $sql_begriffe[$j]['name'])."'", $link);
//                        }
////                      Setzt den Tooltip zusammen, um ihn beim Popover richtig anzuzeigen
//                        foreach($sql_ersatz as $wort_ersatz){
//                            $tooltip_title = $tooltip_title.'<p>'.$wort_ersatz['ersatz'].'</p>';
//                        }
//                        $tooltip_title = str_replace(',', '</p><p>', $tooltip_title);
////                      Wenn der Tooltip Werte enthaelt, wird eine Markierung benutzt die den Tooltip anzeigen kann
//                        if(strlen($tooltip_title) !== 0 && strlen($tooltip_title) !== 7 && strlen($tooltip_title) !== 14){
////                              Span, der bestimmt wie die Markierung aussieht
//                            $span_zum_markieren = "<span title='Probieren Sie doch mal einen von diesen Begriffen' class='has_ersatz'>";
//                        }
////                      Wenn der Tooltip leer ist, wird eine Markierung benutzt die keine Tooltips anzeigt
//                        if(strlen($tooltip_title) === 0 || strlen($tooltip_title) === 7 || strlen($tooltip_title) === 14){
////                              Markierung fuer FLoskeln
//                            if($action == 'floskel')
//                                $span_zum_markieren = "<span class='floskel'>";
////                              Markierung fuer Anglizismen
//                            if($action == 'anglizismus')
//                                $span_zum_markieren = "<span class='anglizismen'>";
////                              Markierung fuer Negative Woerter
//                            if($action == 'negativewoerter')
//                                $span_zum_markieren = "<span class='negative_woerter'>";
//                        }
//
////                      Markiert das letzte Wort des Editors
//                        $new_string = substr($new_string,0, -(strlen($index_treffer[0]))).$span_zum_markieren.$index_treffer[0].'</span>';
////                      Addiert die Laenge einer Markierung zum Zaehler der Markierungen
//                        $laenge_markierungen = $laenge_markierungen + strlen($span_zum_markieren) + strlen('</span>');
//                    }
//                }
//            }
//
////          Setzt Tooltip_title fuer die naechste Iteration auf 0
//            $tooltip_title = '';
////          Setzt die Anzahl der eingefuegten Markierungen fuer die naechste iteration auf 0
//            $laenge_markierungen = 0;
//        }
//    }
//    //  Gibt den neuen Inhalt mit Markierungen zurueck
//    return $new_string;
//}
//
///**Prueft, ob im Editor negative Wörter enthalten sind
// *
// * @global function $link - Datenbankverbindung
// * @param string $inhalt - Zu pruefender Text
// * @return string - Uebergebener Text mit eingefuegten Markierungen
// */
//function checkNegativeWoerter($inhalt, $action){
//    global $link;
//
//    $new_string = $inhalt;
////  String, der am Ende mit eingefügten Markierungen, zurückgegeben wird
//    //$new_string = $inhalt;
////  Entfernt alle Tags, um reinen Text zum Selektieren aus der Datenbank zu bekommen
//    $inhalt_ohne_tags = rip_tags($inhalt);
//
////  Zaehlt die Anzahl der eingefuegten Markierungen
//    $laenge_markierungen = 0;
////  Array, das alle vorkommenden Woerter des Editors speichert
//    $woerter = array();
////  Speichert den Text fuer das Popover
//    $tooltip_title = '';
//    $test_string = '';
////  Teilt den Inhalt in einzelne Wörter auf und speichert sie in das Array 'woerter'
//    $woerter = preg_split('/[^0-9a-zA-Z\x80-\xFF]/', $inhalt_ohne_tags);
//
////  Loescht alle Doppelungen im Array
//    $woerter = array_unique($woerter);
////  loescht 'leere Zeichen'aus dem Array
//    $woerter = array_filter($woerter, create_function('$a','return preg_match("#\S#", $a);'));
////  Fuegt die einzelnen Woerter wieder zu einem String zusammen und setzt ein '|' zwischen die einzelnen Woerter
//    $reg_inhalt = implode('|', $woerter);
//
//
////  Selektiert alle Woerter aus der Datenbank die mit dem jeweils gegebenen Wort anfangen
//    $sql_begriffe = query("SELECT DISTINCT name FROM editor_negativewoerter WHERE name REGEXP '^(".mysqli_real_escape_string($link, $reg_inhalt).")'", $link);
//
////  Prueft, ob Woerter aus der Datenbank selektiert wurden
//    if(count($sql_begriffe) != 0){
////      Iteriert durch die gefundenen Woerter
//        for($j = 0; $j < count($sql_begriffe); $j++){
//
////          Prueft, ob sich das Wort an irgendeiner Stelle im Editor befindet
//            if(mb_stripos($new_string, $sql_begriffe[$j]['name']) !== false){
////              Sucht alle Woerter im Editor die sich folgendermassen zusammensetzen lassen: [Anfangsbuchstabe_klein_oder_gross]+[Wort]
//                preg_match_all('/(?<!class=")(?<!id=")\b'.$sql_begriffe[$j]['name'].'\b/i', $new_string, $index, PREG_OFFSET_CAPTURE);
//
////              Iteriert durch die Treffer der Floskeln/Anglizismen/Textverstaendniswoerter im Editor
//                foreach($index[0] as $index_treffer){
////                  Prueft durch den Index des Treffers, ob sich der Treffer am Anfang oder am Ende vom Editor befindet
//                    if($index_treffer[1] != 0 && $index_treffer[1] != (strlen($new_string) - 1 - strlen($index_treffer[0]))){
////                      Selektiert die Ersatzwoerter aus der Datenbank
//                        $sql_ersatz = query("SELECT ersatz FROM editor_negativewoerter WHERE name = '".mysqli_real_escape_string($link, $sql_begriffe[$j]['name'])."'", $link);
//
////                      Setzt den Tooltip zusammen, um ihn beim Popover richtig anzuzeigen
//                        foreach($sql_ersatz as $wort_ersatz){
//                            $tooltip_title = $tooltip_title.'<p>'.$wort_ersatz['ersatz'].'</p>';
//                        }
//                        $tooltip_title = str_replace(',', '</p><p>', $tooltip_title);
//
////                      Prueft, ob der Tooltip fuer das Popover Ersatzweorter enthaelt
//                        if(strlen($tooltip_title) !== 0 && strlen($tooltip_title) !== 7 && strlen($tooltip_title) !== 14){
////                          Span, der bestimmt wie die Markierung aussieht
//                            $span_zum_markieren = "<span title='Probieren Sie doch mal einen von diesen Begriffen' class='has_ersatz'>";
//                        }
////                      Prueft, ob der Tooltip fuer das Popover leer ist
//                        if(strlen($tooltip_title) === 0 || strlen($tooltip_title) === 7 || strlen($tooltip_title) === 14){
//                            $span_zum_markieren = "<span class='negative-woerter'>";
//                        }
////                      Fuegt den Index des gefundenen String, an die oben bestimmte Markierung
//                        $new_string = substr($new_string, 0, $index_treffer[1] + $laenge_markierungen).
//                                      $span_zum_markieren.$index_treffer[0].
//                                      '</span>'.
//                                      substr($new_string, $index_treffer[1] + $laenge_markierungen + strlen($index_treffer[0]));
//
////                      Addiert die Laenge einer Markierung zum Zaehler der Markierungen
//                        $laenge_markierungen = $laenge_markierungen + strlen($span_zum_markieren) + strlen('</span>');
//                    }
////                  Leert den Tooltip, damit bei der naechsten Markierung neue Werte eingefuegt werden
//                    $tooltip_title = '';
//
////                  Prueft durch den Index des Treffers, ob der Treffer am Anfang des Editors steht
//                    if($index_treffer[1] === 0){
//
////                      Selektiert die Ersatzwoerter aus der Datenbank
//                        $sql_ersatz = query("SELECT ersatz FROM editor_negativewoerter WHERE name = '".mysqli_real_escape_string($link, $sql_begriffe[$j]['name'])."'", $link);
//
////                      Setzt den Tooltip zusammen, um ihn beim Popover richtig anzuzeigen
//                        foreach($sql_ersatz as $wort_ersatz){
//                            $tooltip_title = $tooltip_title.'<p>'.$wort_ersatz['ersatz'].'</p>';
//                        }
//                        $tooltip_title = str_replace(',', '</p><p>', $tooltip_title);
////                      Wenn der Tooltip Werte enthaelt, wird eine Markierung benutzt die den Tooltip anzeigen kann
//                        if(strlen($tooltip_title) !== 0 && strlen($tooltip_title) !== 7 && strlen($tooltip_title) !== 14){
////                          Span, der bestimmt wie die Markierung aussieht
//                            $span_zum_markieren = "<span title='Probieren Sie doch mal einen von diesen Begriffen' class='has_ersatz'>";
//                        }
////                      Wenn der Tooltip leer ist, wird eine Markierung benutzt die keine Tooltips anzeigt
//                        if(strlen($tooltip_title) === 0 || strlen($tooltip_title) === 7 || strlen($tooltip_title) === 14){
//                            $span_zum_markieren = "<span class='negative-woerter'>";
//                        }
////                      Markiert das erste Wort des Editors
//                        $new_string = $span_zum_markieren.$index_treffer[0].'</span>'.substr($new_string, strlen($index_treffer[0]));
//                        echo "\n\n".$new_string;
////                      Addiert die Laenge einer Markierung zum Zaehler der Markierungen
//                        $laenge_markierungen = $laenge_markierungen + strlen($span_zum_markieren) + strlen('</span>');
//                    }
//
////                  Leert den Tooltip, damit bei der naechsten Markierung neue Werte eingefuegt werden
//                    $tooltip_title = '';
////                  Prueft, ob der Treffer am Ende des Editors steht
//                    if($index_treffer[1] === (strlen($new_string) - 1 - strlen($index_treffer[0]))){
////                      Selektiert die Ersatzwoerter aus der Datenbank
//                        $sql_ersatz = query("SELECT ersatz FROM editor_floskeln WHERE name = '".mysqli_real_escape_string($link, $sql_begriffe[$j]['name'])."'", $link);
//
////                      Setzt den Tooltip zusammen, um ihn beim Popover richtig anzuzeigen
//                        foreach($sql_ersatz as $wort_ersatz){
//                            $tooltip_title = $tooltip_title.'<p>'.$wort_ersatz['ersatz'].'</p>';
//                        }
//                        $tooltip_title = str_replace(',', '</p><p>', $tooltip_title);
////                      Wenn der Tooltip Werte enthaelt, wird eine Markierung benutzt die den Tooltip anzeigen kann
//                        if(strlen($tooltip_title) !== 0 && strlen($tooltip_title) !== 7 && strlen($tooltip_title) !== 14){
////                          Span, der bestimmt wie die Markierung aussieht
//                            $span_zum_markieren = "<span title='Probieren Sie doch mal einen von diesen Begriffen' class='has_ersatz'>";
//                        }
////                      Wenn der Tooltip leer ist, wird eine Markierung benutzt die keine Tooltips anzeigt
//                        if(strlen($tooltip_title) === 0 || strlen($tooltip_title) === 7 || strlen($tooltip_title) === 14){
//                            $span_zum_markieren = "<span class='negative-woerter'>";
//                        }
//
////                      Markiert das letzte Wort des Editors
//                        $new_string = substr($new_string,0, -(strlen($index_treffer[0]))).$span_zum_markieren.$index_treffer[0].'</span>';
//
////                      Addiert die Laenge einer Markierung zum Zaehler der Markierungen
//                        $laenge_markierungen = $laenge_markierungen + strlen($span_zum_markieren) + strlen('</span>');
//                    }
//                }
//            }
//
////          Setzt Tooltip_title fuer die naechste Iteration auf 0
//            $tooltip_title = '';
////          Setzt die Anzahl der eingefuegten Markierungen fuer die naechste iteration auf 0
//            $laenge_markierungen = 0;
//        }
//    }
//    //  Gibt den neuen Inhalt mit Markierungen zurueck
//
//    return $new_string;
//
//}
//
///**Prueft, ob im Editor Saetze enthalten sind, die mehr als 20 Woerter haben
// *
// * @global function $link - Datenbankverbindung
// * @param string $inhalt - Zu pruefender Text
// * @return string - Uebergebener Text mit eingefuegten Markierungen
// */
//function checkSaetze($inhalt){
//    global $link;
//
////  Array, das die Moeglichkeiten einen Satz zu beenden speichert
//    $arrLineEnd     = array('! ','? ','. ', '!</p>', '?</p>', '.</p>', '</li>');
//
//    $unidelim       = $arrLineEnd[0];
//    $step_01        = str_replace($arrLineEnd, $unidelim, $inhalt);
//    $arrLines       = explode($unidelim, $step_01);
//
//    foreach($arrLines as $strLine)
//    {
//        if(strlen(rip_tags($strLine)) <= 250)
//            continue;
//
//        $strLine       = str_replace('<p>', '', $strLine);
//        $strLine       = str_replace('</p>', '', $strLine);
//
//        $inhalt = str_replace($strLine, "<span class='lange_saetze'>$strLine</span>", $inhalt);
//    }
//    return $inhalt;
//
///* OLD Version
//    $new_string = $inhalt;
////  Bestimmt die Markierung von langen Sätzen
//    $span_zum_markieren = "<span class='lange_saetze'>";
////  Selektiert die Abkuerzungen
//    $sql_abkuerzungen = query("SELECT DISTINCT name FROM editor_abkuerzungen", $link);
//
////  Speichert die Treffer des preg_match_all
//    $treffer = array();
////  Array, das die einzelnen Abkuerzungen speichert
//    $toCheck = array();
////  Iteriert durch die Treffer aus der Datenbank
//    foreach($sql_abkuerzungen as $wort) {
////      Prueft auf Doppelung
//        $wort['name'] = trim($wort['name']);
//        if (in_array($wort['name'], $toCheck)) {
//            continue;
//        }
//        $toCheck[] = mb_strtolower($wort['name']);
//    }
//
////  FALLS MAN ES MIT REGEX MACHT: (BEI ZU LANGEN SAETZEN ABSTURZ!!!)
////
////  Fügt die einzelnen Abkuerzungen zu einem String zusammen, um ihn im Regex zu nutzen
////    $tokenReg = implode(')|( ', $toCheck);
////    $tokenReg = '( '.$tokenReg.')';
////
////    $tokenReg = preg_replace('/\./', '\\\.', $tokenReg);
////    REGEX fuer lange Saetze, funktioniert, aber stuerzt bei Saetzen ab 308 Zeichen ab
////    $regex_zu_suchen = '/((((\b('.$tokenReg.')+\b([a-zöäü]{0,2}\.){0,1})|(Herr [A-ZÄÜÖ]\.)|(Frau [A-ZÄÜÖ]\.))|((<[biu]>)*(<\/[biu]>)*(<strong>)*(<\/strong>)*(&nbsp\;)*(\d+\.)*(<span>)*(<\/span>)*[^\?!\.;<> ])+)[ ]){19,}[^\?!\.;<> ]{1,}[\?!\.;]/i';
//
////  ENDE ERSTER TEIL REGEX
//
////  Teilt den Inhalt des Editors in einzelne Woerter auf, die im Folgenden zu einem Satz zusammengesetzt werden
//    $regex_zu_suchen = '/([^ ]+@[^ ]+[\.][^ ]+)|(www\.[^ ]+\.[^ ]+)|(([^\?!\.;<> ](&amp\;)?(<b>)?(<\/b>)?(<i>)?(<\/i>)?(<u>)?(<\/u>)?(<br>)?(<a>)?(<\/a>)?(<small>)?(<\/small>)?(<span(.*?)>)?(<\/span>)?(<strong>)?(<\/strong>)?'.
//                      '(<sub>)?(<\/sub>)?(<sup>)?(<\/sup>)?){1,})[ ]?([\.\?;!<]){0,1}/';
//
////  Sucht Woerter im Editor und speichert diese in $treffer
//    preg_match_all($regex_zu_suchen, $new_string, $treffer, PREG_OFFSET_CAPTURE);
////  Variable, die die Anzahl der Woerter fuer jeden Satz zaehlt
//    $word_count = 0;
////  Variable, die den index im Array speichert an dem das erste Wort jedes Satzes steht
//    $index_satzanfang = 0;
////  Variable, die die Laenge einer kompletten Markierung speichert
//    $laenge_markierung = strlen($span_zum_markieren.'</span>');
////  Variable, die die Anzahl der Markierungen bei jeder Iteration speichert
//    $anzahl_markierungen = 0;
//
////  Array, das die Moeglichkeiten einen Satz zu beenden speichert
//    $arrSatzende = array('!','?','.',';');
//
////  Iteriert durch die Treffer
//    for($i = 0; $i < count($treffer[0]); $i++) {
////      Prueft, ob das getroffene Wort eine Abkuerzung, eine Zahl, ein Link oder eine E-Mail Adresse ist
//        if(in_array(mb_strtolower($treffer[0][$i][0]), $toCheck) === false && !preg_match('/([\d]+[\.])+|\b[a-zA-Z]{1}[\.]{1}/', $treffer[0][$i][0])
//        && !preg_match('/www\.[0-9a-zA-Z\x80-\xFF]+\.[a-z]+/', $treffer[0][$i][0])
//        && !preg_match('/[^ ]+@[^ ]+[\.][^ ]+/', $treffer[0][$i][0])){
////          Wenn das getroffene Wort mit einem Leerzeichen endet oder ein einzelnes sonderzeichen ist wird der zaehler fuer Woerter um 1 hochgezaehlt
//            if($treffer[0][$i][0][strlen($treffer[0][$i][0]) - 1] === ' ' || $treffer[0][$i][0] === '&amp;'){
//                $word_count = $word_count + 1;
//            }
////          Prueft, ob das getroffene Wort mit einem Punkt, Strichpunkt, Ausrufezeichen oder Fragezeichen endet
//            else if(in_array($treffer[0][$i][0][strlen($treffer[0][$i][0]) - 1], $arrSatzende) === true){
////              Prueft, ob mehr als 18 Woerter gezahelt wurden (da das Wort an dem die iteration steht noch nicht gezahelt wurde)
//                if($word_count > 18){
//
////                  Fuegt die Markierung an den Indizies des ersten und letzten Wortes des Satzes ein
//                    $new_string = substr($new_string, 0, $treffer[0][$index_satzanfang][1]+($anzahl_markierungen*$laenge_markierung)).
//                            $span_zum_markieren.
//                            substr($new_string, $treffer[0][$index_satzanfang][1]+($anzahl_markierungen*$laenge_markierung), -(strlen($new_string)-($treffer[0][$i][1]+($anzahl_markierungen*$laenge_markierung)+strlen($treffer[0][$i][0])))).
//                            '</span>'.
//                            substr($new_string, $treffer[0][$i][1]+($anzahl_markierungen*$laenge_markierung)+strlen($treffer[0][$i][0]));
////                  Zaehlt die Anzahl der Markierungen um 1 hoch
//                    $anzahl_markierungen = $anzahl_markierungen + 1;
//                }
////              Setzt den Zaehler fuer Woerter wieder auf 0, da ein neuer Satz anfaengt
//                $word_count = 0;
////              Setzt den Index des neuen Satzes auf die naechste Iteration
//                $index_satzanfang = $i+1;
//            }
////          Prueft, ob das getroffene Wort von einem unerlaubtem Tag gefolgt wird
//            else{
////              Setzt den Zaehler fuer Woerter wieder auf 0
//                $word_count = 0;
////              Setzt den Index des neuen Satzes auf die naechste Iteration
//                $index_satzanfang = $i+1;
//            }
//        }
////      Wenn das getroffene Wort eine Abkuerzung oder ein Sonderzeichen ist, wird der Zeahler fuer Woerter um 1 erhoeht
//        else {
//            $word_count = $word_count + 1;
//        }
//    }
//
////  Gibt den neuen String mit Markierungen zurueck
//    return $new_string;
////  MARKIERUNG FALLS MAN DEN TEIL MIT ABSTURZ NIMMT
//////  Loescht alle Doppelungen in $treffer, falls es gleiche Saetze im Editor gibt
////    $treffer[0] = array_unique($treffer[0]);
//////  Prueft, ob Saetze gefunden werden
////    if($treffer[0] != ''){
//////      Iteriert durch die Treffer und markiert diese
////        foreach($treffer[0] as $treffer){
////            $new_string = str_replace($treffer, $span_zum_markieren.$treffer."</span>", $new_string);
////        }
////    }
////  ENDE DES TEILS
//
//    */
//
//}
//
///**Prueft, ob im Editor zu lange Woerter enthalten sind
// *
// * @param string $inhalt - Zu pruefender Text
// * @return string - Uebergebener Text mit eingefuegten Markierungen
// */
//function checkWortlaenge($inhalt){
//
//    $new_string = $inhalt;
//    $treffer = array();
////  Bestimmt die Markierung von langen Sätzen
//    $span_zum_markieren = "<span class='wortlaenge'>";
//
////  Prueft ob im Editor ein Wort nach dem Regex aufgebaut ist
//    $regex_zu_suchen = '/\b(([0-9a-zA-Z\x80-\xFF](<b>)*(<\/b>)*(<i>)*(<\/i>)*(<u>)*(<\/u>)*){15,})\b/i';
////  Sucht Woerter im Editor und speichert diese in $treffer
//    preg_match_all($regex_zu_suchen, $new_string, $treffer);
//    $treffer[0] = array_unique($treffer[0]);
//
//    //  Prueft, ob Woerter gefunden werden
//    if($treffer[0] !== ''){
////      Iteriert durch die Treffer und markiert diese
//        foreach($treffer[0] as $treffer){
//            $new_string = preg_replace('/\b'.$treffer.'\b/', $span_zum_markieren.$treffer."</span>", $new_string);
//        }
//    }
////  Gibt den neuen String mit Markierungen zurueck
//    return $new_string;
//}
//
///**Prueft, ob im Editor Aktiv-Passiv Formulierungen vorkommen
// *
// * @global function $link - Datenbankverbindung
// * @param string $inhalt - Zu pruefender Text
// * @return string - Uebergebener Text mit eingefuegten Markierungen
// */
//function checkPassivAktiv($inhalt){
//    global $link;
//    $link->set_charset("latin1");
//
//    $new_string = $inhalt;
////  Bestimmt die Markierung von langen Sätzen
//    $span_zum_markieren = "<span class='aktiv_passiv'>";
////  Selektiert die Abkuerzungen
//    $sql_abkuerzungen = query("SELECT DISTINCT name FROM editor_abkuerzungen", $link);
//    //  Speichert die Treffer des preg_match_all
//    $treffer = array();
////  Array, das moegliche Hilfsverben fuer Passivformulierungen speichert
//    $hilfsverben = array('werde', 'wirst', 'wird', 'werden', 'werdet', 'wurde', 'wurdest', 'wurden', 'wurdet', 'bin', 'bist', 'ist', 'sind', 'seid', 'war', 'warst', 'waren', 'wart');
////  Setzt aus dem Array aus Hilfsverben einen String fuer den Regex Ausdruck zusammen
//    $hilfsverben_string = implode(')|(', $hilfsverben);
//    $hilfsverben_string = '('.$hilfsverben_string.')';
//
////  Array, das die einzelnen Abkuerzungen speichert
//    $toCheck = array();
////  Iteriert durch die Treffer aus der Datenbank
//    foreach($sql_abkuerzungen as $wort) {
////      Prueft auf Doppelung
//        $wort['name'] = trim($wort['name']);
//        if (in_array($wort['name'], $toCheck)) {
//            continue;
//        }
//        $toCheck[] = $wort['name'];
//    }
//    //  Teilt den Inhalt des Editors in einzelne Woerter auf, die im Folgenden zu einem Satz zusammengesetzt werden
//    $regex_zu_suchen = '/(www\.[^ ]+\.[^ ]+)|(([^\?!\.;<> ](<b>)?(<\/b>)?(<i>)?(<\/i>)?(<u>)?(<\/u>)?(<br>)?(<a>)?(<\/a>)?(<small>)?(<\/small>)?(<span>)?(<\/span>)?(<strong>)?(<\/strong>)?'.
//                      '(<sub>)?(<\/sub>)?(<sup>)?(<\/sup>)?){1,})[ ]?([\.\?;!<]){0,1}/';
//
////  Sucht Woerter im Editor und speichert diese in $treffer
//    preg_match_all($regex_zu_suchen, $new_string, $treffer, PREG_OFFSET_CAPTURE);
////  Variable, die den index im Array speichert an dem das erste Wort jedes Satzes steht
//    $index_satzanfang        = 0;
////  Variable, die die Anzahl der Markierungen bei jeder Iteration speichert
//    $anzahl_markierungen     = 0;
////  Array, das die Moeglichkeiten einen Satz zu beenden speichert
//    $arrSatzende             = array('!','?','.');
////  String, der jeden Satz einzeln zum pruefen speichert
//    $pruef_string            = '';
////  Laenge einer kompletten Markierung
//    $markierungs_laenge      = strlen($span_zum_markieren.'</span>');
////  String, der genutzt wird um Markierungen einzufügen um den alten String durch diesen auszutauschen
//    $string_mit_markierungen = '';
////  Zaehlt die Anzahl der markierten Passivformulierungen
//    $anzahl_passiv           = 0;
////  Variable, die bestimmt, ob ein Hilfsverb markiert wurde, damit es nicht mehrmals markiert wird
//    $hilfsverben_eingefuegt  = false;
////  Speichert alle Indizes und die dazugehoerigen Treffer fuer jeden Satz
//    $indizes_verben          = array();
//
//    $hilfsverb_vorhanden = false;
//    $passivverb_vorhanden = false;
////  Iteriert durch die Treffer
//    for($i = 0; $i < count($treffer[0]); $i++) {
//
////      Falls ein Wort nicht mit einem Leerzeichen endet oder ein nicht erlaubter Tag folgt, wird es nicht als Satz registriert und die Zaehlung fuer Saetze
////      Beginnt bei der naechsten Iteration von vorne
//        if($treffer[0][$i][0][strlen($treffer[0][$i][0]) - 1] !== ' ' && !(in_array($treffer[0][$i][0][strlen($treffer[0][$i][0]) - 1], $arrSatzende))){
//            $index_satzanfang = $i + 1;
//        }
////      Prueft, ob das getroffene Wort eine Abkuerzung ist oder ob es eine Zahl ist
//        if(!in_array($treffer[0][$i][0], $toCheck) && !preg_match('/([\d]+[\.])+/', $treffer[0][$i][0])){
//
////          Prueft, ob das getroffene Wort mit einem Punkt, Strichpunkt, Ausrufezeichen oder Fragezeichen endet
//            if(in_array($treffer[0][$i][0][strlen($treffer[0][$i][0]) - 1], $arrSatzende) === true){
////              Prueft, ob der Editor nur einen Satz hat, falls ja, wird dieser zum PruefString
//                if((strlen($new_string)-($treffer[0][$i][1]+($anzahl_markierungen*$markierungs_laenge)+strlen($treffer[0][$i][0]))) === 0){
//                    $pruef_string = substr($new_string, 0);
//                }
////              Wenn der Editor mehrere Saetze enthaelt, wird der Satz durch die Indizes des ersten Wortes und des letzten Wortes aus dem Inhalt des Editors ausgeschnitten
//                else{
//                    $pruef_string = substr($new_string, $treffer[0][$index_satzanfang][1]+($anzahl_markierungen*$markierungs_laenge), -(strlen($new_string)-($treffer[0][$i][1]+($anzahl_markierungen*$markierungs_laenge)+strlen($treffer[0][$i][0]))));
//                }
//
//                //echo "$pruef_string\n\n\n-";
//
//                //die($pruef_string);
//
////              Der Satz wird einer anderen Variable zugewiesen, damit Markierungen eingefuegt werden koennen und dieser Satz dann jeden im Editor ersetzen kann
//                $string_mit_markierungen = $pruef_string;
////              Sucht im Satz nach kompletten Passivfodrmulierungen, wie zb geschlossen, geoeffnet, entlassen werden, entlassen worden sein und speichert den Index an dem sie gefunden werden
//                preg_match_all('/(\b[0-9a-z\x80-\xFF]+((en)|t){1}( worden| werden){0,1}( sein){0,1}\b)/u', $string_mit_markierungen, $treffer_ende_en_oder_t, PREG_OFFSET_CAPTURE);
//
////              Sucht im Satz nach Hilfsverben und speichert den Index an dem sie gefunden werden
//                preg_match_all('/\b('.$hilfsverben_string.')\b/u', $string_mit_markierungen, $treffer_hilfsverb, PREG_OFFSET_CAPTURE);
//
////              Prueft, ob Hilfsverben UND Passivformulierungen gefunden wurden
//                if(count($treffer_ende_en_oder_t[0]) !== 0 && count($treffer_hilfsverb[0]) !== 0){
////                  Iteriert durch die Passivformulierungen
//                    foreach($treffer_ende_en_oder_t[0] as $treffer_endung){
////                      Trennt den Treffer nach Leerzeichen auf, damit der erste Teil mit der Datenbank abgeglichen werden kann
//                        $treffer_ohne_zusatzverben = explode(' ', $treffer_endung[0]);
////                      Selektiert alle Woerter aus der Datenbank, die gleich dem ersten Teil des Treffers sind
//                        $passiv_aus_db = query("SELECT name FROM passivverben WHERE name = '".mysqli_real_escape_string($link, $treffer_ohne_zusatzverben[0])."'", $link);
//
//
//
//                        //print_r("SELECT name FROM passivverben WHERE name = '".mysqli_real_escape_string($link, $treffer_ohne_zusatzverben[0])."'\n");
//                        //continue;
//
////                      Prueft, ob das Wort in der Datenbank ist und ob das gefundene Wort ein Hilfsverb ist
//                        if(count($passiv_aus_db) !== 0 && !(in_array($treffer_endung[0], $hilfsverben))){
////                          Prueft, ob Hilfsverben in diesem Satz schon markiert worden sind
//                            if($hilfsverben_eingefuegt === false){
////                              Iteriert durch die Hilfsverben im Satz
//                                foreach($treffer_hilfsverb[0] as $h_verb){
////                                  Fuegt das gefundene Hilfsverb mit dazugehoerigem Schluessel in die Variable ein
//                                    $indizes_verben[] = array($h_verb[1], $h_verb[0]);
//                                    $hilfsverb_vorhanden = true;
//                                }
////                              Setzt die Variable auf true, damit die Hilfsverben nicht nochmal markiert werden
//                                $hilfsverben_eingefuegt = true;
//
//                            }
////                          Fuegt die gefundene Passivformulierung mit dazugehoerigem Schluessel in die Variable ein
//                            $indizes_verben[] = array($treffer_endung[1], $treffer_endung[0]);
//                            $passivverb_vorhanden = true;
//                        }
////                      Prueft, ob eine Passivformulierung im Perfekt, Plusquamperfekt, Futur I oder Futur II gefunden wurde  (4 Spezialfaelle)
//                        if(count($treffer_ohne_zusatzverben) > 1 && count($passiv_aus_db) !== 0){
////                          Iteriert durch das Array, das Verben enthaelt, die markiert werden sollen
//                            for($k = 0; $k < count($indizes_verben); $k++){
////                              Wenn ein Hilfverb im Array ist, das Teil eines der 4 Spezialfaelle ist, wird es geloescht, damit es nicht 2 mal markiert wird
//                                if(in_array($indizes_verben[$k][1], $treffer_ohne_zusatzverben)){
//                                    unset($indizes_verben[$k]);
//                                }
//                            }
//                        }
//                    }
////                  Sortiert die Indizes der Variable aufsteigend
//                    sort($indizes_verben);
//                    if($hilfsverb_vorhanden === true && $passivverb_vorhanden === true){
//    //                  Iteriert durch die Indizes der Treffer
//                        for($j = 0; $j < count($indizes_verben); $j++){
//    //                      Zerschneidet bei jeder Iteration den String und fuegt die Markierung ein
//                            $string_mit_markierungen = substr($string_mit_markierungen, 0, $indizes_verben[$j][0]+($anzahl_passiv*$markierungs_laenge)).
//                                                       $span_zum_markieren.
//                                                       substr($string_mit_markierungen, $indizes_verben[$j][0]+($anzahl_passiv*$markierungs_laenge), strlen($indizes_verben[$j][1])).
//                                                       '</span>'.
//                                                       substr($string_mit_markierungen, -(strlen($string_mit_markierungen)-($indizes_verben[$j][0]+($anzahl_passiv*$markierungs_laenge)+strlen($indizes_verben[$j][1]))));
//                                                       $anzahl_markierungen = $anzahl_markierungen + 1;
//    //                      Zaehlt den Zaehler fuer Markierungen um 1 hoch
//                            $anzahl_passiv = $anzahl_passiv + 1;
//                        }
//
//                    }
//
////                  Setzt den Zaehler fuer die Anzahl der Passivformulierungen pro Satz auf 0
//                    $anzahl_passiv = 0;
////                  Ersetzt den Alten Satz mit dem Satz, der die eingefuegten Markierungen enthaelt
//                    $new_string = str_replace($pruef_string, $string_mit_markierungen, $new_string);
////                  Setzt die Variable auf false, damit bei der naechsten iteration Hilfsverben markiert werden koennen
//                    $hilfsverben_eingefuegt = false;
//                    $hilfsverb_vorhanden = false;
//                    $passivverb_vorhanden = false;
//                    $treffer_ohne_zusatzverben = array();
//                }
////              Leert das Array mit den Indizes der Treffer
//                $indizes_verben = array();
////                  Leert das Array mit den Hilfsverbtreffern
//                $treffer_hilfsverb = array();
////              Setzt den Index fuer den Satzbeginn auf die naechste Iteration
//                $index_satzanfang = $i + 1;
//            }
//        }
//    }
////  Gibt den neuen String mit Markierungen zurueck
//    return $new_string;
//}
//
///**Markiert Woerter im Editor, die nicht im Grundwortschatz enthalten sind
// *
// * @param string $inhalt - Inhalt des Editors
// * @return string - Inhalt des Editors mit eingefuegten Markierungen
// */
//function checkTextverstaendnis($inhalt){
//    header('Content-Type: text/html; charset=utf-8');
//    global $link;
//    //$link->set_charset("utf-8");
////  Inhalt des Editors
//    $new_string = $inhalt;
//
//
////  Regulaerer Ausdruck, um alle Woerter im Inhalt zu selektieren
//    $regex_zu_suchen = '/(?<![<=])(?<!www.)(([\w\-\.]+)@((\[([0-9]{1,3}\.){3}[0-9]{1,3}\])|(([\w\-]+\.)+)([a-zA-Z]{2,4}))|((((ht|f)tp(s?))\:\/\/)?(www.|[a-zA-Z].)[a-zA-Z0-9\-\.]+\.([a-zA-Z]{2,4})(\:[0-9]+)*(\/($|[a-zA-Z0-9\.\,\;\?\'\\\+&amp;%\$#\=~_\-]+))*)|(([[ÄäÜüÖößa-zA-Z][\-]?(<b>)?(<\/b>)?(<i>)?(<\/i>)?(<u>)?(<\/u>)?(<br>)?(<a>)?(<\/a>)?(<small>)?(<\/small>)?(<span>)?(<\/span>)?(<strong>)?(<\/strong>)?(<sub>)?(<\/sub>)?(<sup>)?(<\/sup>)?))+)(?![>=])/';
//    //$regex_zu_suchen = '/([ÄäÜüÖößa-zA-Z0-9-_]+[ÄäÜüÖößa-zA-Z0-9-_])/';
////  Sucht den Regulaeren Ausdruck im Inhalt und speichert die Treffer in $treffer
//    preg_match_all($regex_zu_suchen, rip_tags($new_string), $treffer);
//
//
////  Loescht doppelte Treffer im Array
//    $treffer[0] = array_unique($treffer[0]);
//
////  String, der alle Treffer zusammenfuegt, um diese mit der Datenbank abzugleichen
//    $string_to_check = implode("','", $treffer[0]);
//
//
//
//
////  Array zur Speicherung der Treffer in der Datenbank
//    $grundwortschatz_wert = array();
////  Selektiert alle Woerter Grundwortschattz-Begriffe der Datenbank, die im Inhalt vorkommen
//    $sql_begriffe = query("SELECT `name` FROM editor_grundwortschatz WHERE LOWER(`name`) IN ('".mb_strtolower($string_to_check)."')", $link);
//
////  Selektiert die Abkuerzungen
//    $arrSqlAbkuerzungen       = query("SELECT DISTINCT name FROM editor_abkuerzungen", $link);
//
////  Array, das die einzelnen Abkuerzungen speichert
//    $arrAbkuerzungenCheck   = array();
//
////  Iteriert durch die Treffer aus der Datenbank
//    foreach($arrSqlAbkuerzungen as $arrWort) {
//        $arrWort['name']            = trim(mb_strtolower($arrWort['name']));
//
//        if (in_array($arrWort['name'], $arrAbkuerzungenCheck))
//            continue;
//
//        $arrAbkuerzungenCheck[]     = $arrWort['name'];
//    }
//
////  Bestimmt die Art der Markierung
//    $span_zum_markieren = "<span class='textverstaendnis'>";
////  Iteriert durch die Begriffe aus der Datenbank
//    foreach($sql_begriffe as $begriff){
////      Fuegt sie in ein 1-dimensionales Array zusammen
//        $grundwortschatz_wert[] = trim(mb_strtolower($begriff['name']));
//    }
//    $grundwortschatz_wert = array_unique($grundwortschatz_wert);
//
////  Iteriert durch die Treffer aller Woerter im Inhalt
//    foreach($treffer[0] as $einzelnes_wort){
//
//        $arrFilterMath      = Array();
//        preg_match_all('/(\d+)(-|\/|\\|\*|\||:|.|~|\+|-|x|,|=|>|<)(\d+)/', $einzelnes_wort, $arrFilterMath);
//        if(isset($arrFilterMath[2][0]) && $arrFilterMath[2][0] == '-')
//            continue;
//
//        // Check if word is a number
//        if(preg_match('/^[0-9]{1,}$/', $einzelnes_wort))
//            continue;
//
//        // Check if word is E-Mail
//        if(preg_match('/([\w\-\.]+)@((\[([0-9]{1,3}\.){3}[0-9]{1,3}\])|(([\w\-]+\.)+)([a-zA-Z]{2,4}))\b/', $einzelnes_wort))
//            continue;
//
//        // Check if word is hyperlink
//        if(preg_match('/((((ht|f)tp(s?))\:\/\/)?(www.|[a-zA-Z].)[a-zA-Z0-9\-\.]+\.([a-zA-Z]{2,4})(\:[0-9]+)*(\/($|[a-zA-Z0-9\.\;\?\'\\\+&amp;%\$#\=~_\-]+))*\b)/', $einzelnes_wort))
//            continue;
//
//    //  Prueft, ob das Wort nicht in der Whitelist in der Datenbank ist
//
//        if((!in_array(mb_strtolower($einzelnes_wort), $grundwortschatz_wert) && $einzelnes_wort !== '') || in_array(mb_strtolower($einzelnes_wort), $arrAbkuerzungenCheck)){
//
//    //      Ersetzt Schraegstriche, durch Escapte Schraegstriche
//            $einzelnes_wort_regex = str_replace('/', '\/', $einzelnes_wort);
//
//    //      Ersetzt das gefundene Wort aus der Blacklist durch das gefundene Wort mit eingefuegten Markierungen
//            //$new_string = preg_replace('/\b'.$einzelnes_wort_regex.'\b/', $span_zum_markieren.$einzelnes_wort.'</span>', $new_string);
//            $new_string = str_replace($einzelnes_wort_regex, $span_zum_markieren.$einzelnes_wort.'</span>', $new_string);
//
//        }
//    }
//
////  Gibt den Inhalt mit eingefuegten Markierungen zurueck
//    return $new_string;
//}
//
///**
// *
// * @global function $link - Datenbankverbindung
// * @param string $inhalt - Inhalt des Editors
// * @return string - Inhalt des Editors mit eingefuegten Markierungen
// */
//function checkWiederholung($inhalt){
////  Datenbankverbindung
//    global $link;
////  Inhalt des Editors
//    $new_string = $inhalt;
////  Array, das alle Treffer in Kleinbuchstaben umwandelt
//    $treffer_klein = array();
////  Array von Woertern, die nicht als Wiederholung gezaehlt werden
//    $arrSqlWerte = array();
//    $treffer = array();
//    $treffer_einzeln = array();
////  Selektiert alle Woerter aus der Datenbank, die nicht als Wiederholung gezaehlt werden sollen
//    $sql_begriffe = query("SELECT DISTINCT name FROM editor_wiederholungen", $link);
////  Iteriert durch die Woerter der Datenbank und fuegt sie zu einem ein-dimensionalen Array zusammen
//    foreach($sql_begriffe as $wert){
//        $arrSqlWerte[] = mb_strtolower($wert['name']);
//    }
////  Bestimmt die Farbe der Markierung
//    $span_zum_markieren = "<span class='wiederholungen'>";
////  Teilt den Inhalt des Editors in einzelne Woerter auf
//    $regex_zu_suchen = '/(?<![<="])(?<!www\.)\b((www\.[\d\w]+\.[\d\w]+)|(([a-zA-Z\x80-\xFF](<b>)?(<\/b>)?(<i>)?(<\/i>)?(<u>)?(<\/u>)?(<br>)?(<a>)?(<\/a>)?(<small>)?(<\/small>)?(<span>)?(<\/span>)?(<strong>)?(<\/strong>)?'.
//                      '(<sub>)?(<\/sub>)?(<sup>)?(<\/sup>)?))+)\b(?![>="])/';
////  Sucht den regulaeren Ausdruck im Inhalt
//    preg_match_all($regex_zu_suchen, $new_string, $treffer);
////  Iteriert durch die Treffer und wandelt die Buchstaben in Kleinbuchstaben um
//    foreach($treffer[0] as $test){
//        $treffer_klein[] = mb_strtolower($test);
//    }
////  Sucht sich die Anzahl der Vorkommen der einzelnen Woerter
//    $werte_vorkommen = array_count_values($treffer_klein);
//    $keys = array_keys($werte_vorkommen);
////  Iteriert durch die Treffer
//    $treffer_klein = array_unique($treffer_klein);
////  Iteriert durch die Treffer
//
//
//    foreach($treffer_klein as $wort){
////      Prueft, ob ein Treffer mit Tags in der Liste der Keys ist
//        if(isset($werte_vorkommen[rip_tags($wort)])){
//            if($werte_vorkommen[rip_tags($wort)] > 2 && rip_tags($wort) !== $wort){
//    //          Fuegt einen neuen Schluessel (Treffer mit Tags) in das Array ein, mit dem Wert 5 damit das Wort als Wiederholung erkannt wird
//                $werte_vorkommen[$wort] = 5;
//    //          Fuegt den Treffer mit Tags in das Key-Array
//                $keys[] = $wort;
//            }
//        }
//    }
//
//    for($i = 0; $i < count($keys); $i++) {
////      Prueft, ob das Wort oefter als 5 mal vorkommt
//        if($werte_vorkommen[$keys[$i]] > 2){
////          Prueft, ob das Wort Teil eines Tags ist und ob es ein Wort ist, das nicht als Wiederholung zaehlt
//            if(!preg_match('/data|\bspan\b|\b(p)\b|\b(a)\b|body|\bui\b|amp|nbsp|(!>>)\b[ac-hj-tv-zAC-HJ-TV-Z]{1}\b/', $keys[$i]) && $keys[$i] !== '' && !in_array($keys[$i], $arrSqlWerte)){
////              Sucht das Wort nochmal, diesmal mit Groß- und Kleinbuchstaben, da es im Array nur kleingeschrieben enthalten ist
//                $keys[$i] = str_replace('/', '\/', $keys[$i]);
//                preg_match_all('/'.$keys[$i].'/i', $new_string, $treffer_einzeln);
////              loescht Doppelungen im Array
//                $treffer_einzeln[0] = array_unique($treffer_einzeln[0]);
////              Iteriert durch die neuen treffer und markiert diese
//                foreach($treffer_einzeln[0] as $wiederholung){
////                  Fuegt die Markierung ein
//                    $wiederholung = str_replace('/', '\/', $wiederholung);
//                    $new_string = preg_replace('/\b'.$wiederholung.'\b/', $span_zum_markieren.str_replace('\/', '/', $wiederholung).'</span>', $new_string);
//                }
//            }
//        }
//    }
////  Gibt den Inhalt mit eingefuegten Markierungen zurueck
//    return $new_string;
//}
?>