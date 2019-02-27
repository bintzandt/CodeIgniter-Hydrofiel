<!DOCTYPE HTML>
<html>
<body>
<div style="width: 100%; margin: auto;" align="center">
	<table cellspacing="0" cellpadding="0" width="100%">
		<tr style="background: #ffab3a; height: 120px; padding: 7px;">
			<td><img src="<?= site_url( '/images/logomail.png' ) ?>" alt="Logo" height="100" style="float:left;"></td>
		</tr>
		<tr>
			<td valign="top" style="text-align: left;">
				<p>
					Dear <?= $voornaam ?>,<br>
					<br>
					I've processed your application and from today you're officially a member! This means that you'll
					have
					access to the Hydrofiel website. With <a href="<?= site_url( '/inloggen/reset/' . $recovery ) ?>">this</a>
					link you'll be able to make an account for <a href="<?= site_url( '' ) ?>">www.hydrofiel.nl</a>.
					Among other
					things, you can view the calendar and sign yourself up for events/tournaments/competitions on the
					website.
					You can also find the Rules and Regulations, the Articles of Association, and other useful
					information.<br>
					<br>
					From now, you will also receive the newsletter and any other important e-mails, and you'll be kept
					up-to-date with everything going on.<br>
					<br>
					Practice sessions are on Tuesday and Thursday evening, and they're split accordingly:<br>
				<ul>
					<li>20.00-21.00h: Swimming</li>
					<li>21.00-22.30h: Water polo</li>
				</ul>
				<?php if( $events !== FALSE ) { ?>
					<br>
					<b>Upcoming events:</b><br>
					<ul>
						<?php
						foreach( $events as $event ) { ?>
							<li><a href="<?= site_url( '/agenda/id/' . $event->event_id ) ?>"><?= $event->en_naam ?>
									op <?= date_format( date_create( $event->van ), 'd-m-Y H:i' ) ?></a></li>
						<?php } ?>
					</ul>
				<?php } ?>
				In the attachment you'll find our welcoming letter. If you have any questions, please ask away. This can
				be done in person or through an e-mail to <a href="mailto:secretaris@hydrofiel.nl">secretaris@hydrofiel.nl</a>.<br>
				<br>
				Don't forget to like us on Facebook: <a href="https://www.facebook.com/Hydrofiel/">https://www.facebook.com/Hydrofiel/</a><br>
				<br>
				See you in the pool!<br>
				<br>
				With Hard and Wet greetings,<br>
				<br>
				Vincent van 't Hof<br>
				<i>Secretary 2018-2019<br>
					N.S.Z.&W.V. Hydrofiel</i>
				</p>
			</td>
		</tr>
		<tr>
			<td style="color: #FFFFFF; background: #315265; padding: 7px;" height="50"></td>
		</tr>
	</table>
</div>
</body>
</html>