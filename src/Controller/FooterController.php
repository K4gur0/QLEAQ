<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;


/**
 * @Route("/footer", name="footer_")
 */
class FooterController extends AbstractController
{


    /**
     * @Route("/mentions", name="mentions")
     */
    public function mentions()
    {
        return $this->render('footer/mentions.html.twig', [
            'controller_name' => 'FooterController',
        ]);
    }


    /**
     * @Route("/contact", name="contact")
     */
    public function contact()
    {
        return $this->render('footer/contact.html.twig', [
            'controller_name' => 'FooterController',
        ]);
    }


    /**
     * @Route("/partenaires", name="partenaires")
     */
    public function partenaires()
    {
        return $this->render('footer/partenaires.html.twig', [
            'controller_name' => 'FooterController',
        ]);
    }



    /**
     * @Route("/cgu", name="cgu")
     */
    public function cgu()
    {
        return $this->render('footer/cgu.html.twig', [
            'controller_name' => 'FooterController',
        ]);
    }


    /**
     * @Route("/presse", name="presse")
     */
    public function presse()
    {
        return $this->render('footer/presse.html.twig', [
            'controller_name' => 'FooterController',
        ]);
    }


    /**
     * @Route("/pro", name="pro")
     */
    public function professionnels()
    {
        return $this->render('footer/professionnels.html.twig', [
            'controller_name' => 'FooterController',
        ]);
    }


    /**
     * @Route("/annonceurs", name="annonceurs")
     */
    public function annonceurs()
    {
        return $this->render('footer/annonceurs.html.twig', [
            'controller_name' => 'FooterController',
        ]);
    }

}
