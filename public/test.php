<?php
/**
 * Created by PhpStorm.
 * User: m.dhia
 * Date: 01.07.15
 * Time: 10:40
 */
exit;

// SMTP connection
ini_set('display_errors', 1);
error_reporting(E_ALL);

require("phpmailer/PHPMailerAutoload.php");
$objMailer = new PHPMailer;
$objMailer->IsSMTP();
$objMailer->SetLanguage("de");
$objMailer->CharSet    = 'utf-8';
$objMailer->Host       = "mail.dedi1612.your-server.de";
$objMailer->SMTPAuth   = true;
$objMailer->Username   = "no-reply@stellenanzeigen-texten.de";
$objMailer->Password   = "56he8K249UpE95sf";
$objMailer->SMTPSecure = "tls";

//if(!$objMailer->Send())
//{
//    echo "Die Nachricht konnte nicht versandt werden <p>";
//    echo "Mailer Error: " . $objMailer->ErrorInfo;
//    exit;
//}
//else
//    echo "ok";

// Database connection
$objDB = mysqli_connect('dedi1612.your-server.de', 'stellu_1_w', 'bWE12BCRpH1mRbqK', 'stellu_db1');
$objDB->set_charset("utf8");

$strSQL = "SELECT
                           *
                       FROM
                           users
                       WHERE
                           user_passwort_nocrypt != '' AND
                           user_confirmed = 0 AND
                           id IN (202,206,211,216,217,219,220,221,243,248,249)
                       ORDER BY
                           created_at DESC;";

$objResult = $objDB->query($strSQL);
$i         = 0;
while ($arrRow = $objResult->fetch_assoc())
{
    $objMailer->clearAddresses();
    $objMailer->clearAllRecipients();
    $objMailer->clearAttachments();
    $objMailer->clearBCCs();
    $objMailer->clearCCs();
    $objMailer->clearReplyTos();

    $objMailer->setFrom("no-reply@stellenanzeigen-texten.de", "TOOLBOX für Stellenanzeigen");
    $objMailer->addReplyTo('willkommen@aicovo.com');
    $objMailer->addAddress($arrRow['user_email'], $arrRow['user_vorname'] . " " . $arrRow['user_nachname']);
    $objMailer->IsHTML(true);

    $strSubject = "Ihre Registrierung bei stellenanzeigen-texten.de";
    $strContent = "Grüß Gott, " . (empty($arrRow['user_titel']) ? $arrRow['user_anrede'] : $arrRow['user_anrede'] . " " . $arrRow['user_titel']) . " " . $arrRow['user_vorname'] . " " . $arrRow['user_nachname'] . ",<br><br>bei einigen wenigen Anwender ist bei der kostenlosen Registrierung bei stellenanzeigen-texten.de ein Fehler unterlaufen.<br><br>Auch Sie waren davon betroffen, dass Ihre Registrierung nicht abgeschlossen werden konnte. Entschuldigung!<br><br>Bitte klicken Sie auf den folgenden Link, um Ihre Registrierung anzuschließen<br><br><a href='http://www.stellenanzeigen-texten.de/check/public/auth/registration/confirm-email/" . trim($arrRow['user_hashid']) . "' style='color: #F00;'>http://www.stellenanzeigen-texten.de/check/public/auth/registration/confirm-email/" . trim($arrRow['user_hashid']) . "</a>
<br><br>Sie können dann schon bald die kostenlosen Tools nutzen, um Ihre Stellenanzeigen zu optimieren.<br><br>Sollten Sie noch Fragen haben, sprechen Sie uns bitte an.<br><br><br>Mit freundlichen Grüßen<br /><br />Ihr Team von TOOLBOX für Stellenanzeigen<br />";

    include("mails/customAdminMail.php");
    $objMailer->Subject = $strSubject;
    $objMailer->Body    = $strEmail;

    if (!$objMailer->Send())
    {
        echo "Die Nachricht konnte nicht versandt werden <p>";
        echo $arrRow['user_email'] . " - Mailer Error: " . $objMailer->ErrorInfo;
        exit;
    }
    else
        echo $arrRow['user_email'] . " - ok<br>";
}
/*
echo "<h3>7 Tage</h3>";
sendNotify($objDB, false);
echo "<h3>3 Tage</h3>";
sendNotify($objDB, true);

function sendNotify($objDB, $boolFirstNotify = true)
{
    echo "<pre>";
    $strSQL             = "SELECT
                               *
                           FROM
                               users
                           WHERE
                               user_passwort_nocrypt != '' AND
                               user_confirmed = 0 AND
                               created_at+INTERVAL ".(!$boolFirstNotify ? 7 : 3)." DAY < NOW() AND
                               send_notify = ".(!$boolFirstNotify ? 1 : 0)."
                           ORDER BY
                               created_at DESC;";

    $objResult          = $objDB->query($strSQL);

    while($arrRow = $objResult->fetch_assoc())
    {
        echo "<pre>";
        print_r($arrRow);
        echo "</pre>";
    }

    $objResult->close();
    unset($obj, $strSQL, $arrUser);
}
*/

?>