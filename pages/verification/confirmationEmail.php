<?php
require_once '../../lib/vendor/autoload.php';

use Symfony\Component\Mailer\Transport;
use Symfony\Component\Mailer\Mailer;
use Symfony\Component\Mime\Email;
$transport = Transport::fromDsn('smtp://localhost:1025');
$mailer = new Mailer($transport);
$email = (new Email())
->from('babel@gmail.com')
->to('desti.nataire@quelquepart.com')
//->cc('cc@exemple.com')
//->bcc('bcc@exemple.com')
//->replyTo('replyto@exemple.com')
//->priority(Email::PRIORITY_HIGH)
->subject('Concerne : Envoi de mail')
->text('Un peu de texte')
->html('<h1>Un peu de html</h1>');
$result = $mailer->send($email);
if ($result==null) {
echo "Un mail a été envoyé ! <a href='http://localhost:8025'>voir le
mail</a>";
} else {
echo "Un problème lors de l'envoi du mail est survenu";
}