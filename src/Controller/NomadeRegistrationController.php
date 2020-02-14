<?php

namespace App\Controller;

use App\Entity\Nomade;
use App\Entity\User;
use App\Form\LostNomadePasswordType;
use App\Form\NomadeResetPasswordFormType;
use App\Form\PasswordResetFormType;
use App\Form\RegistrationFormType;
use App\Form\UsernameFormType;
use App\Notif\NotifNomade;
use App\Repository\NomadeRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;


class NomadeRegistrationController extends AbstractController
{
    /**
     * @Route("/inscription-locataire", name="app_register")
     *
     * @param Request                      $request         Pour que le formulaire récupère les données POST
     * @param UserPasswordEncoderInterface $passwordEncoder Pour hasher le mot de passe de l'utilisateur
     * @param EntityManagerInterface       $entityManager   Pour enregistrer l'utilisateur en base de données
     * @param MailerInterface              $mailer          Pour envoyer un email de confirmation
     */
    public function register(Request $request,
                             UserPasswordEncoderInterface $passwordEncoder,
                             EntityManagerInterface $entityManager,
                             NotifNomade $notifNomade,
                             MailerInterface $mailer): Response
    {
//        $user = new Nomade();
        $form = $this->createForm(RegistrationFormType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            /**
             * @var Nomade $user
             */
            $user = $form->getData();
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

            $notifNomade->notify($user);

//            $this->sendConfirmationEmail($mailer, $user);

            $this->addFlash('success', 'Votre compte a bien été créée');
            $this->addFlash('info', 'Vous devrez confirmez votre compte, un lien vous a été envoyé par email.');

            return $this->redirectToRoute('login_nomade');
        }

        return $this->render('registration/register.html.twig', [
            'registrationForm' => $form->createView(),
        ]);
    }






    /**
     * Confirmation du compte après inscription (lien envoyé par email)
     * @Route("/user-confirmation/{id}/{token}", name="user_confirmation")
     *
     * @param Nomade                 $user          L'utilisateur qui tente de confirmer son compte
     * @param                        $token         Le jeton à vérifier pour confirmer le compte
     * @param EntityManagerInterface $entityManager Pour mettre à jour l'utilisateur
     */

    public function confirmAccount(Nomade $user, $token, EntityManagerInterface $entityManager)
    {
        // L'utilisateur a déjà confirmé son compte
        if ($user->getIsConfirmed()) {
            $this->addFlash('warning', 'Votre compte est déjà confirmé, vous pouvez vous connecter.');
            return $this->redirectToRoute('login_nomade');
        }

        // Le jeton ne correspond pas à celui de l'utilisateur
        if ($user->getSecurityToken() !== $token) {
            $this->addFlash('danger', 'Le jeton de sécurité est invalide.');
            return $this->redirectToRoute('login_nomade');
        }

        // Le jeton est valide: mettre à jour le jeton et confirmer le compte
        $user->setIsConfirmed(true);
        $user->renewToken();

        $entityManager->persist($user);
        $entityManager->flush();

        $this->addFlash('success', 'Votre compte est confirmé, vous pouvez vous connecter.');
        return $this->redirectToRoute('login_nomade');
    }







    /**
     * Demander un renvoi du mail de confirmation
     * @Route("/send-confirmation", name="send_confirmation")
     *
     * @param Request         $request              Pour le formulaire
     * @param NomadeRepository $nomadeRepository    Pour rechercher l'utilisateur
     * @param MailerInterface $mailer               Pour renvoyer l'email de confirmation
     */
    public function sendConfirmation(Request $request, NomadeRepository $nomadeRepository, MailerInterface $mailer)
    {
        // Création d'un formulaire demandant un email/pseudo
        $usernameForm = $this->createForm(UsernameFormType::class);
        $usernameForm->handleRequest($request);

        if ($usernameForm->isSubmitted() && $usernameForm->isValid()) {
            $username = $usernameForm->getData()['username'];

            // Récupérer un utilisateur par email ou pseudo
            // Note: vous pouviez choisir de récupérer par seulement l'email ou seulement le pseudo
            $user = $nomadeRepository->findOneBy(['email' => $username])
                ?? $nomadeRepository->findOneBy(['pseudo' => $username]);

            if ($user === null) {
                $this->addFlash('danger', 'Utilisateur inconnu');

            } elseif ($user->getIsConfirmed()) {
                $this->addFlash('warning', 'Votre compte est déjà confirmé.');
                return $this->redirectToRoute('login_nomade');

            } else {
                // Renvoi de l'email (voir plus bas la méthode sendConfirmationEmail() )
                $this->sendConfirmationEmail($mailer, $user);
                $this->addFlash('info', 'Un email de confirmation vous a été renvoyé.');
                return $this->redirectToRoute('login_nomade');
            }
        }

        return $this->render('emails/send_confirmation_nomade.html.twig', [
            'username_form' => $usernameForm->createView()
        ]);
    }








    /**
     * Demander un lien de réinitialisation du mot de passe
     * @Route("/lost-password", name="lost_password")
     *
     * @param Request         $request          Pour le formulaire
     * @param NomadeRepository  $userRepository   Pour rechercher l'utilisateur
     * @param MailerInterface $mailer           Pour envoyer l'email de réinitialisation
     */
    public function lostPassword(Request $request, NomadeRepository $nomadeRepository, NotifNomade $notifNomade)
    {

        $lostNomadePasswordForm = $this->createForm(LostNomadePasswordType::class);
        $lostNomadePasswordForm->handleRequest($request);
//        dump($lostNomadePasswordForm);
//        die();
        if ($lostNomadePasswordForm->isSubmitted() && $lostNomadePasswordForm->isValid()) {
            $nomade = $lostNomadePasswordForm->getData()['email'];
//            dump($nomade);
            $user = $nomadeRepository->findOneBy(['email' => $nomade]);
//            dump($user);die();
            if ($user === null) {
                $this->addFlash('danger', 'Cet adresse Email n\'est pas enregistrée');

//                dump($user);die();
            } else {

//                dump($user);die();

                $notifNomade->lostPasswordNomade($user);

                $this->addFlash('info', 'Un email de réinitialisation vous a été renvoyé.');
                return $this->redirectToRoute('login_nomade');

            }
        }

        return $this->render('nomade/lost_password.html.twig', [
            'lost_nomade_password_form' => $lostNomadePasswordForm->createView()
        ]);
    }






    /**
     * Code de l'envoi d'email de confirmation
     * Le code a été refactorisé en une méthode
     * car il est le même dans les méthodes register() & sendConfirmation()
     */
//    private function sendConfirmationEmail(MailerInterface $mailer, Nomade $user)
//    {
//        // Création de l'email de confirmation
//        $email = (new TemplatedEmail())
//            ->from('admin@qleaq.fr')
//            ->to($user->getEmail())
//            ->subject('Confirmation du compte Locataire | QLEAQ')
//            /*
//             * Indiquer le template de l'email puis les variables nécessaires
//             */
//            ->htmlTemplate('emails/confirmation.html.twig')
//            ->context([
//                'user' => $user
//            ])
//        ;
////            dump($email);die();
//        // Envoi de l'email
//        $mailer->send($email);
//    }



    /**
     * Réinitialiser le mot de passe
     * @Route("/reset-password/{id}/{token}", name="reset_password")
     *
     * @param User                          $user            L'utilisateur qui souhaite réinitialiser son mot de passe
     * @param                               $token           Le jeton à vérifier pour la réinitialisation
     * @param Request                       $request         Pour le formulaire de réinitialisation
     * @param EntityManagerInterface        $entityManager   Pour mettre à jour l'utilisateur
     * @param UserPasswordEncoderInterface $passwordEncoder Pour hasher le nouveau mot de passe
     */
    public function resetPassword(
        Nomade $user,
        $token,
        Request $request,
        EntityManagerInterface $entityManager,
        UserPasswordEncoderInterface $passwordEncoder
    ) {
        // Le jeton ne correspond pas à celui de l'utilisateur
        if ($user->getSecurityToken() !== $token) {
            $this->addFlash('danger', 'Le jeton de sécurité est invalide.');
            return $this->redirectToRoute('login_nomade');
        }

        // Création du formulaire de réinitialisation du mot de passe
        $resetForm = $this->createForm(NomadeResetPasswordFormType::class);
        $resetForm->handleRequest($request);

        if ($resetForm->isSubmitted() && $resetForm->isValid()) {
            $password = $resetForm->get('plainPassword')->getData();

//            dump($password);

            $user->setPassword($passwordEncoder->encodePassword($user, $password));
            $user->renewToken();


            // Mise à jour de l'entité en BDD

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($user);
            $entityManager->flush();

            // Ajout d'un message flash
            $this->addFlash('success', 'Votre mot de passe a bien été modifié.');
            return $this->redirectToRoute('login_nomade');
        }

        return $this->render('nomade/reset_password_form.html.twig', [
            'reset_form' => $resetForm->createView()
        ]);
    }





}
