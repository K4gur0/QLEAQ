<?php

namespace App\Controller;

use App\Form\ChangePasswordFormProprioType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class ChangePasswordProprioController extends AbstractController
{
    /**
     * @Route("/proprietaire/change/password", name="change_password_proprio")
     */
    public function index(Request $request, UserPasswordEncoderInterface $passwordEncoder) : Response
    {
        // Récupération de l'utilisateur courant
        $proprio = $this->getUser();

        // Passage de l'utilisateur au formulaire pour pré-remplir les champs
        $changePasswordFormProprio = $this->createForm(ChangePasswordFormProprioType::class, $proprio);

        $changePasswordFormProprio->handleRequest($request);

        // Vérification de validité
        if ($changePasswordFormProprio->isSubmitted() && $changePasswordFormProprio->isValid())
        {
//            $passwordEncoder = $this->get('security.csrf.token_manager');
//            dump($request->request);die();
            $oldPassword = $request->request->get('change_password_form_proprio')['password'];

            if ($passwordEncoder->isPasswordValid($proprio, $oldPassword))
            {
                // Formulaire lié à une classe entité: getData() retourne l'entité
                $proprio = $changePasswordFormProprio->getData();

                $proprio->setPassword(
                    $passwordEncoder->encodePassword(
                        $proprio,
                        $changePasswordFormProprio->get('plainPassword')->getData()
                    )
                );
                // Mise à jour de l'entité en BDD

                $entityManager = $this->getDoctrine()->getManager();
                $entityManager->persist($proprio);
                $entityManager->flush();


                // Ajout d'un message flash
                $this->addFlash('success', 'Votre mot de passe a bien été modifié.');
            }
            else
            {
                $this->addFlash('danger', 'L\'ancien mot de passe est incorrect');
            }



        }

        return $this->render('change_password/proprio.html.twig', [
            'changePasswordFormProprio' => $changePasswordFormProprio->createView()
        ]);
    }
}
