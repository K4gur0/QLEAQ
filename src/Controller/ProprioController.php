<?php

namespace App\Controller;

use App\Form\NomadeType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

/**
 * @Route("/proprio", name="proprio_")
 * @IsGranted("ROLE_PROPRIO")
 */
class ProprioController extends AbstractController
{

        /**
         * @Route("/", name="home")
         */

        public function espace(){

        return $this->render('proprietaire/espace.html.twig');
    }

        /**
         * @Route("/profile", name="profile")
         *
         */
//    public function index()
//    {
//        $nomade = new Nomade();
//        $form = $this->createForm(NomadeType::class, $nomade);
//
//        return $this->render('nomade/nomade.html.twig', [
//            'formNomade' => $form->createView(),
//        ]);
//    }
        public function index(Request $request, UserPasswordEncoderInterface $passwordEncoder): Response
    {
        // Récupération de l'utilisateur courant
        $nomade = $this->getUser();
        // Passage de l'utilisateur au formulaire pour pré-remplir les champs
        $nomadeForm = $this->createForm(NomadeType::class, $nomade);

        $nomadeForm->handleRequest($request);

        // Vérification de validité
        if ($nomadeForm->isSubmitted() && $nomadeForm->isValid()) {
            // Formulaire lié à une classe entité: getData() retourne l'entité
            $nomade = $nomadeForm->getData();

            // Mise à jour de l'entité en BDD

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($nomade);
            $entityManager->flush();

//            /**
//             * @var UploadedFile $photo
//             */
//            $photo = $nomadeForm->get('photo_profile')->getData();
//
//            if ($photo){
//                $originalFilename = pathinfo($photo->getClientOriginalName(), PATHINFO_FILENAME);
//                $safeFilename = transliterator_transliterate('Any-Latin; Latin-ASCII; [^A-Za-z0-9_] remove; Lower()', $originalFilename);
//                $newFilename = $safeFilename.'-'.uniqid().'.'.$photo->guessExtension();
//
//                try {
//                    $photo->move(
//                        $this->getParameter('dossier_photo'),
//                        $newFilename
//                    );
//                } catch (FileException $e) {
//                    // ... handle exception if something happens during file upload
//                }
//
//            }
//
//
//
//            $nomade->setPhotoProfile($newFilename);




            // Ajout d'un message flash
            $this->addFlash('success', 'Votre profil a été mis à jour.');



        }else if ($nomadeForm->isSubmitted()) {
            $this->addFlash('danger', 'Echec de mise à jour.');
        }

        return $this->render('proprietaire/proprio.html.twig', [
            'nomadeForm' => $nomadeForm->createView()
        ]);
    }

/**
 * @Route("/login", name="app_login")
 */
public function login(AuthenticationUtils $authenticationUtils): Response
{
    // if ($this->getUser()) {
    //     return $this->redirectToRoute('target_path');
    // }

    // get the login error if there is one
    $error = $authenticationUtils->getLastAuthenticationError();
    // last username entered by the user
    $lastUsername = $authenticationUtils->getLastUsername();

    return $this->render('security/login.html.twig', ['last_username' => $lastUsername, 'error' => $error]);
}

/**
 * @Route("/logout", name="app_logout")
 */
public function logout()
{
    throw new \Exception('This method can be blank - it will be intercepted by the logout key on your firewall');
}

}
