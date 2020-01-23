<?php

namespace App\Controller;

use App\Entity\Nomade;
use App\Form\NomadeType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class NomadeController extends AbstractController
{
    /**
     * @Route("/nomade", name="nomade")
     */
    public function index()
    {
        $nomade = new Nomade();
        $form = $this->createForm(NomadeType::class, $nomade);

        return $this->render('nomade/index.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
