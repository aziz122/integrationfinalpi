<?php

namespace App\Controller;

use App\Entity\Produit;
use App\Form\ProduitFType;
use App\Form\ProduitType;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\File\File;
class ProduitController extends AbstractController

{
    /**
     * @Route (" ",name = "home")
     */
    public function index()
    {

        return $this->render("base.html.twig");
    }


    /**
     * @Route("/new", name="produit_new")
     */
    public function new(Request $request): Response
    {
        $produit = new Produit();
        $form = $this->createForm(ProduitFType::class, $produit);
        $form->add('Ajouter',SubmitType::class,['label'=>'Ajouter']);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $file = $form->get('img_prod')->getData();
            $newFilename= md5(uniqid()).'.'.$file->guessExtension();
            try {
                $file->move(
                    $this->getParameter('img_directory') ,
                    $newFilename
                );
            } catch (FileException $e) {
                // ... handle exception if something happens during file upload
            }
            $entityManager = $this->getDoctrine()->getManager();
            $produit->setImgProd($newFilename);
            $entityManager->persist($produit);
            $entityManager->flush();

            return $this->redirectToRoute('showproduit');
        }

        return $this->render('produit/new.html.twig', [

            'form' => $form->createView(),
        ]);
    }
    /**
     * @Route("/liste", name="showproduit")
     */

    public function listProduit(Request $request, PaginatorInterface $paginator)
    {
        $donnes= $this->getDoctrine()->getRepository(Produit::class)->findBy([],
        ['id'=>'desc']);
        $arcticle = $paginator ->paginate(
            $donnes,
            $request->query->getInt('page',1),
            9
        );
        return $this->render("produit/listeproduit.html.twig",array('listproduit'=>$arcticle));

    }

    /**
     * @param Request $request
     * @return Response
     * @Route ("/produitajaxxx/mahmoud",name="searchrdvzz")
     */
    public function searchrdvvv(Request $request)
    {
        $repository = $this->getDoctrine()->getRepository(Produit::class);
        $requestString=$request->get('searchValue');
        $rdv = $repository->findrdvByname($requestString);
        return $this->render('produit/produitajax.html.twig' ,[
            "listproduit"=>$rdv,
        ]);
    }








}
