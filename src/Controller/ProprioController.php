<?php

namespace App\Controller;

use App\Entity\Annonce;
use App\Entity\Proprietaire;
use App\Form\AnnonceFormType;
use App\Form\ProprioType;
use App\Repository\ProprietaireRepository;
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

        public function espace(){

        return $this->render('proprietaire/espace.html.twig');
        }


    /**
     * @Route("/ajouter-annonce", name="add_annonce")
     */
        public function addAnnonce(Request $request, ProprietaireRepository $proprietaireRepository){

            $proprio = $this->getUser();
            $idProprio = $proprio->getId();
//            dump($proprio->getId());
//            die();
            $annonceForm = $this->createForm(AnnonceFormType::class);

            return $this->render('proprietaire/add_annonce.html.twig', [
                'annonceForm' => $annonceForm->createView(),
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
