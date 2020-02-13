<?php

namespace App\Controller;

use App\Entity\Proprietaire;
use App\Form\ProprioRegistrationType;
use App\Form\UsernameFormType;
use App\Notif\NotifProprio;
use App\Repository\ProprietaireRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class ProprioRegistrationController extends AbstractController
{
    /**
     * @Route("/inscription-proprietaire", name="proprio_register")
     *
     * @param Request                      $request         Pour que le formulaire récupère les données POST
     * @param UserPasswordEncoderInterface $passwordEncoder Pour hasher le mot de passe de l'utilisateur
     * @param EntityManagerInterface       $entityManager   Pour enregistrer l'utilisateur en base de données
     * @param MailerInterface              $mailer          Pour envoyer un email de confirmation
     */
    public function register(Request $request,
                             UserPasswordEncoderInterface $passwordEncoder,
                             EntityManagerInterface $entityManager,
                             NotifProprio $notifProprio,
                             MailerInterface $mailer): Response
    {
//        $proprio = new Proprietaire();
        $form = $this->createForm(ProprioRegistrationType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            /**
             * @var Proprietaire $user
             */
            $user = $form->getData();


            $user->setRoles(
              ["ROLE_PROPRIO"]
            );

            // encode the plain password
            $user->setPassword(
                $passwordEncoder->encodePassword(
                    $user,
                    $form->get('plainPassword')->getData()
                )
            );

//            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($user);
            $entityManager->flush();

            $notifProprio->notifyProprio($user);

//            $this->sendConfirmationEmail($mailer, $user);

            $this->addFlash('success', 'Votre compte a bien été créée');
            $this->addFlash('info', 'Vous devrez confirmez votre compte, un lien vous a été envoyé par email.');

            return $this->redirectToRoute('login_proprietaire');
        }

        return $this->render('registration/proprio_register.html.twig', [
            'ProprioRegistrationForm' => $form->createView(),
        ]);
    }






    /**
     * Confirmation du compte après inscription (lien envoyé par email)
     * @Route("/{id}/{token}", name="proprio_confirmation")
     *
     * @param Proprietaire           $user          L'utilisateur qui tente de confirmer son compte
     * @param                        $token         Le jeton à vérifier pour confirmer le compte
     * @param EntityManagerInterface $entityManager Pour mettre à jour l'utilisateur
     */

    public function confirmAccount(Proprietaire $user, $token, EntityManagerInterface $entityManager)
    {
        // L'utilisateur a déjà confirmé son compte
        if ($user->getIsConfirmed()) {
            $this->addFlash('warning', 'Votre compte est déjà confirmé, vous pouvez vous connecter.');
            return $this->redirectToRoute('login_proprietaire');
        }

        // Le jeton ne correspond pas à celui de l'utilisateur
        if ($user->getSecurityToken() !== $token) {
            $this->addFlash('danger', 'Le jeton de sécurité est invalide.');
            return $this->redirectToRoute('login_proprietaire');
        }

        // Le jeton est valide: mettre à jour le jeton et confirmer le compte
        $user->setIsConfirmed(true);
        $user->renewToken();

        $entityManager->persist($user);
        $entityManager->flush();

        $this->addFlash('success', 'Votre compte est confirmé, vous pouvez vous connecter.');
        return $this->redirectToRoute('login_proprietaire');
    }







    /**
     * Demander un renvoi du mail de confirmation
     * @Route("/send-confirmation", name="send_confirmation")
     *
     * @param Request         $request                          Pour le formulaire
     * @param ProprietaireRepository $proprietaireRpository     Pour rechercher l'utilisateur
     * @param MailerInterface $mailer                           Pour renvoyer l'email de confirmation
     */
    public function sendConfirmation(Request $request, ProprietaireRepository $proprietaireRepository, MailerInterface $mailer)
    {
        // Création d'un formulaire demandant un email/pseudo
        $usernameForm = $this->createForm(UsernameFormType::class);
        $usernameForm->handleRequest($request);

        if ($usernameForm->isSubmitted() && $usernameForm->isValid()) {
            $username = $usernameForm->getData()['username'];

            // Récupérer un utilisateur par email ou pseudo
            // Note: vous pouviez choisir de récupérer par seulement l'email ou seulement le pseudo
            $user = $proprietaireRepository->findOneBy(['email' => $username]);

            if ($user === null) {
                $this->addFlash('danger', 'Compte propriétaire inconnu');

            } elseif ($user->getIsConfirmed()) {
                $this->addFlash('warning', 'Votre compte est déjà confirmé.');
                return $this->redirectToRoute('login_proprietaire');

            } else {
                // Renvoi de l'email (voir plus bas la méthode sendConfirmationEmail() )
                $this->sendConfirmationEmail($mailer, $user);
                $this->addFlash('info', 'Un email de confirmation vous a été renvoyé.');
                return $this->redirectToRoute('login_proprietaire');
            }
        }

        return $this->render('emails/send_confirmation_proprietaire.html.twig', [
            'username_form' => $usernameForm->createView()
        ]);
    }
}
