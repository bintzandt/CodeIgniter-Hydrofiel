<!DOCTYPE HTML>
<html>
<body>
<table cellspacing="0" cellpadding="0" width="100%" style="max-width: 800px;" align="center">
	<tr style="background: #ffab3a; height: 120px; padding: 7px;">
		<td><img src="<?= site_url( '/images/logomail.png' ) ?>" alt="Logo" height="100" style="float:left;"></td>
	</tr>
	<tr>
		<td valign="top" style="text-align: left;"><?= $content ?></td>
	</tr>
	<tr>
		<td style="color: #FFFFFF; background: #315265; padding: 7px;" height="50"></td>
	</tr>
	<tr align="center">
		<td><p>
				<a href="<?= site_url( '/mail/' . $hash ) ?>"><?= $engels ? "Message not readable?" : "Bericht niet goed leesbaar?" ?></a>
			</p></td>
	</tr>
</table>
</body>
</html>