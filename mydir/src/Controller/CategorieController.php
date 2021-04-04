<?php

namespace App\Controller;

use App\Entity\Categorie;
use App\Form\CategorieType;
use App\Repository\CategorieRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/categorie")
 */
class CategorieController extends AbstractController
{
    /**
     * @Route("/", name="categorie_index", methods={"GET","POST"})
     */
    public function index(CategorieRepository $categorieRepository, Request $request): Response
    {
        $categorie = new Categorie();
        $form = $this->createForm(CategorieType::class, $categorie);
        $form->handleRequest($request);
        $em=$this->getDoctrine()->getManager();

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($categorie);
            $entityManager->flush();


            return $this->redirectToRoute('/');
        }
        return $this->render('categorie/indexCategorie.html.twig', [
            'categories' => $categorieRepository->findAll(),
            'categorie' => $categorie,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/back", name="categorie_index_back", methods={"GET"})
     */
    public function indexBack(CategorieRepository $categorieRepository): Response
    {

        return $this->render('categorie/indexBackCategorie.html.twig', [
            'categoriesBack' => $categorieRepository->findAll(),
        ]);
    }

    /**
     * @Route("/home", name="home")
     */
    public function indexhome(): Response
    {
        return $this->render('categorie/home.html.twig', [
            'controller_name' => 'CategorieController',
        ]);
    }

    /**
     * @Route("/all", name="findcategorie")
     */
    public function findcategorie(): Response
    {
        $categories=$this->getDoctrine()->getRepository(Categorie::class)->findAll();
        return $this->render('categorie/indexCategorie.html.twig',array('categories' => $categories)

        );
    }


    /**
     * @Route("/new", name="categorie_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $categorie = new Categorie();
        $form = $this->createForm(CategorieType::class, $categorie);
        $form->handleRequest($request);
        $em=$this->getDoctrine()->getManager();

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($categorie);
            $entityManager->flush();

          //  $nbrQuestion=$categories->getNbrQuestion();
            //$categories->setNbrQuestion($nbrQuestion+1);
            //$em->flush();

            return $this->redirectToRoute('categorie_index');
        }

        return $this->render('categorie/new.html.twig', [
            'categorie' => $categorie,
            'form' => $form->createView(),

        ]);
    }

    /**
     * @Route("/{id}", name="categorie_show", methods={"GET"})
     */
    public function show(Categorie $categorie): Response
    {
        return $this->render('categorie/show.html.twig', [
            'categorie' => $categorie,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="categorie_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Categorie $categorie, CategorieRepository $categorieRepository): Response
    {
        $form = $this->createForm(CategorieType::class, $categorie);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('categorie_index');
        }

        return $this->render('categorie/editCategorie.html.twig', [
            'categorie' => $categorie,
            'form' => $form->createView(),
            'categories' => $categorieRepository->findAll(),
        ]);
    }

    /**
     * @Route("/{id}/editBack", name="categorie_edit_Back", methods={"GET","POST"})
     */
    public function editBack(Request $request, Categorie $categorie): Response
    {
        $form = $this->createForm(CategorieType::class, $categorie);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('categorie_index_back');
        }

        return $this->render('categorie/editBack.html.twig', [
            'categorieBack' => $categorie,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="categorie_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Categorie $categorie): Response
    {
        if ($this->isCsrfTokenValid('delete'.$categorie->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($categorie);
            $entityManager->flush();
        }

        return $this->redirectToRoute('categorie_index');
    }

    /**
     * @Route("/{id}", name="categorieDelete")
     */
    public function deleteC($id){
        $em=$this->getDoctrine()->getManager();
        $categorie=$this->getDoctrine()->getRepository(Categorie::class)->find($id);
        $em->remove($categorie);
        return $this->redirectToRoute('categorie_index');
    }

    /**
     * @Route("/{id}/back", name="categorie_delete_back", methods={"DELETE"})
     */
    public function deleteBack(Request $request, Categorie $categorie): Response
    {
        if ($this->isCsrfTokenValid('delete'.$categorie->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($categorie);
            $entityManager->flush();

        }

        return $this->redirectToRoute('categorie_index_back');
    }

    /**
     * @param Request $request
     * @return Response
     * @Route ("/cat/ajax",name="rechercheajax")
     */
    public function recherche(Request $request)
    {
        $repository = $this->getDoctrine()->getRepository(Categorie::class);
        $requestString=$request->get('searchValue');
        $requete = $repository->findByname($requestString);
        return $this->render('categorie/Categorieajaxfront.html.twig' ,[
            "categories"=>$requete,
        ]);
    }

    /**
     * @param Request $request
     * @return Response
     * @Route ("/cat/ajaxxx",name="rechercheajaxBack")
     */
    public function rechercheBack(Request $request)
    {
        $repository = $this->getDoctrine()->getRepository(Categorie::class);
        $requestString=$request->get('searchValue');
        $requete = $repository->findBynameBack($requestString);
        return $this->render('categorie/CategorieAjaxBack.html.twig' ,[
            "categories"=>$requete,
        ]);
    }


    /**
     * @Route("/triH", name="trih")
     */
    public function Tri(Request $request,CategorieRepository $repository): Response
    {
        // Retrieve the entity manager of Doctrine
        $order=$request->get('type');
        if($order== "Croissant"){
            $categorie = $repository->tri_asc();
        }
        else {
            $categorie = $repository->tri_desc();
        }
        // Render the twig view
        return $this->render('categorie/index.html.twig', [
            'categories' => $categorie
        ]);
    }
}
