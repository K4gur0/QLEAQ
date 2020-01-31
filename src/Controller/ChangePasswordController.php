<?php

namespace App\Controller;

use App\Form\ChangePasswordFormType;
use App\Form\NomadeType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class ChangePasswordController extends AbstractController
{
    /**
     * @Route("/change/password", name="change_password")
     */
    public function index(Request $request, UserPasswordEncoderInterface $passwordEncoder): Response
    {
        // Récupération de l'utilisateur courant
        $nomade = $this->getUser();
        // Passage de l'utilisateur au formulaire pour pré-remplir les champs
        $changePasswordForm = $this->createForm(ChangePasswordFormType::class, $nomade);

        $changePasswordForm->handleRequest($request);

        // Vérification de validité
        if ($changePasswordForm->isSubmitted() && $changePasswordForm->isValid()) {
            // Formulaire lié à une classe entité: getData() retourne l'entité
            $nomade = $changePasswordForm->getData();

            $nomade->setPassword(
                $passwordEncoder->encodePassword(
                    $nomade,
                    $changePasswordForm->get('plainPassword')->getData()
                )
            );
            // Mise à jour de l'entité en BDD
//            $em->persist($nomade);
//            $em->flush();
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($nomade);
            $entityManager->flush();


            // Ajout d'un message flash
            $this->addFlash('success', 'Votre mot de passe a été modifié.');
//            $this->addFlash('error', 'Echec de mise à jour.');
        }

        return $this->render('change_password/index.html.twig', [
            'changePasswordForm' => $changePasswordForm->createView()
        ]);
    }
}

