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
        public function addAnnonce(Request $request, EntityManagerInterface $entityManager, NotifProprio $notifProprio){


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


                $this->addFlash('success', 'Votre Annonce à bien été créée. Vous pouvez dès à présent la modifier, publier ou supprimer à partir de la rubrique "Mes Annonces"');

                $notifProprio->NewAnnonce($proprio, $annonce);

                return $this->redirectToRoute('proprio_home');



            }else if ($annonceForm->isSubmitted()) {
                $this->addFlash('danger', 'Echec lors de la création de l\'annonce.');
            }

            return $this->render('proprietaire/add_annonce.html.twig', [
                'annonceForm' => $annonceForm->createView(),
            ]);
        }









    /**
     * @Route("/demande-publication-annonce-{id}", name="publication_annonce")
     * @IsGranted("ROLE_PROPRIO")
     */
    public function publicationAnnonce(Request $request, EntityManagerInterface $entityManager, NotifProprio $notifProprio,AnnonceRepository $annonceRepository, $id){
        $proprio = $this->getUser();
        $annonce = $annonceRepository->find($id);
        $publication = $annonce->getPublicationAuth();

        if ($publication == false or null){
            $annonce->setPublicationAuth(true);
            $entityManager->persist($annonce);
            $entityManager->flush();
            $this->addFlash('success', 'Votre Annonce : ' . $annonce->getTitre() . ' est maintenant visible par les Locataires');

        }else{
            $annonce->setPublicationAuth(false);
            $entityManager->persist($annonce);
            $entityManager->flush();
            $this->addFlash('warning', 'Votre Annonce : ' . $annonce->getTitre() . ' vient d\'être retiréé des publications');

        }
        return $this->redirectToRoute('proprio_home');
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
            'proprio' => $this->getUser(),
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
