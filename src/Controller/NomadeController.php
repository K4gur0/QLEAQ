<?php

namespace App\Controller;
use App\Entity\AnnonceSearch;
use App\Form\AnnonceSearchFormType;
use App\Form\NomadeType;
use App\Repository\AnnonceRepository;
use App\Repository\NomadeRepository;
use App\Repository\ProprietaireRepository;
use Knp\Component\Pager\PaginatorInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Constraints\Date;

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
     * @Route("/accueil-nomade", name="home")
     * @IsGranted("ROLE_USER")
     *
     */
    public function espace(AnnonceRepository $annonceRepository, ProprietaireRepository $proprietaireRepository, PaginatorInterface $paginator, Request $request){

        $proprio = $proprietaireRepository->findAll();
        $annoncePublie = $annonceRepository->findByPublication(true);
        $nomade = $this->getUser();
        $search = new AnnonceSearch();
        $form = $this->createForm(AnnonceSearchFormType::class, $search);
        $form->handleRequest($request);
        $totalAnnonce = count($annoncePublie);
        $date = new \DateTime();

        if ($form->isSubmitted() and $form->isValid())
        {
                $annonce = $paginator->paginate(
                    $annonceRepository->findByFiltre($search),
                    $request->query->getInt('page', 1),
                    12
                );
        }elseif($form->isSubmitted() and !$form->isValid()){
            $this->addFlash('error','Valeur(s) dans les filtres incorecte(s)');
            $annonce = $paginator->paginate(
                $annonceRepository->findByFiltre($search),
                $request->query->getInt('page', 1),
                12
            );
        }else{
            $annonce = $paginator->paginate(
                $annonceRepository->findByFiltre($search),
                $request->query->getInt('page', 1),
                12
            );
        }



///////////////        IF NO PUBLISHED ARTICLES        ///////////////
        if ($annoncePublie == false) {

             return $this->render('nomade/espace.html.twig',[
                 'annonce' => $annonce,
                 'proprio' => $proprio,
                 'noAnnonces' => $annoncePublie,
                 'nomade' => $nomade,
                 'form' => $form->createView(),
                 'compteur' => $totalAnnonce,
                 'currentDate' => $date
             ]);
        }
/////////////////////////////////////////////////////////////////////


        return $this->render('nomade/espace.html.twig',[
            'annonce' => $annonce,
            'proprio' => $proprio,
            'noAnnonces' => $annoncePublie,
            'nomade' => $nomade,
            'form' => $form->createView(),
            'compteur' => $totalAnnonce,
            'currentDate' => $date
        ]);

    }


    /**
     * Montrer en détail une annonce
     * @Route("/annonce-{id}", name="annonce_detail")
     * @IsGranted("ROLE_USER")
     *
     */
    public function annonceDetail(AnnonceRepository $annonceRepository, $id){
        $annonce = $annonceRepository->findById2($id);
        $nomade = $this->getUser();

        return $this->render('nomade/annonceDetail.html.twig',[
            'annonce' => $annonce,
            'id' => $id,
            'noAnnonces' => true,
            'nomade' => $nomade,
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

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($nomade);
            $entityManager->flush();

            $this->addFlash('success', 'Votre profil a été mis à jour.');

        }else if ($nomadeForm->isSubmitted()) {
            $this->addFlash('danger', 'Echec de mise à jour.');
        }

        return $this->render('nomade/nomade.html.twig', [
            'nomadeForm' => $nomadeForm->createView(),
            'nomade' => $nomade,
        ]);
    }



    /**
     * @Route("/add_favorite_{id}", name="add_favorite")
     * @IsGranted("ROLE_USER")
     */
    public function addFavorite(AnnonceRepository $annonceRepository, $id, NomadeRepository $nomadeRepository){
        $annonce = $annonceRepository->find($id);
        $nomade = $this->getUser();
        $nomade->addFavori($annonce);
        $annonce->addNomade($nomade);
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->persist($nomade);
        $entityManager->persist($annonce);
        $entityManager->flush();

        $this->addFlash('success', 'L\'annonce : "' . $annonce->getTitre() . '" a été ajoutée aux favoris');
        return $this->redirectToRoute('nomade_home', [
            'nomade' => $nomade,
            'annonce' => $annonce,
        ]);

    }

    /**
     * @Route("/remove_favorite_{id}", name="remove_favorite")
     * @IsGranted("ROLE_USER")
     */
    public function removeFavorite(AnnonceRepository $annonceRepository, $id){
        $annonce = $annonceRepository->find($id);

        $nomade = $this->getUser();
        $nomade->removeFavori($annonce);
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->persist($nomade);
        $entityManager->flush();

        $this->addFlash('warning', 'L\'annonce : "' . $annonce->getTitre() . '" a été retirée de vos favoris');
        return $this->redirectToRoute('nomade_home', [
            'nomade' => $nomade,
            'annonce' => $annonce,
        ]);
    }


    /**
     * @Route("/mes_favoris", name="my_favorites")
     * @IsGranted("ROLE_USER")
     */
    public function myFavorites(AnnonceRepository $annonceRepository){

        $nomade = $this->getUser();
        $annonce = $annonceRepository->orderByDate();


        return $this->render('nomade/my_favorites.html.twig', [
            'nomade' => $nomade,
            'annonce' => $annonce,
        ]);
    }




}

