<?php

namespace App\Controller;
use App\Form\NomadeType;
use App\Repository\AnnonceRepository;
use App\Repository\ProprietaireRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/locataire", name="nomade_")
 *
 */

class NomadeController extends AbstractController
{

    /**
     * Presentation et "Comment ça marche" de l'espace Nomade (Locataire)
     * @Route("/", name="presentation")
     *
     */

    public function presentationLoc(){

        return $this->render('nomade/presentation.html.twig');
    }

    /**
     * @Route("/offres", name="offres")
     */
    public function offres(){

        return $this->render('nomade/offres.html.twig');
    }









    /**
     * Page d'accueil une foi le Nomade connecté
     * @Route("/accueil", name="home")
     * @IsGranted("ROLE_USER")
     *
     */
    public function espace(AnnonceRepository $annonceRepository, ProprietaireRepository $proprietaireRepository){

        $annonce = $annonceRepository->orderByDate();
        $proprio = $proprietaireRepository->findAll();
        $annoncePublie = $annonceRepository->findByPublication(true);

        if ($annoncePublie == false) {

             return $this->render('nomade/espace.html.twig',[
                 'annonce' => $annonce,
                 'proprio' => $proprio,
                 'noAnnonces' => $annoncePublie,
             ]);
            }

        return $this->render('nomade/espace.html.twig',[
            'annonce' => $annonce,
            'proprio' => $proprio,
            'noAnnonces' => $annoncePublie,
        ]);

    }


    /**
     * @Route("/profile", name="profile")
     * @IsGranted("ROLE_USER")
     *
     */
    public function index(Request $request): Response
    {
        $nomade = $this->getUser();
        $nomadeForm = $this->createForm(NomadeType::class, $nomade);
        $nomadeForm->handleRequest($request);

        if ($nomadeForm->isSubmitted() && $nomadeForm->isValid()) {

            $nomade = $nomadeForm->getData();

            //$photoProfile = $nomadeForm->get('photo_profile')->getData();

            //if ($photoProfile) {

            //    $originalFilename = pathinfo($photoProfile->getClientOriginalName(), PATHINFO_FILENAME);
            //    // this is needed to safely include the file name as part of the URL
            //    $safeFilename = transliterator_transliterate('Any-Latin; Latin-ASCII; [^A-Za-z0-9_] remove; Lower()', $originalFilename);
            //    $newFilename = $safeFilename.'-'.uniqid().'.'.$photoProfile->guessExtension();
            //    //$newFilename = $nomade->getNom() . '.' . $nomade->getPrenom() . '-id_' . $nomade->getId() . '.' . $photoProfile->guessExtension();

            //    try {
            //        $photoProfile->move(
            //            $this->getParameter('dossier_photo_nomade'),
            //            $newFilename
            //        );
            //    } catch (FileException $e) {
            //        // ... handle exception if something happens during file upload
            //    }

            //    $nomade->setPhotoProfile($newFilename);
            //}

            // Mise à jour de l'entité en BDD

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($nomade);
            $entityManager->flush();


            // Ajout d'un message flash
            $this->addFlash('success', 'Votre profil a été mis à jour.');

        }else if ($nomadeForm->isSubmitted()) {
            $this->addFlash('danger', 'Echec de mise à jour.');
        }

        return $this->render('nomade/nomade.html.twig', [
            'nomadeForm' => $nomadeForm->createView(),
            'nomade' => $nomade,
        ]);
    }



    /*
     * @Route("/remove_picture", name="remove_picture")
     * @IsGranted("ROLE_USER")
     */
    /*
    public function supprimerPhotoNomade(Filesystem $filesystem){

        $nomade = $this->getUser();

        $photoProfileActuelle = $nomade->getPhotoProfile();
        $filesystem->remove($this->getParameter('dossier_photo_nomade') . '/' . $photoProfileActuelle);

        $nomade->setPhotoProfile(null);

        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->persist($nomade);
        $entityManager->flush();


        // Ajout d'un message flash
        $this->addFlash('warning', 'Photo de profile retirée !');

        return $this->redirectToRoute('nomade_nomade');

    }
    */




//    /**
//     * @Route("/add_favorite_{id}", name="add_favorite")
//     * @IsGranted("ROLE_USER")
//     */
//    public function addFavorite(AnnonceRepository $annonceRepository, $id){
//        $annonce = $annonceRepository->find($id);
//
//        $nomade = $this->getUser();
//
//        $nomadeId = $annonce->getNomade($nomade)->current();
//
//        $annonce->addNomade($nomade);
//        $entityManager = $this->getDoctrine()->getManager();
//        $entityManager->persist($annonce);
//        $entityManager->flush();
//
//        $this->addFlash('success', 'L\'annonce : "' . $annonce->getTitre() . '" a été ajoutée aux favories');
//        return $this->redirectToRoute('nomade_home', [
//            'nomade' => $nomade,
//            'nomadeId' => $nomadeId,
//            'annonce' => $annonce,
//        ]);
//
//
//    }
//
//
//
//    /**
//     * @Route("/remove_favorite_{id}", name="remove_favorite")
//     * @IsGranted("ROLE_USER")
//     */
//    public function removeFavorite(AnnonceRepository $annonceRepository, $id){
//        $annonce = $annonceRepository->find($id);
//
//        $nomade = $this->getUser();
//
//        $nomadeId = $annonce->getNomade($nomade)->current();
//
//            $annonce->removeNomade($nomade);
//            $entityManager = $this->getDoctrine()->getManager();
//            $entityManager->persist($annonce);
//            $entityManager->flush();
//
//
//
//            $this->addFlash('warning', 'L\'annonce : "' . $annonce->getTitre() . '" a été retirée de vos favories');
//            return $this->redirectToRoute('nomade_home', [
//                'nomade' => $nomade,
//                'nomadeId' => $nomadeId,
//                'annonce' => $annonce,
//            ]);
//
//    }


    /**
     * @Route("/favorite_{id}", name="add_favorite")
     * @IsGranted("ROLE_USER")
     */
    public function annonceFavorite(AnnonceRepository $annonceRepository, $id){
        $annonce = $annonceRepository->find($id);

        $nomade = $this->getUser();

        $nomadeId = $annonce->getNomade($nomade)->current();



        if ($nomade == $nomadeId){
            $annonce->removeNomade($nomade);
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($annonce);
            $entityManager->flush();


            $this->addFlash('warning', 'L\'annonce : "' . $annonce->getTitre() . '" a été retirée de vos favories');
            return $this->redirectToRoute('nomade_home', [
                'nomade' => $nomade,
                'nomadeId' => $nomadeId,
                'annonce' => $annonce,
            ]);
        }
            $annonce->addNomade($nomade);
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($annonce);
            $entityManager->flush();


            $this->addFlash('success', 'L\'annonce : "' . $annonce->getTitre() . '" a été ajoutée aux favories');
            return $this->redirectToRoute('nomade_home', [
                'nomade' => $nomade,
                'nomadeId' => $nomadeId,
                'annonce' => $annonce,
            ]);


    }





}

