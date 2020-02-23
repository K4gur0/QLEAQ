<?php

namespace App\Controller;


use App\Entity\Proprietaire;
use App\Form\LostNomadePasswordType;
use App\Form\NomadeResetPasswordFormType;
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
                             NotifProprio $notifProprio): Response
    {

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

            $password = $form->get('plainPassword')->getData();
//            dd($password);

//            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($user);
            $entityManager->flush();

            $notifProprio->notifyProprio($user, $password);

//            $this->sendConfirmationEmail($mailer, $user);

            $this->addFlash('info', 'Votre demande est en cours de traitement, vous serez prochainement informé par Mail.');

            return $this->redirectToRoute('login_proprietaire');
        }

        return $this->render('registration/proprio_register.html.twig', [
            'ProprioRegistrationForm' => $form->createView(),
        ]);
    }






    /**
     * Confirmation du compte après inscription (lien envoyé par email)
     * @Route("{id}/confirm/{token}", name="proprio_confirmation")
     *
     * @param Proprietaire           $user          L'utilisateur qui tente de confirmer son compte
     * @param                        $token         Le jeton à vérifier pour confirmer le compte
     * @param EntityManagerInterface $entityManager Pour mettre à jour l'utilisateur
     */

    public function confirmAccount(Proprietaire $user,
                                   $token,
                                   EntityManagerInterface $entityManager,
                                   NotifProprio $notifProprio)
    {

        $refus = $user->setRefus(1);
//        dd($user->getRefus());

        if($refus !== 2 ){

            // L'utilisateur a déjà confirmé son compte
            if ($user->getIsConfirmed()) {
                $this->addFlash('warning', 'Le compte Propriétaire est déjà confirmé !');
                return $this->redirectToRoute('admin_login');
            }

            // Le jeton ne correspond pas à celui de l'utilisateur
            if ($user->getSecurityToken() !== $token) {

                $this->addFlash('danger', 'Le jeton est invalide');
                return $this->redirectToRoute('admin_login');
            }

            // Le jeton est valide: mettre à jour le jeton et confirmer le compte
            $user->setIsConfirmed(true);
            $user->renewToken();

            $entityManager->persist($user);
            $entityManager->flush();

            $notifProprio->confirmationProprio($user);

            $this->addFlash('success', 'Le compte Propriétaire : ' . $user->getRaisonSocial() . ' est maintenant confirmé. L\'information vient dêtre envoyer à l\'adresse mail : ' . $user->getEmail());
            return $this->redirectToRoute('admin_login');

        }else{
            $this->addFlash('warning', 'Ce compte à déjà été refusé et ne peut être confirmé');
            return $this->redirectToRoute('admin_login');
        }

    }





    /**
     * Refus de création du compte Propriétaire
     * @Route("{id}/refus/{refusToken}", name="proprio_refus")
     *
     * @param Proprietaire           $user          L'utilisateur qui tente de confirmer son compte
     * @param                        $refusToken         Le jeton à vérifier pour confirmer le compte
     * @param EntityManagerInterface $entityManager Pour mettre à jour l'utilisateur
     */

    public function refusAccount(Proprietaire $user,
                                   $refusToken,
                                   EntityManagerInterface $entityManager,
                                   NotifProprio $notifProprio)
    {

        $confirm = $user->getIsConfirmed();

        if ($confirm == false){

            // Le jeton ne correspond pas à celui de l'utilisateur
            if ($user->getRefusToken() !== $refusToken) {
                $this->addFlash('danger', 'Le jeton de sécurité est invalide.');
                return $this->redirectToRoute('admin_login');
            }

            // Le jeton est valide: mettre à jour le jeton et confirmer le compte

            $user->renewRefusToken();

            $user->setRefus(2);

            $entityManager->persist($user);
            $entityManager->flush();

            $notifProprio->refusProprio($user);

            $this->addFlash('warning', 'La création du compte Porpriétaire ' . $user->getRaisonSocial() . ' a été refusé');
            return $this->redirectToRoute('admin_login');
        }else{
            $this->addFlash('warning', 'Ce compte est déjà validé et ne peut être refusé');
            return $this->redirectToRoute('admin_login');
        }



    }






    /**
     * Demander un lien de réinitialisation du mot de passe
     * @Route("/lost-password-proprio", name="lost_password_proprio")
     *
     * @param Request         $request          Pour le formulaire
     * @param ProprietaireRepository  $userRepository   Pour rechercher l'utilisateur
     * @param MailerInterface $mailer           Pour envoyer l'email de réinitialisation
     */
    public function lostPassword(Request $request, ProprietaireRepository $proprietaireRepository, NotifProprio $notifProprio)
    {

        $lostPasswordProprioForm = $this->createForm(LostNomadePasswordType::class);
        $lostPasswordProprioForm->handleRequest($request);

        if ($lostPasswordProprioForm->isSubmitted() && $lostPasswordProprioForm->isValid()) {
            $proprio = $lostPasswordProprioForm->getData()['email'];

            $user = $proprietaireRepository->findOneBy(['email' => $proprio]);

            if ($user === null) {
                $this->addFlash('danger', 'Cet adresse Email n\'est pas enregistrée');


            } else {


                $notifProprio->lostPasswordProprio($user);

                $this->addFlash('info', 'Un email de réinitialisation vous a été envoyé.');
                return $this->redirectToRoute('login_proprietaire');

            }
        }

        return $this->render('proprietaire/lost_password.html.twig', [
            'lost_proprio_password_form' => $lostPasswordProprioForm->createView()
        ]);
    }









    /**
     * Réinitialiser le mot de passe
     * @Route("/reset-password-proprio/{id}/{token}", name="reset_password_proprio")
     *
     * @param Proprietaire                  $user            L'utilisateur qui souhaite réinitialiser son mot de passe
     * @param                               $token           Le jeton à vérifier pour la réinitialisation
     * @param Request                       $request         Pour le formulaire de réinitialisation
     * @param EntityManagerInterface        $entityManager   Pour mettre à jour l'utilisateur
     * @param UserPasswordEncoderInterface $passwordEncoder Pour hasher le nouveau mot de passe
     */
    public function resetPassword(
        Proprietaire $user,
        $token,
        Request $request,
        EntityManagerInterface $entityManager,
        UserPasswordEncoderInterface $passwordEncoder
    ) {
        // Le jeton ne correspond pas à celui de l'utilisateur
        if ($user->getSecurityToken() !== $token) {
            $this->addFlash('danger', 'Le jeton de sécurité est invalide.');
            return $this->redirectToRoute('login_proprietaire');
        }

        // Création du formulaire de réinitialisation du mot de passe
        $resetForm = $this->createForm(NomadeResetPasswordFormType::class);
        $resetForm->handleRequest($request);

        if ($resetForm->isSubmitted() && $resetForm->isValid()) {
            $password = $resetForm->get('plainPassword')->getData();

            $oldPassword = $passwordEncoder->isPasswordValid($user, $password);

            dump($oldPassword);

            if($oldPassword === false){
                $user->setPassword($passwordEncoder->encodePassword($user, $password));
                $user->renewToken();

                // Mise à jour de l'entité en BDD

                $entityManager = $this->getDoctrine()->getManager();
                $entityManager->persist($user);
                $entityManager->flush();

                // Ajout d'un message flash
                $this->addFlash('success', 'Votre mot de passe a bien été modifié.');
                return $this->redirectToRoute('login_proprietaire');
            }else{
                $this->addFlash('danger', 'Votre mot de passe doit être différent de l\'ancien');
            }

        }

        return $this->render('proprietaire/reset_password_form.html.twig', [
            'reset_form' => $resetForm->createView()
        ]);
    }


}
