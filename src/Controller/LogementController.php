<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class LogementController extends AbstractController
{
    /**
     * @Route("/logement", name="logement")
     */
    public function index()
    {
        return $this->render('logement/index.html.twig', [
            'controller_name' => 'LogementController',
        ]);
    }
}
