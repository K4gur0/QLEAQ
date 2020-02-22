<?php


namespace App\Notif;

use App\Entity\Annonce;
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

    public function notifyProprio(Proprietaire $proprio, $password)
    {
        /**
         * Ci dessous entrez l'adresse administrateur pour recevoir les demandes d'inscriptions Propriétaires : ->setTo('admin@qleaq.com')
         * Email : admin@qleaq.com
         */
        $message = (new \Swift_Message('Demande de création de compte Propriétaire'))
            ->setFrom('qleaq@gmail.com')

            ->setTo('kenshin91cb@gmail.com')
//            ->setReplyTo($proprio->getEmail())
            ->setBody($this->renderer->render('emails/confirmation_proprio.html.twig',[
                'proprio' => $proprio
            ]), 'text/html' );
        $this->mailer->send($message);

        /**
         * Ci dessous récupérer l'adresse du Propriétaire pour qu'il reçoive un accusé de reception lors de sa demande : ->setTo($proprio->getEmail())
         * Email : $proprio->getEmail()
         */
        $messageSecondaire = (new \Swift_Message('Votre demande de création de compte Propriétaire'))
            ->setFrom('qleaq@gmail.com')

            ->setTo('kenshin91cb@gmail.com')
//            ->setReplyTo($proprio->getEmail())
            ->setBody($this->renderer->render('emails/accusee_demande_proprio.html.twig',[
                'proprio' => $proprio,
                'password' => $password
            ]), 'text/html' );
        $this->mailer->send($messageSecondaire);
    }


    public function confirmationProprio(Proprietaire $proprio)
    {
        $message = (new \Swift_Message('Confirmation de création de compte Propriétaire Qleaq'))
            ->setFrom('qleaq@gmail.com')
            /**
             * Ci dessous récupérer l'adresse du Propriétaire pour qu'il reçoive la réponse suite à validation des admin Qleaq : ->setTo($proprio->getEmail())
             * Email : $proprio->getEmail()
             */
            ->setTo('kenshin91cb@gmail.com')
            ->setReplyTo($proprio->getEmail())
            ->setBody($this->renderer->render('emails/validation_compte_proprio.html.twig',[
                'proprio' => $proprio
            ]), 'text/html' );
        $this->mailer->send($message);
    }

    public function refusProprio(Proprietaire $proprio)
    {
        $message = (new \Swift_Message('Refus de création de compte Propriétaire Qleaq'))
            ->setFrom('qleaq@gmail.com')
            /**
             * Ci dessous récupérer l'adresse du Propriétaire pour qu'il reçoive la réponse suite à refus des admin Qleaq : ->setTo($proprio->getEmail())
             * Email : $proprio->getEmail()
             */
            ->setTo('kenshin91cb@gmail.com')
            ->setReplyTo($proprio->getEmail())
            ->setBody($this->renderer->render('emails/refus_compte_proprio.html.twig',[
                'proprio' => $proprio
            ]), 'text/html' );
        $this->mailer->send($message);
    }

    public function lostPasswordProprio(Proprietaire $proprio)
    {
        // Création de l'email de réinitialisation
        $message = (new \Swift_Message('Réinitialisation de votre mot de passe'))
            ->setFrom('admin@qleaq.fr')
            ->setTo('kenshin91cb@gmail.com')
            ->setBody($this->renderer->render('emails/reset_password_proprio.html.twig',[
                'proprio' => $proprio
            ]), 'text/html' );
        $this->mailer->send($message);

    }

    public function NewAnnonce(Proprietaire $proprio, Annonce $annonce)
    {
        // Création de l'email de réinitialisation
        $message = (new \Swift_Message('Annonce créée'))
            ->setFrom('admin@qleaq.fr')
            ->setTo('kenshin91cb@gmail.com')
            ->setBody($this->renderer->render('emails/annonce_cree.html.twig', [
                'proprio' => $proprio,
                'annonce' => $annonce
            ]), 'text/html');
        $this->mailer->send($message);
    }

}