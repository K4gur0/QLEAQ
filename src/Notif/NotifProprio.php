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

    public function confirmationProprio(Proprietaire $proprio)
    {
        $message = (new \Swift_Message('Confirmation de création de compte Propriétaire Qleaq'))
            ->setFrom('qleaq@gmail.com')
            /**
             * Ci dessous entrez l'adresse administrateur pour gérer les inscriptions Propriétaires
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
             * Ci dessous entrez l'adresse administrateur pour gérer les inscriptions Propriétaires
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

    public function demandePublicationAnnonce(Proprietaire $proprio, Annonce $annonce)
    {
        // Création de l'email de réinitialisation
        $message = (new \Swift_Message('Demande de publication d\'Annonce'))
            ->setFrom('admin@qleaq.fr')
            ->setTo('kenshin91cb@gmail.com')
            ->setBody($this->renderer->render('emails/demande_publication_annonce.html.twig',[
                'proprio' => $proprio,
                'annonce' => $annonce
            ]), 'text/html' );
        $this->mailer->send($message);

        $messageSecondaire = (new \Swift_Message('Demande de publication d\'Annonce'))
            ->setFrom('admin@qleaq.fr')
            ->setTo('kenshin91cb@gmail.com')
            ->setBody($this->renderer->render('emails/accuse_demande_publication_annonce.html.twig',[
                'proprio' => $proprio,
                'annonce' => $annonce
            ]), 'text/html' );
        $this->mailer->send($messageSecondaire);
    }


    public function confirmPublication(Proprietaire $proprio, Annonce $annonce){
        // Création de l'email de réinitialisation
        $message = (new \Swift_Message('Validation pour la publication d\'annonce'))
            ->setFrom('admin@qleaq.fr')
            ->setTo('kenshin91cb@gmail.com')
            ->setBody($this->renderer->render('emails/validation_publication.html.twig',[
                'proprio' => $proprio,
                'annonce' => $annonce
            ]), 'text/html' );
        $this->mailer->send($message);
    }


}