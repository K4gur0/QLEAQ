<?php

namespace App\Controller;

use App\Entity\Nomade;
use App\Form\ProprioRegistrationType;
use App\Notif\NotifNomade;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class ProprioRegistrationController extends AbstractController
{
    /**
     * @Route("/inscription-proprietaire", name="proprio_register")
     */
    public function register(Request $request, UserPasswordEncoderInterface $passwordEncoder, NotifNomade $notifNomade): Response
    {
        $proprio = new Nomade();
        $form = $this->createForm(ProprioRegistrationType::class, $proprio);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $this->addFlash('success', 'Votre compte a bien été créée');


            $proprio->setRoles(
              ["ROLE_PROPRIO"]
            );

            // encode the plain password
            $proprio->setPassword(
                $passwordEncoder->encodePassword(
                    $proprio,
                    $form->get('plainPassword')->getData()
                )
            );

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($proprio);
            $entityManager->flush();

            $notifNomade->notify($proprio);

            return $this->redirectToRoute('login_proprietaire');
        }

        return $this->render('registration/proprio_register.html.twig', [
            'ProprioRegistrationForm' => $form->createView(),
        ]);
    }
}
