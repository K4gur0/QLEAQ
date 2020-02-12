<?php

namespace App\Controller;

use App\Entity\Admin;
use App\Form\AdminRegistrationType;
use App\Repository\NomadeRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

/**
 * @Route("/adm", name="admin_")
 *
 */
class AdminController extends AbstractController
{

    /**
     * @param AuthenticationUtils $authenticationUtils
     * @return Response
     * @Route("/login", name="login")
     */

    public function login(AuthenticationUtils $authenticationUtils): Response
    {
         if ($this->getUser()) {
             return $this->redirectToRoute('admin_home');
         }

        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();
        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('admin/login.html.twig', ['last_username' => $lastUsername, 'error' => $error]);
    }


    /**
     * @return Response
     * @Route("/register", name="register")
     */
    public function register(Request $request, UserPasswordEncoderInterface $passwordEncoder): Response
    {
        $admin = new Admin();
        $form = $this->createForm(AdminRegistrationType::class, $admin);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $this->addFlash('success', 'Votre compte a bien été créée');


            // encode the plain password
            $admin->setPassword(
                $passwordEncoder->encodePassword(
                    $admin,
                    $form->get('plainPassword')->getData()
                )
            );

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($admin);
            $entityManager->flush();

            return $this->redirectToRoute('admin_login');
        }

        return $this->render('admin/register.html.twig', [
            'AdminRegistrationForm' => $form->createView(),
        ]);
    }

    /**
     * @Route("/accueil", name="home")
     *
     */

    public function espace(NomadeRepository $nomadeRepository){
        $nomade = $nomadeRepository->findAll();

//        dump($nomade);die();

        return $this->render('admin/espace.html.twig', [
            'nomade' => $nomade
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
