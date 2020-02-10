<?php


namespace App\Notif;


use App\Entity\Proprietaire;
use Twig\Environment;

class NotifProprio
{
    /**
     * NotifNomade constructor.
     * @param \Swift_Mailer $mailer
     * @param Environment $renderer
     */
    private $mailer;
    private $renderer;

    public function __construct(\Swift_Mailer $mailer, Environment $renderer)
    {
        $this->mailer = $mailer;
        $this->renderer = $renderer;
    }

    public function notifyProprio(Proprietaire $proprio)
    {
        $message = (new \Swift_Message('Confirmation de création de compte Qleaq'))
            ->setFrom('qleaq@gmail.com')
            ->setTo($proprio->getEmail())
            ->setReplyTo($proprio->getEmail())
            ->setBody($this->renderer->render('emails/proprio.html.twig',[
                'proprio' => $proprio
            ]), 'text/html' );
        $this->mailer->send($message);
    }
}