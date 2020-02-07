<?php

namespace App\Controller;

use App\Form\ChangePasswordFormType;
use App\Form\NomadeType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\FormError;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;


class ChangePasswordController extends AbstractController
{
    /**
     * @Route("/change/password", name="change_password")
     */
    public function index(Request $request, UserPasswordEncoderInterface $passwordEncoder) : Response
    {
        // Récupération de l'utilisateur courant
        $nomade = $this->getUser();

        // Passage de l'utilisateur au formulaire pour pré-remplir les champs
        $changePasswordForm = $this->createForm(ChangePasswordFormType::class, $nomade);

        $changePasswordForm->handleRequest($request);

        // Vérification de validité
        if ($changePasswordForm->isSubmitted() && $changePasswordForm->isValid())
        {
//            $passwordEncoder = $this->get('security.csrf.token_manager');
//            dump($request->request);die();
            $oldPassword = $request->request->get('change_password_form')['password'];

            if ($passwordEncoder->isPasswordValid($nomade, $oldPassword))
            {
                // Formulaire lié à une classe entité: getData() retourne l'entité
                $nomade = $changePasswordForm->getData();

                $nomade->setPassword(
                    $passwordEncoder->encodePassword(
                        $nomade,
                        $changePasswordForm->get('plainPassword')->getData()
                    )
                );
                // Mise à jour de l'entité en BDD

                $entityManager = $this->getDoctrine()->getManager();
                $entityManager->persist($nomade);
                $entityManager->flush();


                // Ajout d'un message flash
                $this->addFlash('success', 'Votre mot de passe a bien été modifié.');
            }
            else
            {
                $this->addFlash('danger', 'L\'ancien mot de passe est incorrect');
            }



        }

        return $this->render('change_password/index.html.twig', [
            'changePasswordForm' => $changePasswordForm->createView()
        ]);
    }
}

