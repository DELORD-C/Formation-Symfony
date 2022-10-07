<?php

namespace App\Custom;

use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;

class Mailer
{
    private MailerInterface $mail;

    function __construct(MailerInterface $mail) {
        $this->mail = $mail;
    }

    /**
     * @throws TransportExceptionInterface
     */
    function sendMail(String $from, String $to, String $subject, String $body) {
        $email = (new Email())
            ->from($from)
            ->to($to)
            ->subject($subject)
            ->text($body)
            ->html($body);

        $this->mail->send($email);
    }
}