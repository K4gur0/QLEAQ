<?php

namespace App\Controller;

use App\Entity\Product;
use App\Form\ProductType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class ProductController extends AbstractController
{
    /**
     * @Route("/product/new", name="app_product_new")
     */

    public function new(Request $request)
    {
        $product = new Product();
        $form = $this->createForm(ProductType::class, $product);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid())
        {
            /**
             * @var UploadedFile $brochureFile
             */
            $brochureFile = $form->get('brochure')->getData();

            if ($brochureFile)
            {
                $originalFilenam = pathinfo($brochureFile->getClientOriginalName(), PATHINFO_FILENAME);
                $safeFilmename = transliterator_transliterate('Any-Latin; Latin-ASCII; [^A-Za-z0-9_] remove; Lower()', $originalFilenam);
                $newFilename = $safeFilmename. '-' . uniqid(). '.' .$brochureFile->getExtension();

                try{
                    $brochureFile->move(
                        $this->getParameter('brochures_directory'),
                        $newFilename
                    );
                } catch (FileException $e) {

                }
                $product->setBrochureFilename($newFilename);
            }
            return $this->redirect($this->generateUrl('app_product_new'));
        }

        return $this->render('product/index.html.twig', [
            'form' => $form->createView(),
        ]);

    }

}
