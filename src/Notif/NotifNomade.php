<?php


namespace App\Notif;

use App\Entity\Nomade;
use App\Entity\Proprietaire;
use Twig\Environment;

class NotifNomade
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


    public function notify(Nomade $nomade)
    {
        $message = (new \Swift_Message('Confirmation de création de compte Qleaq'))
            ->setFrom('qleaq@gmail.com')
            ->setTo('kenshin91cb@gmail.com')
//            ->setReplyTo($nomade->getEmail())
            ->setBody($this->renderer->render('emails/confirmation_nomade.html.twig',[
                'nomade' => $nomade
            ]), 'text/html' );
        $this->mailer->send($message);
    }

}