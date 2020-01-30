<?php

namespace App\Controller;

use App\Entity\Nomade;
use App\Form\NomadeType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;


class NomadeController extends AbstractController
{
    /**
     * @Route("/nomade", name="nomade")
     */
//    public function index()
//    {
//        $nomade = new Nomade();
//        $form = $this->createForm(NomadeType::class, $nomade);
//
//        return $this->render('nomade/nomade.html.twig', [
//            'formNomade' => $form->createView(),
//        ]);
//    }
    public function index(Request $request, EntityManagerInterface $em)
    {
        // Récupération de l'utilisateur courant
        $nomade = $this->getUser();
        // Passage de l'utilisateur au formulaire pour pré-remplir les champs
        $nomadeForm = $this->createForm(NomadeType::class, $nomade);
        $nomadeForm->handleRequest($request);

        // Vérification de validité
        if ($nomadeForm->isSubmitted() && $nomadeForm->isValid()) {
            // Formulaire lié à une classe entité: getData() retourne l'entité
            $nomade = $nomadeForm->getData();

            // Mise à jour de l'entité en BDD
            $em->persist($nomade);
            $em->flush();

            // Ajout d'un message flash
            $this->addFlash('success', 'Votre profil a été mis à jour.');
        }

        return $this->render('nomade/nomade.html.twig', [
            'nomadeForm' => $nomadeForm->createView()
        ]);
    }
}

