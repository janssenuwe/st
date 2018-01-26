<?php

$strEmail = "<br />
<table border='0' cellpadding='15' align='center' width='650' style='border: 1px solid #c0c0c0;'>
	<tr>
		<td><img src='http://www.stellenanzeigen-texten.de/images/allgemein/logo-toolbox-stellenanzeigen.png' alt='TOOLBOX für Stellenanzeigen'></td>
	</tr>
	<tr>
		<td style='font-size: 10.5pt; border-top: 1px solid #c0c0c0; color: #262626; font-family: 'Lucida Grande', 'Lucida Sans Unicode', Arial, Verdana, sans-serif;'>
<span style='font-size: 16pt; font-weight: bold; padding-bottom: 50px;'>Ihre Anmerkung zum Text-Optimierer</span>
<br /><br /><br />
Grüß Gott, ".(empty($arrUser->user_titel) ? $arrUser->user_anrede : $arrUser->user_anrede." ".$arrUser->user_titel)." ".utf8_encode($arrUser->user_vorname)." ".utf8_encode($arrUser->user_nachname).",
<br /><br /> 
vielen Dank für Ihre Anmerkung zum Text-Optimierer. Die stetige Weiterentwicklung dieses Tools ist uns wichtig.
<br /><br /> 
Wir werden uns schnellstmöglich bei Ihnen melden.
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