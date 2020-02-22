<?php

namespace App\Controller;

use App\Entity\Annonce;
use App\Entity\Proprietaire;
use App\Form\AnnonceFormType;
use App\Form\DeleteAnnonceFormType;
use App\Form\ProprioType;
use App\Notif\NotifProprio;
use App\Repository\AnnonceRepository;
use App\Repository\ProprietaireRepository;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

/**
 * @Route("/proprietaire", name="proprio_")
 *
 */
class ProprioController extends AbstractController
{

        /**
         * @Route("/", name="presentation")
         *
         */

        public function presentationProprio(){

            return $this->render('proprietaire/presentation.html.twig');
        }

        /**
         * @Route("/accueil", name="home")
         * @IsGranted("ROLE_PROPRIO")
         */

        public function espace(Request $request, EntityManagerInterface $entityManager, AnnonceRepository $annonceRepository){


            $annonces = $annonceRepository->findById($this->getUser()->getId());
            $proprio = $this->getUser();
//            dd($annonces);



        return $this->render('proprietaire/espace.html.twig',
            [
                'annonce' => $annonces,
                'proprio' => $proprio,
            ]);
        }


    /**
     * @Route("/ajouter-annonce", name="add_annonce")
     * @IsGranted("ROLE_PROPRIO")
     */
        public function addAnnonce(Request $request, EntityManagerInterface $entityManager){


            $annonce = new Annonce();
            $proprio = $this->getUser();
            $annonce->setProprio($proprio);


            $annonceForm = $this->createForm(AnnonceFormType::class, $annonce);
            $annonceForm->handleRequest($request);


            if ($annonceForm->isSubmitted() && $annonceForm->isValid()) {

                /**
                 * @Var Annonce $annonce
                 */

                $annonce = $annonceForm->getData();

                $entityManager->persist($annonce);
                $entityManager->flush();


                $this->addFlash('success', 'Votre Annonce à bien été créer. Vous pouvez maintenant effectué une demande de publication sur l\'espace "Mes annonces"');

                return $this->redirectToRoute('proprio_home');



            }else if ($annonceForm->isSubmitted()) {
                $this->addFlash('danger', 'Echec lors de la création de l\'annonce.');
            }

            return $this->render('proprietaire/add_annonce.html.twig', [
                'annonceForm' => $annonceForm->createView(),
            ]);
        }

    /**
     * @Route("/demande-publication-annonce-{id}", name="ask_publication")
     * @IsGranted("ROLE_PROPRIO")
     */
    public function askPublication(Request $request, EntityManagerInterface $entityManager, NotifProprio $notifProprio,AnnonceRepository $annonceRepository, $id){

        $annonce = $annonceRepository->find($id);
        $proprio = $this->getUser();

            $this->addFlash('success', 'Votre demande à bien étée envoyer. Nos équipe vont à présent l\'étudier pour validation avant publication');
            $notifProprio->demandePublicationAnnonce($proprio, $annonce);

            return $this->redirectToRoute('proprio_home');

    }



    /**
     * Confirmation de l'annonce (lien envoyé par email)
     * @Route("/confirm_publication_{id}", name="annonce_confirmation")
     *
     * @param Proprietaire           $user          Le Propriétaire qui souhaite publié l'annonce
     * @param Annonce                $annonce       L'annonce concernée
     * @param                        $token         Le jeton à vérifier pour confirmer le compte
     * @param EntityManagerInterface $entityManager Pour mettre à jour l'utilisateur
     */

    public function confirmAnnonce(AnnonceRepository $annonceRepository,
                                   $id,
                                   EntityManagerInterface $entityManager,
                                   NotifProprio $notifProprio)
    {
        $proprio = $this->getUser();
        $annonce = $annonceRepository->find($id);

        $publicationStatus = $annonce->getPublicationAuth();

        if($publicationStatus != true ){

            $annonce->setPublicationAuth(true);

            $entityManager->persist($annonce);
            $entityManager->flush();

            $notifProprio->confirmPublication($proprio, $annonce);

            $this->addFlash('success', 'Vous avez bien valider la publication de l\'annonce');
            return $this->redirectToRoute('admin_login');

        }elseif($publicationStatus == false){
            $this->addFlash('warning', 'Vous avez refuser la publication de l\'annonce');
            return $this->redirectToRoute('admin_login');
        }


    }


    /**
     * @Route("/modif-annonce{id}", name="edit_annonce")
     * @IsGranted("ROLE_PROPRIO")
     */
    public function editAnnonce(Request $request, AnnonceRepository $annonceRepository, $id){


        $annonce = $annonceRepository->find($id);
        $editAnnonceForm = $this->createForm(AnnonceFormType::class, $annonce);
        $editAnnonceForm->handleRequest($request);

        if ($editAnnonceForm->isSubmitted() && $editAnnonceForm->isValid()) {
            // Formulaire lié à une classe entité: getData() retourne l'entité
            $annonce = $editAnnonceForm->getData();

            // Mise à jour de l'entité en BDD

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($annonce);
            $entityManager->flush();


            // Ajout d'un message flash
            $this->addFlash('success', 'L\'annonce a été mise à jour.');



        }else if ($editAnnonceForm->isSubmitted()) {
            $this->addFlash('danger', 'Echec de modification.');
        }

        return $this->render('proprietaire/add_annonce.html.twig', [
            'annonceForm' => $editAnnonceForm->createView(),
            'annonce' => $annonce,
        ]);
    }

    /**
     * @Route("/supprimer-annonce{id}", name="delete_annonce")
     * @IsGranted("ROLE_PROPRIO")
     */
    public function deleteAnnonce(Request $request, AnnonceRepository $annonceRepository, $id){


        $annonce = $annonceRepository->find($id);
        $deleteAnnonceForm = $this->createForm(DeleteAnnonceFormType::class, $annonce);
        $deleteAnnonceForm->handleRequest($request);

        if ($deleteAnnonceForm->isSubmitted() && $deleteAnnonceForm->isValid()) {
            // Formulaire lié à une classe entité: getData() retourne l'entité
            $annonce = $deleteAnnonceForm->getData();

            // Mise à jour de l'entité en BDD

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($annonce);
            $entityManager->flush();


            $this->addFlash('warning', 'L\'annonce ' . $annonce->getTitre() . ' a été supprimer.');

            return $this->redirectToRoute('proprio_home');


        }else if ($deleteAnnonceForm->isSubmitted()) {
            $this->addFlash('danger', 'Echec lors de la suppression');
        }

        return $this->render('proprietaire/delete_annonce.html.twig', [
            'deleteAnnonceForm' => $deleteAnnonceForm->createView(),
            'annonce' => $annonce,
        ]);
    }




    /**
         * @Route("/profile", name="profile")
         * @IsGranted("ROLE_PROPRIO")
         *
         */

        public function index(Request $request, UserPasswordEncoderInterface $passwordEncoder): Response
        {
        // Récupération de l'utilisateur courant
        $proprio = $this->getUser();
        // Passage de l'utilisateur au formulaire pour pré-remplir les champs
        $proprioForm = $this->createForm(ProprioType::class, $proprio);

        $proprioForm->handleRequest($request);

        // Vérification de validité
        if ($proprioForm->isSubmitted() && $proprioForm->isValid()) {
            // Formulaire lié à une classe entité: getData() retourne l'entité
            $proprio = $proprioForm->getData();

            // Mise à jour de l'entité en BDD

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($proprio);
            $entityManager->flush();




            // Ajout d'un message flash
            $this->addFlash('success', 'Votre profil a été mis à jour.');



        }else if ($proprioForm->isSubmitted()) {
            $this->addFlash('danger', 'Echec de mise à jour.');
        }

        return $this->render('proprietaire/proprio.html.twig', [
            'proprioForm' => $proprioForm->createView()
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
