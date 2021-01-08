<?php

namespace App\Services;

use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class Mailer
{
    /**
     * @var MailerInterface
     */
    private $mailer;

    /**
     * @var UrlGeneratorInterface
     */
    private $router;
    /**
     * @var ParameterBagInterface
     */
    private $parameterBag;

    public function __construct(
        MailerInterface $mailer,
        UrlGeneratorInterface $router,
        ParameterBagInterface $parameterBag
    ) {
        $this->mailer = $mailer;
        $this->router = $router;
        $this->parameterBag = $parameterBag;
    }

    public function sendPasswordMail(string $email, string $token) : void
    {
        $url = $this->router->generate(
            'app_reset_password',
            array('token' => $token),
            UrlGeneratorInterface::ABSOLUTE_URL
        );
        $message = (new Email())
            ->from($this->parameterBag->get('mailer_sender'))
            ->to($email)
            ->subject('Réinitialiser votre mot de passe')
            ->text("Cliquez sur le lien suivant pour réinitialiser votre mot de passe : " . $url);
        $this->mailer->send($message);
    }

    public function sendConfirmationEmail($email, string $token) : void
    {
        $url = $this->router->generate(
            'app_confirm_account',
            array('token' => $token),
            UrlGeneratorInterface::ABSOLUTE_URL
        );
        $message = (new Email())
            ->from($this->parameterBag->get('mailer_sender'))
            ->to($email)
            ->subject('Valider votre compte')
            ->text("Cliquez sur le lien suivant pour valider votre compte : " . $url);
        $this->mailer->send($message);
    }
}
