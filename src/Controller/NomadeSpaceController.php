<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class NomadeSpaceController extends AbstractController
{
    /**
     * @Route("/nomade/space", name="nomade_space")
     */
    public function index()
    {
        return $this->render('nomade_space/nomade_space.html.twig');
    }
}
