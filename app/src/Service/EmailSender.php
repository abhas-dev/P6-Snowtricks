<?php

declare(strict_types=1);

namespace App\Service;

use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Address;
use Symfony\Component\Mime\Email;

class EmailSender
{
    private MailerInterface $mailer;
    private string $senderEmail;
    private string $senderName;

    public function __construct(MailerInterface $mailer, string $senderEmail, string $senderName)
    {
        $this->mailer = $mailer;
        $this->senderEmail = $senderEmail;
        $this->senderName = $senderName;
    }

    /** @param array<mixed> $arguments */
    public function send(array $arguments): void
    {
        [
            'recipient_email' => $recipentEmail,
            'subject' => $subject,
            'html_template' => $htmlTemplate,
            'context' => $context
        ] = $arguments;

        $email = (new TemplatedEmail())
                    ->from(new Address($this->senderEmail, $this->senderName))
                    ->to($recipentEmail)
                    ->subject($subject)
                    ->htmlTemplate($htmlTemplate)
                    ->context($context)
            ;
        try{
            $this->mailer->send($email);
        } catch (TransportExceptionInterface $mailerException){
            throw $mailerException;
        }
    }

//    public function sendEmailConfirmation():void
//    {}

//    public function sendEmailConfirmation($viewPath, $user, $confirmationMmail)
//    {
////        $confirmationMmail =
////        (new TemplatedEmail())
////            ->from(new Address('admin@snowtricks.fr', 'Snowtricks'))
////            ->to($user->getEmail())
////            ->subject('Confirmation de votre adresse mail')
////            ->text('Merci de confirmer votre adresse mail ')
////            ->htmlTemplate('registration/confirmation_email.html.twig')
////            ;
//
////        (new Email())
////            ->from()
//        $this->mailer->send($confirmationMmail);
//    }
}