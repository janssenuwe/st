<?php
namespace Application\Util;

use Zend\Mail\Message;
use Zend\Mail;
use Zend\Mime\Message as MimeMessage;
use Zend\Mime\Part as MimePart;


/**
 * Class MailUtil
 * @package Application\Util
 */
class MailUtil
{
    const HOST_AND_DOMAIN_NAME = 'localhost.aicovo.com';
    const LOCAL_IP = '127.0.0.1';
    const MAIL_PORT = 25;

    /**
     * @description encapsulates preparation and submission of an e-mail
     * @author Uwe Janssen
     * @param $serviceLocator
     * @param $userEmail
     * @param $strEmail
     * @param $subject
     * @param null $cc
     * @param null $bcc
     */
    public static function sendMail($serviceLocator,$userEmail, $strEmail, $subject, $cc = null, $bcc = null) {

        $message    = new Mail\Message();
        $message
            // An wen es gesendet werden soll
            ->addTo($userEmail)
            // Von welcher Email es gesendet wird
            ->addFrom('no-reply@stellenanzeigen-texten.de', 'TOOLBOX für Stellenanzeigen')
            // An welche E-Mail Adresse kann man eine Antwort senden
            ->setReplyTo('willkommen@aicovo.com')
            // Betreff
            ->setSubject($subject);// $subject
        if ($cc != null) {
            $message->addCc($cc);
        }
        if ($bcc != null) {
            $message->addBcc($bcc);
        }
        // create a text message as well
        $text          = new MimePart($strEmail);
        $text->type = "text/plain";

        // STE-XXX: enclose html message in html tags
        $strEmail = "<html>" . $strEmail . "</html>";

        $html           = new MimePart($strEmail);
        $html->type     = "text/html";
        $html->charset  = "UTF-8";
        $body           = new MimeMessage();
        $transport      = $serviceLocator->get('mail.transport');

        //$body->addPart($html);
        $body->setParts(array($text,$html));
        $message->setEncoding("UTF-8");
        $message->setBody($body);

        $message->getHeaders()->get('content-type')->setType('multipart/alternative');

        $options = new Mail\Transport\SmtpOptions(
            array(
                'name'  => self::HOST_AND_DOMAIN_NAME,
                'host'  =>  self::LOCAL_IP,
                'port' => self::MAIL_PORT,
            )
        );
        $transport->setOptions($options);
        // Note: send() might throw an exception which is to be caught in calling method
        $transport->send($message);
    }
}