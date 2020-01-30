<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class ProprioSpaceController extends AbstractController
{
    /**
     * @Route("/espace-proprietaire", name="proprio_space")
     */
    public function index()
    {
        return $this->render('proprio_space/proprio_space.html.twig');
    }
}
