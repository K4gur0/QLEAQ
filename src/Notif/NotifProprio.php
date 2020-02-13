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
        $message = (new \Swift_Message('Demande de création de compte Propriétaire Qleaq'))
            ->setFrom('qleaq@gmail.com')
            /**
             * Ci dessous entrez l'adresse administrateur pour gérer les inscriptions Propriétaires
             */
            ->setTo('kenshin91cb@gmail.com')
//            ->setReplyTo($proprio->getEmail())
            ->setBody($this->renderer->render('emails/confirmation_proprio.html.twig',[
                'proprio' => $proprio
            ]), 'text/html' );
        $this->mailer->send($message);
    }
}