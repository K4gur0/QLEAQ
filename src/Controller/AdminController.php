<?php

namespace App\Controller;

use App\Entity\Admin;
use App\Entity\Nomade;
use App\Entity\Proprietaire;
use App\Form\AdminRegistrationType;
use App\Form\DeleteNomadeType;
use App\Form\DeleteProprioType;
use App\Form\GestionNomadeType;
use App\Form\GestionProprioType;
use App\Form\NomadeType;
use App\Notif\NotifProprio;
use App\Repository\AdminRepository;
use App\Repository\NomadeRepository;
use App\Repository\ProprietaireRepository;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

/**
 * @Route("/adm", name="admin_")
 *
 */
class AdminController extends AbstractController
{

    /**
     * @param AuthenticationUtils $authenticationUtils
     * @return Response
     * @Route("/", name="login")
     */

    public function login(AuthenticationUtils $authenticationUtils): Response
    {
         if ($this->getUser()) {
             return $this->redirectToRoute('admin_home');
         }

        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();
        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('admin/login.html.twig', ['last_username' => $lastUsername, 'error' => $error]);
    }


    /**
     * @return Response
     * @Route("/register", name="register")
     */
    public function register(Request $request, UserPasswordEncoderInterface $passwordEncoder): Response
    {
        $admin = new Admin();
        $form = $this->createForm(AdminRegistrationType::class, $admin);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $this->addFlash('success', 'Votre compte a bien été créée');


            // encode the plain password
            $admin->setPassword(
                $passwordEncoder->encodePassword(
                    $admin,
                    $form->get('plainPassword')->getData()
                )
            );

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($admin);
            $entityManager->flush();

            return $this->redirectToRoute('admin_login');
        }

        return $this->render('admin/register.html.twig', [
            'AdminRegistrationForm' => $form->createView(),
        ]);
    }

    /**
     * @Route("/accueil", name="home")
     *
     */

    public function espace(NomadeRepository $nomadeRepository, ProprietaireRepository $proprietaireRepository, AdminRepository $adminRepository){
        $nomade = $nomadeRepository->findAll();
        $proprietaire = $proprietaireRepository->findAll();
        $admin = $adminRepository->findAll();


//        dump($nomade);die();

        return $this->render('admin/espace.html.twig', [
            'nomade' => $nomade,
            'proprietaire' => $proprietaire,
            'admin' => $admin
        ]);
    }



    /**
     * @Route("/liste/nomades", name="liste_nomades")
     */
    public function listeNomade(NomadeRepository $nomadeRepository){
        $nomade = $nomadeRepository->findAll();

        return $this->render('admin/liste_nomades.html.twig', [
            'nomade' => $nomade,
        ]);
    }

    /**
     * @Route("/gestion/nomade{id}", name="gestion_nomade")
     *
     */
    public function gestionNomade(Request $request, NomadeRepository $nomadeRepository,$id): Response
    {

        $nomade = $nomadeRepository->find($id);

        $gestionNomadeForm = $this->createForm(GestionNomadeType::class, $nomade);

        $gestionNomadeForm->handleRequest($request);

        // Vérification de validité
        if ($gestionNomadeForm->isSubmitted() && $gestionNomadeForm->isValid()) {
            // Formulaire lié à une classe entité: getData() retourne l'entité
            $nomade = $gestionNomadeForm->getData();

            // Mise à jour de l'entité en BDD

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($nomade);
            $entityManager->flush();


            // Ajout d'un message flash
            $this->addFlash('success', 'Le profil a été mis à jour.');



        }else if ($gestionNomadeForm->isSubmitted()) {
            $this->addFlash('danger', 'Echec de mise à jour.');
        }

        return $this->render('admin/gestion_nomade.html.twig', [
            'gestionNomadeForm' => $gestionNomadeForm->createView(),
            'nomade' => $nomade,
        ]);
    }


    /**
     * @Route("/delete/nomade{id}", name="delete_nomade")
     *
     */

    public function deleteNomade(Request $request, NomadeRepository $nomadeRepository, $id): Response
    {
        $nomade = $nomadeRepository->find($id);

        $deleteNomadeForm = $this->createForm(DeleteNomadeType::class, $nomade);
        $deleteNomadeForm->handleRequest($request);

        if ($deleteNomadeForm->isSubmitted()){
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($nomade);
            $entityManager->flush();

            $this->addFlash('warning', 'Le compte Nomade de ' . $nomade->getPrenom() . ' ' . $nomade->getNom() . ' a été supprimer.');

            return $this->redirectToRoute('admin_home');
        }

        return $this->render('admin/delete_nomade.html.twig', [
            'deleteNomadeForm' => $deleteNomadeForm->createView(),
            'nomade' => $nomade,
        ]);

    }




    /**
     * @Route("/liste/proprietaires", name="liste_proprios")
     */
    public function listeProprio(ProprietaireRepository $proprietaireRepository){
        $proprio = $proprietaireRepository->findAll();

        return $this->render('admin/liste_proprietaires.html.twig', [
            'proprio' => $proprio,
        ]);
    }


    /**
     * @Route("/gestion/proprietaire{id}", name="gestion_proprietaire")
     *
     */
    public function gestionProprietaire(Request $request, ProprietaireRepository $proprietaireRepository,$id): Response
    {
        // Récupération de l'utilisateur courant
//        $id = $request->getUser();
        $proprio = $proprietaireRepository->find($id);
//        dump($id);
//        dump($nomade);die();
        // Passage de l'utilisateur au formulaire pour pré-remplir les champs
        $gestionProprioForm = $this->createForm(GestionProprioType::class, $proprio);

//        dump($proprio);die();
        $gestionProprioForm->handleRequest($request);

        // Vérification de validité
        if ($gestionProprioForm->isSubmitted() && $gestionProprioForm->isValid()) {
            // Formulaire lié à une classe entité: getData() retourne l'entité
            $proprio = $gestionProprioForm->getData();
//            dump($proprio);die();

            // Mise à jour de l'entité en BDD

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($proprio);
            $entityManager->flush();


            // Ajout d'un message flash
            $this->addFlash('success', 'Le profil a été mis à jour.');



        }else if ($gestionProprioForm->isSubmitted()) {
            $this->addFlash('danger', 'Echec de mise à jour.');
        }

        return $this->render('admin/gestion_proprio.html.twig', [
            'gestionProprioForm' => $gestionProprioForm->createView(),
            'proprio' => $proprio
        ]);
    }

    /**
     * @Route("/gestion/proprietaire{id}/valide", name="confirm_proprio")
     *
     * @param Proprietaire           $proprio          L'utilisateur qui tente de confirmer son compte
     * @param EntityManagerInterface $entityManager Pour mettre à jour l'utilisateur
     */
    public function confirmProprioViaAdmin(EntityManagerInterface $entityManager, ProprietaireRepository $proprietaireRepository, $id, NotifProprio $notifProprio){

        $proprio = $proprietaireRepository->find($id);
        $proprio = $proprio->setRefus(1);
        $proprio->setIsConfirmed(true);
        $gestionProprioForm = $this->createForm(GestionProprioType::class, $proprio);

        $entityManager->persist($proprio);
        $entityManager->flush();
        $notifProprio->confirmationProprio($proprio);
        $this->addFlash('success', 'Compte Validé !');
        return $this->render('admin/gestion_proprio.html.twig', [
            'gestionProprioForm' => $gestionProprioForm->createView(),
            'proprio' => $proprio
        ]);
    }
    /**
     * @Route("/gestion/proprietaire{id}/refus", name="refus_proprio")
     *
     * @param Proprietaire           $proprio          L'utilisateur qui tente de confirmer son compte
     * @param EntityManagerInterface $entityManager Pour mettre à jour l'utilisateur
     */
    public function refusProprioViaAdmin(EntityManagerInterface $entityManager, ProprietaireRepository $proprietaireRepository, $id, NotifProprio $notifProprio){

        $proprio = $proprietaireRepository->find($id);
        $proprio = $proprio->setRefus(2);

        $gestionProprioForm = $this->createForm(GestionProprioType::class, $proprio);

        $entityManager->persist($proprio);
        $entityManager->flush();
        $notifProprio->refusProprio($proprio);
        $this->addFlash('danger', 'Compte Refusé !');
        return $this->render('admin/gestion_proprio.html.twig', [
            'gestionProprioForm' => $gestionProprioForm->createView(),
            'proprio' => $proprio
        ]);
    }


    /**
     * @Route("/delete/proprietaire{id}", name="delete_proprietaire")
     *
     */

    public function deleteProprio(Request $request, ProprietaireRepository $proprietaireRepository, $id): Response
    {
        $proprio = $proprietaireRepository->find($id);

        $deleteProprioForm = $this->createForm(DeleteProprioType::class, $proprio);
        $deleteProprioForm->handleRequest($request);

        if ($deleteProprioForm->isSubmitted()){
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($proprio);
            $entityManager->flush();

            $this->addFlash('warning', 'Le compte Propriétaire ' . $proprio->getRaisonSocial() . ' a été supprimer.');

            return $this->redirectToRoute('admin_home');
        }

        return $this->render('admin/delete_proprio.html.twig', [
            'deleteProprioForm' => $deleteProprioForm->createView(),
            'proprio' => $proprio,
        ]);

    }


    /**
     * @Route("/logout", name="app_logout")
     */
    public function logout()
    {
        throw new \Exception('This method can be blank - it will be intercepted by the logout key on your firewall');
    }





}
