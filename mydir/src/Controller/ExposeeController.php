<?php

namespace App\Controller;

use App\Entity\Exposee;
use App\Entity\Photos;
use App\Form\ExposeeType;
use App\Repository\ExposeeRepository;

use App\Repository\PhotosRepository;
use phpDocumentor\Reflection\Type;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\JsonResponse;

/**
 * Class ExposeeController
 * @package App\Controller
 */
class ExposeeController extends AbstractController
{
    /**
     * @Route("/exposee", name="exposee")
     *
     */
    public function index(): Response
    {
        return $this->render('exposee/index.html.twig', [
            'controller_name' => 'ExposeeController',
        ]);
    }

    /**
     * @param ExposeeRepository $repository
     * @return Response
     * @Route ("/affiche",name="affiche")
     * @Security("is_granted('ROLE_ADMIN')")
     */
    public function afficheExposee(ExposeeRepository $repository,PaginatorInterface $paginator,Request $request){


        $exposee=$repository->findAll();
        $articles = $paginator->paginate(
            $exposee, // Requête contenant les données à paginer (ici nos articles)
            $request->query->getInt('page', 1), // Numéro de la page en cours, passé dans l'URL, 1 si aucune page
            6 // Nombre de résultats par page
        );
        return $this->render('exposee/affiche.html.twig',['exposee'=>$articles]);

    }
    /**
     * @param ExposeeRepository $repository
     * @return Response
     * @Route ("",name="")
     */
    public function afficheExposee2(ExposeeRepository $repository,PaginatorInterface $paginator,Request $request){

        $exposee=$repository->findAll();
        $articles = $paginator->paginate(
            $exposee, // Requête contenant les données à paginer (ici nos articles)
            $request->query->getInt('page', 1), // Numéro de la page en cours, passé dans l'URL, 1 si aucune page
            6 // Nombre de résultats par page
        );
        return $this->render('exposee/afficherE.html.twig',['exposee'=>$articles ]);

    }
    /**
     * @param $id
     * @param ExposeeRepository $repository
     * @return Response
     * @Route ("/afficheEX/{id}",name="afficheEX")
     */
    public function afficherEx($id,ExposeeRepository $repository,PaginatorInterface $paginator,Request $request){
        $Exposee=$repository->find($id);
        $articles = $Exposee->getPhoto() ;// Requête contenant les données à paginer (ici nos articles)


        return $this->render('exposee/afficherEX.html.twig',['exposee'=>$articles,'ex'=>$Exposee]);

    }
    /**
     * @param ExposeeRepository $repository
     * @return Response
     * @Route ("/afficheE",name="afficheE")
     */
    public function afficheExposee1(ExposeeRepository $repository,PaginatorInterface $paginator,Request $request){

        $exposee=$repository->findAll();
        $articles = $paginator->paginate(
            $exposee, // Requête contenant les données à paginer (ici nos articles)
            $request->query->getInt('page', 1), // Numéro de la page en cours, passé dans l'URL, 1 si aucune page
            6 // Nombre de résultats par page
        );
        return $this->render('exposee/afficherE.html.twig',['exposee'=>$articles]);
    }
    /**
     * @param ExposeeRepository $repository
     * @return Response
     * @Route ("/trie",name="trie")
     * @Security("is_granted('ROLE_ADMIN')")
     */
    public function TRie(ExposeeRepository $repository,PaginatorInterface $paginator,Request $request){

        $exposee=$repository->findEntitiesorder1();
        $articles = $paginator->paginate(
            $exposee, // Requête contenant les données à paginer (ici nos articles)
            $request->query->getInt('page', 1), // Numéro de la page en cours, passé dans l'URL, 1 si aucune page
            6 // Nombre de résultats par page
        );
        return $this->render('exposee/affiche.html.twig',['exposee'=>$articles]);
    }
    /**
     * @param ExposeeRepository $repository
     * @return Response
     * @Route ("/trieex",name="trieex")
     */
    public function Trieex(ExposeeRepository $repository,PaginatorInterface $paginator,Request $request){

        $exposee=$repository->findEntitiesorder();
        $articles = $paginator->paginate(
            $exposee, // Requête contenant les données à paginer (ici nos articles)
            $request->query->getInt('page', 1), // Numéro de la page en cours, passé dans l'URL, 1 si aucune page
            6 // Nombre de résultats par page
        );
        return $this->render('exposee/afficherE.html.twig',['exposee'=>$articles]);
    }

    /**
     * @param $id
     * @param ExposeeRepository $repository
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     * @Route ("/suppe/{id}",name="d")
     * @Security("is_granted('ROLE_ADMIN')")
     */
    public function supprimer($id,ExposeeRepository $repository){
        $Exposee=$repository->find($id);
        if($Exposee->getPhoto()!=null)
        {
            $this->addFlash('success', 'fas5 tsawer kbal');
        }
        $em=$this->getDoctrine()->getManager();
        $em->remove($Exposee);
        $em->flush();
        return$this->redirectToRoute('affiche');
    }

    /**
     * @param Request $request
     * @return Response
     * @Route ("/ajouterExposee",name="ajouterEx")
     * @Security("is_granted('ROLE_ADMIN')")
     */
    public function ajouter(Request $request){
        $Exposee=new Exposee();
        $form=$this->createForm(ExposeeType::class,$Exposee);
        $form->add('ajouter',submitType::class);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            $images = $form->get('Photos')->getData();
            foreach ($images as $image) {
            $fichier = md5(uniqid()) . '.' . $image->guessExtension();
            $image->move(
            $this->getParameter('upload_destination'),
            $fichier
              );
             $img = new Photos();
             $img->setName($fichier);
             $Exposee->addPhoto($img);
            }
            $em=$this->getDoctrine()->getManager();
            $em->persist($Exposee);
            $em->flush();
            return $this->redirectToRoute('affiche');
        }
        return $this->render('Exposee/ajouterExposee.html.twig',['form'=>$form->createView()

        ]);
    }

    /**
     * @param ExposeeRepository $repository
     * @param $id
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|Response
     * @Route ("/update.twig.html/{id}", name="update")
     * @Security("is_granted('ROLE_ADMIN')")
     */
    public function modifier( ExposeeRepository $repository,$id,Request $request,PhotosRepository $repository1){
        $Exposee=$repository->find($id);
        $form=$this->createForm(ExposeeType::class,$Exposee);
        $form->add('Update',SubmitType::class);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            $images = $form->get('Photos')->getData();
// Boucle sur les images
            foreach ($images as $image) {
                $fichier = md5(uniqid()) . '.' . $image->guessExtension();
                $image->move(
                    $this->getParameter('upload_destination'),
                    $fichier
                );
                $img = new Photos();
                $img->setName($fichier);
                $Exposee->addPhoto($img);
            }
            $em=$this->getDoctrine()->getManager();
            $em->flush();
            return $this->redirectToRoute('affiche');
        }
        return $this->render('Exposee/update.html.twig',['form'=>$form->createView() ,'exposee'=>$Exposee

        ]);
    }
    public function adminDashboard()
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');

        // or add an optional message - seen by developers
        $this->denyAccessUnlessGranted('ROLE_ADMIN', null, 'User tried to access a page without having ROLE_ADMIN');
    }
    /**
     * @param ExposeeRepository $repository
     * @param Request $request
     * @return Response
     * @Route ("/search_ajax",name="search_ajax")
     */
    public function searchAction(Request $request,ExposeeRepository $repository)
    {
        $em = $this->getDoctrine()->getManager();
        $requestString = $request->get('q');



//        var_dump(strlen($requestString));
        $entities =  $em->getRepository(Exposee::class)->findEntitiesByString($requestString);

        if(!$entities)
        {
            $result['entities']['error'] = "there is no demande with this titre";

        }
       if(strlen($requestString)==1)
        {

            $entities=$repository->findAll();
            $result['entities']=$this->getRealEntities($entities);
        }
        else
        {

            $result['entities'] = $this->getRealEntities($entities);
        }

        return new JsonResponse($result, 200);
    }


    public function getRealEntities($entities){


        foreach ($entities as $entity)
        {
            $realEntities[$entity->getId()] = [$entity->getNom(),$entity->getDescription(),$entity->getId(),$entity->getDateC(),$entity->getPhoto()];
        }


        return $realEntities;
    }
    /**
    * @Route("/supprime/image/{id}/{idPhotos}", name="annonces_delete_image")
    * @Security("is_granted('ROLE_ADMIN')")
    */
    public function deleteImage( Request $request,ExposeeRepository $repository,PhotosRepository $photoRepo,PaginatorInterface $paginator){
        $fetchedImage=$photoRepo->find($request->get('idPhotos'));
        if($fetchedImage != null) {
            $fetchedExposee=$repository->find($request->get('id'));
            $fetchedExposee->removePhoto($fetchedImage);
            $em = $this->getDoctrine()->getManager();
            $em->remove($fetchedImage);
            $em->flush();
            $exposee=$repository->findAll();
            $articles = $paginator->paginate(
                $exposee, // Requête contenant les données à paginer (ici nos articles)
                $request->query->getInt('page', 1), // Numéro de la page en cours, passé dans l'URL, 1 si aucune page
                6 // Nombre de résultats par page
            );
             return $this->render('exposee/affiche.html.twig',['exposee'=>$articles]);
          }
                  $exposee=$repository->findAll();
        return $this->render('exposee/affiche.html.twig',['exposee'=>$exposee]);
     }


}
