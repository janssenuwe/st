<?php
$strEmail = "<br />
<table border='0' cellpadding='15' align='center' width='650' style='border: 1px solid #c0c0c0;'>
	<tr>
		<td><img src='http://www.stellenanzeigen-texten.de/images/allgemein/logo-toolbox-stellenanzeigen.png' alt='TOOLBOX für Stellenanzeigen'></td>
	</tr>
	<tr>
		<td style='font-size: 10.5pt; border-top: 1px solid #c0c0c0; color: #262626; font-family: 'Lucida Grande', 'Lucida Sans Unicode', Arial, Verdana, sans-serif;'>
<span style='font-size: 16pt; font-weight: bold; padding-bottom: 50px;'>Bitte bestätigen Sie Ihre E-Mail-Adresse</span>
<br /><br /><br />
Grüß Gott, ".(empty($auth->user_titel) ? $auth->user_anrede : $auth->user_anrede." ".$auth->user_titel)." ".utf8_encode($auth->user_vorname)." ".utf8_encode($auth->user_nachname).",
<br /><br /> 
mit der E-Mail-Adresse: ".utf8_encode($auth->user_email)." erfolgte vor wenigen Minuten eine kostenlose Anmeldung bei der TOOLBOX für Stellenanzeigen.
<br /><br /> 
Bitte bestätigen Sie Ihre E-Mail-Adresse über den folgenden Link:<br /> 
<a href='http://www.stellenanzeigen-texten.de/check/public/auth/registration/confirm-email/$uniqid' style='color: #F00;'>http://www.stellenanzeigen-texten.de/check/public/auth/registration/confirm-email/$uniqid</a>
<br /><br /> 
<i>Sollte der Link nicht funktionieren, kopieren Sie den vorstehenden Link bitte in die Adresszeile Ihres Browsers.</i>
<br /><br /> 
Solange Sie die Freischaltung nicht durchgeführt haben, können Sie sich nicht einloggen und die Funktionen nutzen.
<br /><br />
Wenn Sie sich nicht selbst registriert haben, ignorieren und löschen Sie bitte diese E-Mail.
<br /><br />
Vielen Dank!
<br /><br /><br />
Mit freundlichen Grüßen
<br /><br />
Ihr Team von TOOLBOX für Stellenanzeigen
<br /><br />	
		</td>
	</tr>
	<tr>
		<td style='border-top: 1px solid #c0c0c0; font-size: 9.5pt; color: #636363; font-family: 'Lucida Grande', 'Lucida Sans Unicode', Arial, Verdana, sans-serif;'>
TOOLBOX für Stellenanzeigen von <a href='http://www.stellenanzeigen-texten.de' target='_blank' style='color: #ff0000;'>www.stellenanzeigen-texten.de</a><br />
ist ein Produkt der aicovo gmbh<br />
Hechtseestraße 16 | 83022 Rosenheim | Deutschland, Bayern<br />
Telefon: +49 - 80 31 - 222 76 56 | Fax: +49 - 80 31 - 222 78 10<br />
E-Mail: <a href='mailto:willkommen@aicovo.com' target='_blank' style='color: #ff0000;'>willkommen@aicovo.com</a> | Internet: <a href='http://www.aicovo.com' target='_blank' style='color: #ff0000;'>www.aicovo.com</a><br />
<br />
<span>Impressum der aicovo gmbh<br />
Sitz: 83071 Stephanskirchen, Deutschland | Handelsregister:  HRB 13900 - Amtsgericht Traunstein<br />
Geschäftsführerin: Martina Haitzer | USt.-ID-Nummer: DE 219762137</span>
	
		</td>
	</tr>
</table>
<br />";