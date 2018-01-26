<?php
$strEmail = "<br />
<table border='0' cellpadding='15' align='center' width='650' style='border: 1px solid #c0c0c0;'>
	<tr>
		<td><img src='http://www.stellenanzeigen-texten.de/images/allgemein/logo-toolbox-stellenanzeigen.png' alt='TOOLBOX für Stellenanzeigen'></td>
	</tr>
	<tr>
		<td style='font-size: 10.5pt; border-top: 1px solid #c0c0c0; color: #262626; font-family: 'Lucida Grande', 'Lucida Sans Unicode', Arial, Verdana, sans-serif;'>
<span style='font-size: 16pt; font-weight: bold;'>Neue Experten-Check-Anfrage</span>
<br /><br /><br />
<table>
	<tr>
		<td><b>Kategorie</b><td>
		<td rowspan='2'>&nbsp;&nbsp;&nbsp;</td>
		<td>".$arrBugTypes[$intType]."<td>
	</tr>
	<tr>
		<td><b>Anmerkung</b><td>
		<td>".utf8_encode($strCommit)."<td>
	</tr>
</table>
<br /><br />
<span style='font-size: 12.5pt; font-weight: bold;'>Daten des Nutzers</span>
<br/><br/>
<table>
	<tr>
		<td><b>Firma</b><td>
		<td rowspan='5'>&nbsp;&nbsp;&nbsp;</td>
		<td>".utf8_encode($arrUser->user_firmenname)."<td>
	</tr>
	<tr>
		<td><b>Name</b><td>
		<td>".(empty($arrUser->user_titel) ? $arrUser->user_anrede : $arrUser->user_anrede." ".$arrUser->user_titel)." ".utf8_encode($arrUser->user_vorname)." ".utf8_encode($arrUser->user_nachname)."<td>
	</tr>
	<tr>
		<td><b>E-Mail</b><td>
		<td>".$arrUser->user_email."<td>
	</tr>
	<tr>
		<td><b>Telefon</b><td>
		<td>+".$arrUser->user_laendervorwahl." ".$arrUser->user_vorwahl." ".$arrUser->user_telefon."<td>
	</tr>
	<tr>
		<td><b>Adresse</b><td>
		<td>".utf8_encode($arrUser->user_strasse)." ".$arrUser->user_hausnummer.", ".$arrUser->user_plz." ".utf8_encode($arrUser->user_ort).", ".utf8_encode($arrUser->user_land)."<td>
	</tr>
</table>
<br/><br/>
<span style='font-size: 12.5pt; font-weight: bold;'>Hochgeladene Anzeige</span><br />
[LINK ZUR DATEI]
<br /><br />	
		</td>
	</tr>
</table>
<br />";