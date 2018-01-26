<?php
/**
 *
 * @author    Mahmood Dhia
 * @copyright 2015 Aicovo GmbH
 *
 */

// Debug Error Reporting
error_reporting(E_ALL);
ini_set('display_errors', 1);

global $boolDebug;
$boolDebug = false;

// Check if POST VALID
if ($_SERVER['REQUEST_METHOD'] != 'POST' || !isset($_POST) || !isset($_POST['action']) || !isset($_POST['text']))
    exit;

// Database connection
$objDB = mysqli_connect('dedi1612.your-server.de', 'stellu_3_w', 'NpFK5uxhj3UzGpVH', 'stellu_db1_kopie');
$objDB->set_charset("utf8");

// Set Charset
header('Content-Type: text/html; charset=UTF-8');
mb_internal_encoding('UTF-8');
putenv("LANG=UTF-8");
setlocale(LC_ALL, 'de_DE@euro', 'de_DE', 'de', 'ge', 'UTF-8');

if (!isset($_POST['old']) || $_POST['old'] != 1)
{
    //Check Debug
    $boolDebug = isset($_POST['debug']);

    // Handel action
    switch ($_POST['action'])
    {
        /** Gruppe 1 */
    case 'checkFloskel':
        checkWordsInDatabase($_POST['text'], $objDB, 'editor_floskeln', 'floskeln');
        logUseInDB('Floskeln', $objDB);
        exit;
        break;
    case 'checkNegativeWoerter':
        checkWordsInDatabase($_POST['text'], $objDB, 'editor_negativewoerter', 'negative_woerter');
        logUseInDB('Negative Wörter', $objDB);
        exit;
        break;
    case 'checkTextverstaendnis':
        checkTextverstaendnisN($_POST['text'], $objDB);
        logUseInDB('Textverständnis', $objDB);
        exit;
        break;
    case 'checkAnglizismen':
        checkWordsInDatabase($_POST['text'], $objDB, 'editor_anglizismen', 'anglizismen');
        logUseInDB('Anglizismen', $objDB);
        exit;
        break;

        /** Gruppe 2 */
    case 'checkNominalstil':
        checkNominalstilN($_POST['text'], $objDB);
        logUseInDB('Nominalstil', $objDB);
        exit;
        break;
    case 'checkAktivPassiv':
        checkAktivPassivN($_POST['text'], $objDB);
        logUseInDB('Aktiv-Passiv', $objDB);
        exit;
        break;

        /** Gruppe 3 */
    case 'checkWiederholung':
        checkWiederholungN($_POST['text'], $objDB);
        logUseInDB('Wiederholungen', $objDB);
        exit;
        break;

        /** Gruppe 4 */
    case 'checkWortlaenge':
        checkWortlaengeN($_POST['text'], $objDB);
        logUseInDB('Wortlänge', $objDB);
        exit;
        break;
    case 'checkSatzlaenge':
        checkSatzlaengeN($_POST['text'], $objDB);
        logUseInDB('Lange Sätze', $objDB);
        exit;
        break;

        /** Other */
    case 'printPDF':        
        printPDF($_POST, $objDB);
        exit;
        break;
    case 'printSinglePDF':
        printSinglePDF($_POST['text']);
        exit;
        break;
    }

}

/**
 * Sätze die länger als 250 Zeichen (HTML Tags ausgeschlossen) sind werden markiert
 *
 * @param $text
 */
function checkSatzlaengeN($text, $objDB, $returnText = false)
{
    /* Möglichkeiten eines Satzendes
    $arrLineEnd         = array('! ','? ','. ', '<p>', '<li>');
    $strUnidelim        = $arrLineEnd[0];
    $strFormatText      = str_replace($arrLineEnd, $strUnidelim, $text);
    $arrLines           = explode($strUnidelim, $strFormatText);
    */
    global $boolDebug;

    $arrLines = splitTextToLines($text, $objDB);

    foreach ($arrLines as $strLine)
    {
        if (count(splitTextToWorlds($strLine, $objDB, true, true, true, true, true, true)) < 16)
            continue;

        //if(strlen(filter_tags(filter_spaces($strLine))) <= 250)
        //    continue;

        $strLine = str_replace(array(
            '<h2>',
            '</h2>'
        ), '', $strLine);
        $strLine = str_replace(array(
            '<p>',
            '</p>'
        ), '', $strLine);
        $strLine = str_replace(array(
            '<li>',
            '</li>'
        ), '', $strLine);
        $strLine = str_replace(array(
            '<ul>',
            '</ul>'
        ), '', $strLine);
        $strLine = str_replace(array(
            '<br>',
            '</br>'
        ), '', $strLine);

        $text = str_replace($strLine, "<span class='lange_saetze'>$strLine</span>", $text);
    }

    if ($returnText)
        return $text;

    echo $text;
}

/**
 * Alle Wörter die länger sind als 14 Zeichen werden in dieser Funktion markiert.
 *
 * @param $text string
 */
function checkWortlaengeN($text, $objDB, $returnText = false)
{
    global $boolDebug;
    $arrMarkWords = array();
    $arrWords     = splitTextToWorlds($text, $objDB, true, true, true, true);
    
    if (count($arrWords) <= 0)
    {
        if ($returnText)
            return $text;

        echo $text;
        return;
    }

    foreach ($arrWords as $strWord)
    {
        if (mb_strlen($strWord) >= 15)
            $arrMarkWords[] = $strWord;
    }
    
    
    
    $text = replaceWordInText($text, $arrMarkWords, "<span class='wortlaenge'>$1</span>");

    //foreach ($arrMarkWords as $strMarkWord)
    //    $text = preg_replace("/(?<![ÄäÜüÖößa-zA-Z\-\(\)\"\'])(" . regex_real_string($strMarkWord) . ")(?![ÄäÜüÖößa-zA-Z\-\(\)\"\'])/ui", "<span class='wortlaenge'>$1</span>", $text);

    if ($returnText)
        return $text;

    echo $text;
}

/**
 * Wenn sich ein Wort öfter als 2 mal wiederholt und nicht in der
 * Whitelist `editor_wiederholungen` steht wird es markiert
 *
 * @param $text  string
 * @param $objDB mysqli - Datenbankverbindung
 */
function checkWiederholungN($text, $objDB, $returnText = false)
{
    $arrWhiteList = array();
    $arrResult    = queryToArray("SELECT DISTINCT `name` FROM `editor_wiederholungen`;", $objDB);

    foreach ($arrResult as $value)
        $arrWhiteList[] = mb_strtolower($value['name']);

    $arrWords = splitTextToWorlds($text, $objDB, true, true, true, true, false);

    global $boolDebug;

    if (count($arrWords) <= 0)
    {
        if ($returnText)
            return $text;

        echo $text;
        return;
    }

    // Filter by whitelist
    foreach ($arrWords as $key => $strWord)
    {
        $arrWords[$key] = mb_strtolower($strWord);
        if (in_array(mb_strtolower($strWord), $arrWhiteList))
            unset($arrWords[$key]);
    }



    $arrWordRepetitions = array();
    foreach (array_count_values($arrWords) as $strWord => $intRepeat)
    {
        if ($intRepeat > 2)
            $arrWordRepetitions[] = mb_strtolower($strWord);
    }
        
    $text = replaceWordInText($text, $arrWordRepetitions, "<span class='wiederholungen'>$1</span>");
    //foreach ($arrWordRepetitions as $strWordRepetition)
    //    $text = preg_replace("/(?<![ÄäÜüÖößa-zA-Z\.])(" . regex_real_string($strWordRepetition) . ")(?![ÄäÜüÖößa-zA-Z\.])/ui", "<span class='wiederholungen'>$1</span>", $text);

    if ($returnText)
        return $text;

    echo $text;
}

/**
 * Markiert alle Wörter die nicht in der Table editor_grundwortschatz stehen,
 * ausgenommen davon sind aber Abkürzungen die in der Table editor_abkuerzungen stehen.
 *
 * @param $text
 * @param $objDB mysqli - Datenbankverbindung
 */
function checkTextverstaendnisN($text, $objDB, $returnText = false)
{
    global $boolDebug;
    $arrAbbreviation        = getAbbreviationAndWhitelistHyphen($objDB);
    $arrWords = splitTextToWorlds($text, $objDB, true, true, true, true, false);

    $arrUnknowAbbreviation = array();
    $arrResult = queryToArray("SELECT DISTINCT `name` FROM `editor_unknownabbreviations`;", $objDB);
    foreach ($arrResult as $arrWord)
    {
        $strWord = mb_strtolower(trim($arrWord['name']));
        if (in_array($strWord, $arrUnknowAbbreviation))
            continue;
        $arrUnknowAbbreviation[] = $strWord;
    }

    if (count($arrWords) <= 0)
    {
        if ($returnText)
            return $text;

        echo $text;
        return;
    }

    // Filter abbreviation
    foreach ($arrWords as $key => $strWord)
    {
        $strWord        = trim(mb_strtolower(mysqli_real_escape_string($objDB, $strWord)));
        $arrWords[$key] = $strWord;

        if (empty($strWord) || in_array($strWord, $arrAbbreviation))
            unset($arrWords[$key]);
    }

    $strWordList   = implode("','", $arrWords);
    $arrKnownWords = array();
    $arrResult     = queryToArray("SELECT LOWER(`name`) AS `name` FROM `editor_grundwortschatz` WHERE LOWER(`name`) IN ('$strWordList');", $objDB);


    foreach ($arrResult as $value)
        $arrKnownWords[] = $value['name'];

    // Filter know words
    $arrFilteredWords   = array();
    $arrSharpS          = array();

    foreach ($arrWords as $strWord)
    {
        if (!in_array($strWord, $arrKnownWords))
        {
            if(mb_strstr($strWord, 'ß'))
                $arrSharpS[$strWord] = str_replace('ß', 'ss', $strWord);
            else
                $arrFilteredWords[] = $strWord;
        }
    }

    $strSharpSWordList   = implode("','", $arrSharpS);
    $arrSharpSResult     = queryToArray("SELECT LOWER(`name`) AS `name` FROM `editor_grundwortschatz` WHERE LOWER(`name`) IN ('$strSharpSWordList');", $objDB);
    $arrSharpSKnowWords  = array();

    foreach ($arrSharpSResult as $value)
        $arrSharpSKnowWords[] = $value['name'];

    foreach ($arrSharpS as $strKey => $strWord)
    {
        if (!in_array($strWord, $arrSharpSKnowWords))
            $arrFilteredWords[] = $strKey;
    }

    $arrFilteredWords   = array_merge($arrUnknowAbbreviation, $arrFilteredWords);

    $arrFilteredWords   = array_unique($arrFilteredWords);
    $text               = replaceWordInText($text, $arrFilteredWords, "<span class='textverstaendnis'>$1</span>");

    //foreach ($arrFilteredWords as $strFilteredWord)
    //    $text = preg_replace("/(?<![ÄäÜüÖößa-zA-Z\-\(\)\"\'])(" . regex_real_string($strFilteredWord) . ")(\W\ |\n|\ )/ui", "<span class='textverstaendnis'>$1</span>$2", $text);

    if ($returnText)
        return $text;

    echo $text;
}

/**
 * Markiert Wört die mit {heit, keit, ung, tum, keiten, ungen, heiten} enden
 * und NICHT in der editor_nominalstil Table stehen.
 *
 * @param $text
 * @param $objDB mysqli - Datenbankverbindung
 */
function checkNominalstilN($text, $objDB, $returnText = false)
{
    $arrMarkWords = array();
    $arrWhiteList = array();
    $arrWords     = splitTextToWorlds($text, $objDB, true, true, true, true, true, true);

    if (count($arrWords) <= 0)
    {
        if ($returnText)
            return $text;

        echo $text;
        return;
    }

    $arrResult  = queryToArray("SELECT DISTINCT `name` FROM `editor_nominalstil`;", $objDB);
    $arrEndings = array(
        'heit',
        'keit',
        'ung',
        'tum',
        'keiten',
        'ungen',
        'heiten'
    );

    foreach ($arrResult as $value)
        $arrWhiteList[] = mb_strtolower($value['name']);

    // Filter by whitelist
    foreach ($arrWords as $key => $strWord)
    {
        $arrWords[$key] = mb_strtolower($strWord);
        if (in_array(mb_strtolower($strWord), $arrWhiteList))
            unset($arrWords[$key]);
    }

    foreach ($arrWords as $strWord)
    {
        foreach ($arrEndings as $strEnd)
        {
            if (stringEndsWith($strWord, $strEnd))
                $arrMarkWords[] = $strWord;
        }
    }

    $text = replaceWordInText($text, $arrMarkWords, "<span class='nominalstil'>$1</span>");
    //foreach ($arrMarkWords as $strWord)
    //    $text = preg_replace("/(?<![ÄäÜüÖößa-zA-Z\-\(\)\"\'])(" . regex_real_string($strWord) . ")(?![ÄäÜüÖößa-zA-Z\-\(\)\"\'])/ui", "<span class='nominalstil'>$1</span>", $text);

    if ($returnText)
        return $text;

    echo $text;
}

/**
 * Markiert Wörter die in der $table stehen mit der Klasse $mark
 *
 * @param $text  string
 * @param $objDB mysqli     - Datenbankverbindung
 * @param $table string     - Tablename in der Datenbank
 * @param $mark  string      - Markierungs Klassen namen
 */
function checkWordsInDatabase($text, $objDB, $table, $mark, $returnText = false)
{
    global $boolDebug;

    $arrMarkWords = array();
    $arrWords     = splitTextToWorlds($text, $objDB, true, true, true, true, true, true);

    if (count($arrWords) <= 0)
    {
        if ($returnText)
            return $text;

        echo $text;
        return;
    }

    $arrSql = array();

    foreach ($arrWords as $word)
        $arrSql[] = "LOWER(`name`) LIKE '%" . mysqli_real_escape_string($objDB, $word) . "%'";

    $arrResult = queryToArray("SELECT `name` AS `name`FROM `$table` WHERE " . implode(' OR ', $arrSql) . " ORDER BY CHAR_LENGTH(`name`) DESC", $objDB);

    foreach ($arrResult as $value)
        $arrMarkWords[] = mb_strtolower($value['name']);

    array_unique($arrMarkWords);

    foreach ($arrMarkWords as $strMarkWord)
    {
        if (strstr($strMarkWord, ' '))
            $text = preg_replace("/(" . regex_real_string($strMarkWord) . ")/ui", "<span class='$mark'>$1</span>", $text);
        else
            $text = replaceWordInText($text, $strMarkWord,  "<span class='$mark'>$1</span>");
    }

    if ($returnText)
        return $text;

    echo $text;
}

/**
 * Markiert Aktive/Passive Wörter
 *
 * @param $text
 * @param $objDB mysqli - Datenbankverbindung
 */
function checkAktivPassivN($text, $objDB, $returnText = false)
{
    global $boolDebug;

    $arrLines = splitTextToLines($text, $objDB);

    $arrAuxiliaryVerbs = array(
        'werde',
        'wirst',
        'wird',
        'werden',
        'werdet',
        'wurde',
        'wurdest',
        'wurden',
        'wurdet',
        'bin',
        'bist',
        'ist',
        'sind',
        'seid',
        'war',
        'warst',
        'waren',
        'wart'
    );
    $strAuxiliaryVerbs = implode(')|(', $arrAuxiliaryVerbs);

    foreach ($arrLines as $strLine)
    {
        if (empty($strLine))
            continue;

        $boolAddAuxiliaryVerbs = false;
        $arrMarkWords          = array();

        if (stringEndsWith($strLine, '</p>'))
            $strLine = str_replace(array(
                '<p>',
                '</p>'
            ), '', $strLine);
        else if (stringEndsWith($strLine, '</li>'))
            $strLine = str_replace(array(
                '<li>',
                '</li>'
            ), '', $strLine);

        // Suche nach Passivformulierungen wie z.B. worden, werden und allen wörtern die mit en oder t enden.
        $arrPassiveFormulations = array();
        preg_match_all('/((?<![ÄäÜüÖößa-zA-Z\.\-])[ÄäÜüÖößa-zA-Z]+((en)|t){1}( worden| werden){0,1}( sein){0,1}(?![ÄäÜüÖößa-zA-Z\.\-]))/u', $strLine, $arrPassiveFormulations, PREG_OFFSET_CAPTURE);

        // Suche nach Hilfsverben wie z.B. ...
        $arrAuxiliaryVerbs = array();
        preg_match_all('/\b((' . $strAuxiliaryVerbs . '))\b/u', $strLine, $arrAuxiliaryVerbs, PREG_OFFSET_CAPTURE);


        if (count($arrPassiveFormulations[0]) > 0 && count($arrAuxiliaryVerbs[0]) > 0)
        {
            foreach ($arrPassiveFormulations[0] as $arrPassivFormulation)
            {

                $arrPassivFormulationSplit = explode(' ', $arrPassivFormulation[0]);

                $arrDBPassiveWords = queryToArray("SELECT `name` FROM `passivverben` WHERE `name` = '" . mysqli_real_escape_string($objDB, $arrPassivFormulationSplit[0]) . "'", $objDB);

                if (count($arrDBPassiveWords) > 0 && !in_array($arrPassivFormulationSplit[0], $arrAuxiliaryVerbs))
                {
                    if (!$boolAddAuxiliaryVerbs)
                    {
                        foreach ($arrAuxiliaryVerbs[0] as $arrAuxiliaryVerb)
                        {
                            if (!isset($arrPassivFormulationSplit[1]) || $arrPassivFormulationSplit[1] != $arrAuxiliaryVerb[0])
                                $arrMarkWords[] = mb_strtolower($arrAuxiliaryVerb[0]);
                        }

                        $boolAddAuxiliaryVerbs = true;
                    }


                    $arrMarkWords[] = mb_strtolower($arrPassivFormulation[0]);
                }
            }
        }

        $arrMarkWords = array_unique($arrMarkWords);
        $strLineMark  = $strLine;

        $text = replaceWordInText($text, $arrMarkWords, "<span class='aktiv_passiv'>$1</span>");
        //foreach ($arrMarkWords as $strMarkWord)
        //    $strLineMark = preg_replace("/(?<![ÄäÜüÖößa-zA-Z\-\(\)\"\'])(" . regex_real_string($strMarkWord) . ")(?![ÄäÜüÖößa-zA-Z\-\(\)\"\'])/ui", "<span class='aktiv_passiv'>$1</span>", $strLineMark);
        $text = str_replace($strLine, $strLineMark, $text);

    }

    if ($returnText)
        return $text;

    echo $text;
}

/**
 * Erstellt eine PDF des aktuellen Textes mit den ausgewählten Markierungen
 *
 * @param $text
 */
function printSinglePDF($text)
{
    require_once('../../mpdf/mpdf.php'); // 22
    $objPDF     = new mPDF('', 'A4', 0, '', 15, 15, 22, 13, 9, 9);
    $intYear    = date('Y') + 1;
    $stylesheet = file_get_contents('../../css/editor.content.css'); // external css
    $objPDF->WriteHTML($stylesheet, 1);
    $objPDF->SetHTMLHeader('<div align="right" style="margin-bottom: 20px"><a href="http://www.stellenanzeigen-texten.de"><img src="../../img/logo-toolbox-300dpi.png" style="width: 150px"/></a></div>');
    $objPDF->SetHTMLFooter('© 2015 - ' . $intYear . ' <a href="http://www.stellenanzeigen-texten.de">www.stellenanzeigen-texten.de</a> powered bei aicovo gmbh');

    $objPDF->WriteHTML($text, 2);

    header('Set-Cookie: fileDownload=true; path=/');
    $objPDF->Output('Stellenanzeigen-Texten-Editor.pdf', 'D');
}

/**
 * Erstellt Bericht als PDF
 *
 * @param $arrPost
 * @param $objDB
 */
function printPDF($arrPost, $objDB)
{
    global $boolDebug;
    
    require_once('mPDFReport.php');
    $objPDF     = new mPDFReport('', 'A4', 0, '', 19, 15, 22, 13, 9, 9);
    $strText    = $arrPost['text'];
    $strFooter  = trim($arrPost['footer']);
    $intYear    = date('Y') + 1;
    $stylesheet = file_get_contents('../../css/pdf.content.css'); // external css
    
    
    if(!isset($arrPost['jobquick']))
        $strText     = html_entity_decode($strText);
    else
        $stylesheet .= "p+p{margin-top: 10px;}";
    
    $objPDF->WriteHTML($stylesheet, 1);
    $objPDF->SetHTMLHeader('<div class="pdf"><div align="right" style="margin-bottom: 20px"><a href="http://www.stellenanzeigen-texten.de"><img src="../../img/logo-toolbox-300dpi.png" style="width: 150px"/></a></div>');
    $objPDF->SetHTMLFooter('<table style="width: 100%"><tr><td>'.$strFooter.'</td><td style="text-align: right;">Seite {PAGENO} von {nb}</td></tr></table></div>');

    include("print.firstSite.php");

    /** Group 1 */
    $html = '<h1 style="color: #F00;">Check — Floskeln</h1>' . checkWordsInDatabase($strText, $objDB, 'editor_floskeln', 'floskeln', true);
    if (!strstr($html, "class='floskeln'"))
        $html .= '<h1 style="color: #F00;">Hier gibt es nichts zu meckern ;-)</h1>';

    $objPDF->WriteHTML($html, 2);
    $objPDF->AddPage();

    $html = '<h1 style="color: #F00;">Check — Negative Wörter</h1>' . checkWordsInDatabase($strText, $objDB, 'editor_negativewoerter', 'negative_woerter', true);
    if (!strstr($html, "class='negative_woerter'"))
        $html .= '<h1 style="color: #F00;">Hier gibt es nichts zu meckern ;-)</h1>';

    $objPDF->WriteHTML($html, 2);
    $objPDF->AddPage();

    $html = '<h1 style="color: #F00;">Check — Textverständnis</h1>' . checkTextverstaendnisN($strText, $objDB, true);
    if (!strstr($html, "class='textverstaendnis'"))
        $html .= '<h1 style="color: #F00;">Hier gibt es nichts zu meckern ;-)</h1>';

    $objPDF->WriteHTML($html, 2);
    $objPDF->AddPage();

    $html = '<h1 style="color: #F00;">Check — Anglizismen</h1>' . checkWordsInDatabase($strText, $objDB, 'editor_anglizismen', 'anglizismen', true);
    if (!strstr($html, "class='anglizismen'"))
        $html .= '<h1 style="color: #F00;">Hier gibt es nichts zu meckern ;-)</h1>';

    $objPDF->WriteHTML($html, 2);
    $objPDF->AddPage();

    /** Group 2 */
    $html = '<h1 style="color: #F00;">Check — Nominalstil</h1>' . checkNominalstilN($strText, $objDB, true);
    if (!strstr($html, "class='nominalstil'"))
        $html .= '<h1 style="color: #F00;">Hier gibt es nichts zu meckern ;-)</h1>';

    $objPDF->WriteHTML($html, 2);
    $objPDF->AddPage();

    $html = '<h1 style="color: #F00;">Check — Aktiv-Passiv</h1>' . checkAktivPassivN($strText, $objDB, true);
    if (!strstr($html, "class='aktiv_passiv'"))
        $html .= '<h1 style="color: #F00;">Hier gibt es nichts zu meckern ;-)</h1>';

    $objPDF->WriteHTML($html, 2);
    $objPDF->AddPage();

    /** Group 3 */
    $html = '<h1 style="color: #F00;">Check — Wiederholungen</h1>' . checkWiederholungN($strText, $objDB, true);
    if (!strstr($html, "class='wiederholungen'"))
        $html .= '<h1 style="color: #F00;">Hier gibt es nichts zu meckern ;-)</h1>';

    $objPDF->WriteHTML($html, 2);
    $objPDF->AddPage();

    /** Group 4 */
    $html = '<h1 style="color: #F00;">Check — Wortlänge</h1>' . checkWortlaengeN($strText, $objDB, true);
    if (!strstr($html, "class='wortlaenge'"))
        $html .= '<h1 style="color: #F00;">Hier gibt es nichts zu meckern ;-)</h1>';

    $objPDF->WriteHTML($html, 2);
    $objPDF->AddPage();

    $html = '<h1 style="color: #F00;">Check — Lange Sätze</h1>' . checkSatzlaengeN($strText, $objDB, true);
    if (!strstr($html, "class='lange_saetze'"))
        $html .= '<h1 style="color: #F00;">Hier gibt es nichts zu meckern ;-)</h1>';

    $objPDF->WriteHTML($html, 2);

    if($arrPost['lastPage'] == 1)
    {
        $objPDF->AddPage();
        $strEnd = '<br>
        <div class="firstSite">
            <div class="endSite">
                Seminare zum Thema "wirkungsvolle Stellenanzeigen" finden Sie neben weiteren Bildungsangeboten im Bereich Personal- und Ausbildungsmarketing unter <b>www.recruiting-erfolg.de</b>
                <br><br>
                Neben offenen Seminaren werden auch individuelle Trainingslösungen angeboten. Gemeinsam analysieren wir Ihren Schulungsbedarf und konzipieren für Ihre speziellen Anforderungen maßgeschneiderte Workshops, Inhouse-Schulungen, Coachings oder Trainings on the Job.
                <br><br>
                Gerne entwickeln wir auch für Sie erfolgreiche Stellenanzeigen und kümmern uns um die Schaltung Ihrer Inserate zum bestmöglichen Preis.
                <br><br>
                Ihre Fragen beantwortet Ihnen Axel Haitzer persönlich. Nehmen Sie jetzt Kontakt auf.<br>
                <br>
                aicovo gmbh, Hechtseestraße 16, 83022 Rosenheim, DEUTSCHLAND<br>
                <b>Telefon: 0 80 31 - 222 76 56</b>, Fax: 0 80 31 - 222 78 10<br>
                <b>E-Mail: axel.haitzer@aicovo.com</b><br>
            </div>
        </div>';
        $objPDF->WriteHTML($strEnd, 2);
    }

    header('Set-Cookie: fileDownload=true; path=/');
    $objPDF->Output('Stellenanzeigen-Texten-Editor.pdf', 'D');
}

/**
 * Function
 */

/**
 * Ersetzt ein allein stehendes Wort in einem Text
 *
 * @param $strText
 * @param $mixWord
 * @param $strReplaceContent
 * @return mixed
 */
function replaceWordInText($strText, $mixWord, $strReplaceContent)
{
    global $boolDebug;
    if(!is_array($mixWord))
        $mixWord = array($mixWord);

    foreach ($mixWord as $strWord)
        $strText = preg_replace("/(?<![ÄäÜüÖößA-Za-z0-9\-])(".regex_real_string($strWord).")(?![ÄäÜüÖößA-Za-z0-9\-\>]|\!\?\.[ÄäÜüÖößA-Za-z0-9])/ui", $strReplaceContent, $strText);

    return $strText;
}

/**
 * Ladet ein Array mit allen Abkürzungen und Wörter mit Bildestrichen
 * welches nach größe des wortes sortiert ist.
 *
 * @param $objDB
 * @return array
 */
function getAbbreviationAndWhitelistHyphen($objDB)
{
    $arrList = array();
    $arrResult = queryToArray("SELECT DISTINCT `name` FROM `editor_whitelisthyphen`;", $objDB);
    foreach ($arrResult as $arrWord)
    {
        $strWord = mb_strtolower(trim($arrWord['name']));
        if (in_array($strWord, $arrList))
            continue;
        $arrList[] = $strWord;
    }

    $arrResult = queryToArray("SELECT DISTINCT `name` FROM `editor_abkuerzungen`;", $objDB);
    foreach ($arrResult as $arrWord)
    {
        $strWord = mb_strtolower(trim($arrWord['name']));

        if (in_array($strWord, $arrList))
            continue;

        $arrList[] = $strWord;
    }



    usort($arrList, function ($a,$b){
        return strlen($b)-strlen($a);
    });

    return $arrList;
}

/**
 * Fuehrt SQL Abfragen aus und gibt werte als Array zurück
 *
 * @param $sql   string   - SQL-Abfrage
 * @param $objDB mysqli - Datenbankverbindung
 *
 * @return array        - Array mit Ergebnissen aus Datenbank
 */
function queryToArray($sql, $objDB)
{
    $arrResultArray = array();
    $objResult = $objDB->query($sql) or die(mysqli_error($objDB));

    while ($row = mysqli_fetch_assoc($objResult))
        $arrResultArray[] = $row;

    return $arrResultArray;
}

/**
 * Überprüft ob $word mit $end anfängt
 *
 * @param $word
 * @param $start
 *
 * @return bool
 */
function stringStartsWith($word, $start)
{
    if (mb_strlen($word) < mb_strlen($start))
        return false;

    // search backwards starting from haystack length characters from the end
    return $start === "" || strrpos($word, $start, -strlen($word)) !== false;
}

/**
 * Überprüft ob $word mit $end endet
 *
 * @param $word string  - Wort
 * @param $end  string   - Wortende
 *
 * @return bool
 */
function stringEndsWith($word, $end)
{
    if (mb_strlen($word) < mb_strlen($end))
        return false;

    // search forward starting from end minus needle length characters
    return $end === "" || strpos($word, $end, strlen($word) - strlen($end)) !== false;
}

/**
 * Ähnlich wie mysql_real_string nur für regex
 *
 * @param $string
 * @return mixed
 */
function regex_real_string($string)
{
    $arrSearch  = array(
        "\\",
	"/",
        '(',
        ')',
        '.',
        '!',
        '?',
        '$',
        '<',
        '>',
        '|',
        '^',
        '-',
        ':',
        "\n",
        "\r",
        "\t"
    );
    $arrReplace = array(
        "\\\\",
	"\/",
        '\(',
        '\)',
        '\.',
        '\!',
        '\?',
        '\$',
        '\<',
        '\>',
        '\|',
        '\^',
        '\-',
        '\:',
        '',
        '',
        ''
    );
    return str_replace($arrSearch, $arrReplace, $string);
}


/**
 * Filter & Split Functions
 */

/**
 * Filtert HTML Tags
 *
 * @param $string
 *
 * @return mixed|string
 */
function filter_tags($string)
{
    return preg_replace('/<[^>]*>/', ' ', $string);
}

/**
 * Filtert Zeilenumbrüche und spaces
 *
 * @param $string
 *
 * @return mixed|string
 */
function filter_spaces($string)
{
    $string = str_replace("\r", '', $string); // --- replace with empty space
    $string = str_replace("\n", ' ', $string); // --- replace with space
    $string = str_replace("\t", ' ', $string); // --- replace with space
    $string = trim(preg_replace('/ {2,}/', ' ', $string));

    return $string;
}

/**
 * Main Filter, filtert je nach einstellung Links, Mails, Nummern und HTML-Tags heraus.
 * Außerdem splitet er den übergeben text in einzelne Wörter.
 *
 * @param      $text
 * @param bool $links
 * @param bool $mail
 * @param bool $number
 * @param bool $tags
 * @param bool $unique
 * @param bool $tolower
 *
 * @return array
 *
 * @NOTE encoding fixen bei z.B. Å                  - Ist unwichtig, da solche Zeichen normalerweise nicht markiert werden oder in wörter vorkommen.
 */
function splitTextToWorlds($text, $objDB, $links = false, $mail = false, $number = false, $tags = false, $unique = true, $tolower = false, $decodeEntity = true)
{
    global $boolDebug;

    if($boolDebug)
    {
        $decodeEntity = false;
    }

    if($decodeEntity)
        $text = html_entity_decode($text, ENT_COMPAT, 'UTF-8');

    $arrAbbreviation = getAbbreviationAndWhitelistHyphen($objDB);

    foreach ($arrAbbreviation as $key => $strWord)
        $arrAbbreviation[$key] = (mb_strtolower(regex_real_string($strWord)));

    $strAbbreviation = implode('|', $arrAbbreviation);

    $arrMatches      = array();
    //$text            = filterUnknowChar($text);
    
    preg_match_all('/((' . $strAbbreviation . ')|((((ht|f)tp(s?))\:\/\/)?(www.|[a-zA-Z].)[a-zA-Z0-9\-\.]+\.([a-zA-Z]{2,4})(\:[0-9]+)*(\/($|[a-zA-Z0-9\.\,\;\?\'\\\+&amp;%\$#\=~_\-]+))*)|([\w\-\.]+)@((\[([0-9]{1,3}\.){3}[0-9]{1,3}\])|(([\w\-]+\.)+)([a-zA-Z]{2,4}))|(((\d+)(-|\/|\\|\*|\||:|.|~|\+|-|x|,|=|>|<))+)|(\d+)|(<[^>]*>)|([ÄäÜüÖößa-zA-Z]+))/ui', $text, $arrMatches);

    $arrMatches = $arrMatches[0];
    foreach ($arrMatches as $key => $strMatch)
    {
        if ($tolower)
            $arrMatches[$key] = mb_strtolower($strMatch);

        if ($links)
            if (preg_match('/((((ht|f)tp(s?))\:\/\/)?(www.|[a-zA-Z].)[a-zA-Z0-9\-\.]+\.([a-zA-Z]{2,4})(\:[0-9]+)*(\/($|[a-zA-Z0-9\.\,\;\?\'\\\+&amp;%\$#\=~_\-]+))*)/', $strMatch))
                unset($arrMatches[$key]);

        if ($mail)
            if (preg_match('/([\w\-\.]+)@((\[([0-9]{1,3}\.){3}[0-9]{1,3}\]))/', $strMatch))
                unset($arrMatches[$key]);

        if ($number)
            if (preg_match('/(((\d+)(-|\/|\\\|\*|\||:|.|~|\+|-|x|,|=|>|<))+)/', $strMatch) || preg_match('/(\d+)/', $strMatch))
                unset($arrMatches[$key]);

        if ($tags)
            if (preg_match('/(<[^>]*>)/', $strMatch))
                unset($arrMatches[$key]);
    }


    return ($unique ? array_unique($arrMatches) : $arrMatches);
}

/**
 * Scheidet ein Text in Zeilen dabei wärden folgende Zeichen als Satz ende erkennt:
 * . ! ? : ; <br/> </br> </h2> </p> </li> </ul>
 *
 * @param $text
 * @param $objDB mysqli - Datenbankverbindung
 */
function splitTextToLines($text, $objDB)
{
    global $boolDebug;
    $arrAbbreviation = getAbbreviationAndWhitelistHyphen($objDB);
    $arrResult       = queryToArray("SELECT DISTINCT `word` FROM `editor_lange_saetze_whitelist`;", $objDB);
    $arrWhiteList    = array();
    foreach ($arrResult as $value)
        $arrWhiteList[] = mb_strtolower($value['word']);

    $arrWords     = array();
    $arrSentences = array();
    $strWord      = '';

    for ($i = 0; $i < strlen($text); $i++)
    {
        switch ($text[$i])
        {
        case ' ':
            $arrWords[] = $strWord;
            $strWord    = '';
            break;

        case '.':
            $boolWordFind = false;

            foreach ($arrWhiteList as $value)
            {
                if (checkLastChars($text, $i, mb_strtolower($value)))
                {
                    $boolWordFind = true;
                    break;
                }
            }

            if ($boolWordFind)
            {
                $strWord .= $text[$i];
                break;
            }

        case '!':
        case '?':
        case ';':
        case ':':
            $strWord .= $text[$i];

            if (!checkNextChars($text, $i, '</') && isset($text[$i + 1]) && ($text[$i + 1] == ' ' || $text[$i + 1] == '<'))
            {                
                
                if (!checkWordInArrayRegEx(mb_strtolower($strWord), $arrAbbreviation, '/(?<!([\wäÄüÜöÖß]))([REGEX_WORD])/ui') && !preg_match('/((((ht|f)tp(s?))\:\/\/)?(www.|[a-zA-Z].)[a-zA-Z0-9\-\.]+\.([a-zA-Z]{2,4})(\:[0-9]+)*(\/($|[a-zA-Z0-9\.\,\;\?\'\\\+&amp;%\$#\=~_\-]+))*)/', $strWord) && !preg_match('/([\w\-\.]+)@((\[([0-9]{1,3}\.){3}[0-9]{1,3}\]))/', $strWord) && !preg_match('/((\.)(\d+))/', $strWord)
                )
                {
                    $arrWords[]     = $strWord;
                    $arrSentences[] = trim(implode(' ', $arrWords));
                    $arrWords       = array();
                    $strWord        = '';
                }
            }
            break;
        case '>':
            $strWord .= $text[$i];

            if (checkLastChars($text, $i, '<br') || checkLastChars($text, $i, '</br') || checkLastChars($text, $i, '<br/') || checkLastChars($text, $i, '<br /') || checkLastChars($text, $i, '</h2') || checkLastChars($text, $i, '</p') || checkLastChars($text, $i, '</li') || checkLastChars($text, $i, '</ul')
            )
            {
                $arrWords[]     = $strWord;
                $arrSentences[] = trim(implode(' ', $arrWords));
                $arrWords       = array();
                $strWord        = '';
            }
            break;
        default:
            $strWord .= $text[$i];
            break;
        }
    }

    //if($boolDebug)
    //    exit;

    $arrWords[]     = $strWord;
    $arrSentences[] = trim(implode(' ', $arrWords));
    return $arrSentences;
}

/**
 * Überprüft anhand der $currPos ob die letzten Buchstaben in $text, $word sind.
 *
 * @param $text
 * @param $currPos
 * @param $word
 * @return bool
 */
function checkLastChars($text, $currPos, $word)
{
    $strWord    = "";
    $intWordLen = strlen($word);
    for ($i = $intWordLen; $i >= 1; $i--)
    {


        if (isset($text[$currPos - $i]))
            $strWord .= $text[$currPos - $i];
    }
    //echo "DEBUG: $strWord || $intWordLen || ".($strWord == $word ? 'Y' : 'N')."\n";
    return ($strWord == $word);
}

/**
 * Überprüft anhand der $currPos ob die nächsten Buchstaben in $text, $word sind.
 *
 * @param $text
 * @param $currPos
 * @param $word
 * @return bool
 */
function checkNextChars($text, $currPos, $word)
{    
    $strWord    = "";
    $intWordLen = strlen($word);
    for ($i = 1; $i <= $intWordLen; $i++)
    {
        if (isset($text[$currPos + $i]))
            $strWord .= mb_strtolower($text[$currPos + $i]);
    }

    //echo "DEBUG: $strWord || $intWordLen || ".($strWord == $word ? 'Y' : 'N')."\n";
    return ($strWord == $word);
}

/**
 * Speichert in die Datenbank welche funktion genutzt wurde.
 *
 * @param $function - function name
 * @param $objDB    mysqli
 */
function logUseInDB($function, $objDB)
{
    $objDB->query("INSERT INTO `editor_statistics` (`function`) VALUES ('$function');");
}

/**
 * 
 * 
 * @param type $word  - Wort nach dem gesucht werden soll
 * @param type $array - Array indem gesucht wird
 * @param type $regex - RegEx. [REGEX_WORD] ist hier ein Platzhalter durch das dann das wort ersetzt wird.
 */
function checkWordInArrayRegEx($word, $array, $regex = "/([REGEX_WORD])/")
{
    global $boolDebug;
    
    //if(!$boolDebug)
    //    return in_array($word, $array);
        
    foreach ($array as $strWordFromArray)
    {
        $strTempRegex = str_replace('[REGEX_WORD]', regex_real_string($strWordFromArray), $regex);
        if(preg_match($strTempRegex, $word))
            return true;
    }
    return false;
}
