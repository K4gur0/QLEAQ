<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class DossierController extends AbstractController
{
    /**
     * @Route("/dossier", name="dossier")
     */
    public function index()
    {
        return $this->render('dossier/dossier.html.twig', [
            'controller_name' => 'DossierController',
        ]);
    }
}
