<?php

namespace App\Controller;

use App\Entity\Event;
use App\Entity\Images;
use App\Entity\Recherche;
use App\Form\EventType;
use App\Form\RechercheeType;
use App\Repository\EventRepository;
use Knp\Component\Pager\Pagination\PaginationInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\User;
use App\Entity\Commentaire;
use App\Form\CommentaireType;
use Endroid\QrCode\QrCode;
use Knp\Component\Pager\PaginatorInterface;



/**
 * @Route("/event")
 */
class EventController extends AbstractController

{
    /**
     * @param Request $request
     * @return Response
     * @Route ("/search",name="searchrdvvv")
     */
   public function searchrdvvv(Request $request)
    {
       $repository = $this->getDoctrine()->getRepository(Event::class);
        $requestString=$request->get('searchValue');
        $rdv = $repository->findByname($requestString);
        return $this->render('event/recherche1.html.twig' ,[
            "events"=>$rdv,
        ]);
    }
    /**
     * @Route("/participer{id}", name="event_participer", methods={"GET"})
     */
    public function participerAction($id, \Swift_Mailer $mailer)
    {

        $em = $this->getDoctrine()->getManager();
        $datej = new \DateTime('now');
        $datej2 = $datej->format('Y-m-d');
        $event = $em->getRepository(Event::class)->find($id);
        $datedb = $event->getDateDebut();
        $datedb1 = $datedb->format('Y-m-d');

        //$userid = $this->getUser()->getId();
        $user = $em->getRepository(User::class)->find($id);
        if ($event->getUsers()->contains($user)) {
            $msg = "Vous etes deja inscrit a cet evennement ";
            return $this->render('event/front/participationFailed.html.twig', array('msg' => $msg));

        } else if ($datedb1 < $datej2) {
            $msg = "vous ne pouvez plus participer a cet evennement ";
            return $this->render('event/front/participationFailed.html.twig', array('msg' => $msg));

        } else if ($event->getNbPlaces() <= 0) {
            $msg = "Nous sommes désolé , il n'y a plus de places disponible ";
            return $this->render('event/front/participationFailed.html.twig', array('msg' => $msg));

        } else {
            $users = $event->getUsers();
            $users->add($user);
            $event->setUsers($users);
            $event->setNbPlaces($event->getNbPlaces() - 1);
            $em->persist($event);

           /*$qrCode = new QrCode('Bonjour Monsieur : ' . $user->getNom() . ' , ceci votre ticket vous avez participé à l\'événement : ' . $event->getNom() .
                ' , Date debut : ' .  $datedb1);
            header('Content-Type: image/png');
            $qrCode->writeFile('Evenement/image/qrcode/qrcode.png');*/

            $email = $user->getGmail();
            $id = $user->getId();
            $message = (new \Swift_Message())
                ->setSubject($event->getNom())
                ->setFrom('projet.pidev1@gmail.com')
                ->setTo($email)
                ->setBody(
                    'votre ticket ');
                /*->attach(Swift_Attachment::fromPath('Evenement/image/qrcode/qrcode.png')
                    ->setDisposition('inline'));*/



            $mailer->send($message);

            $em->flush();
            $msg = "Vous avez particper a cet evenemment avec succé !! ";
            return $this->render('event/front/participationSuccess.html.twig', array('msg' => $msg));

        }
    }
    /**
     * @Route("/annuler{id}", name="event_annuler", methods={"GET"})
     */
    public function annulerParticipationAction($id){
        $em = $this->getDoctrine()->getManager();
        $event = $em->getRepository(Event::class)->find($id);
        //$userid = $this->getUser()->getId();
        $user = $em->getRepository(User ::class)->find(1);
        $event->setNbPlaces($event->getNbPlaces() + 1);
        $users =  $event->getUsers();
        $users->removeElement($user);
        $event->setUsers($users);
        $em->flush();
        return $this->redirectToRoute('event_index');


    }

    /**
     *@Route("/Recherche",name="Recherche")
     * Method({"GET", "POST"})
     */

    public function home(Request $request)
    {
        $Recherche= new Recherche();
        $form = $this->createForm(RechercheeType::class,$Recherche);
        $form->handleRequest($request);
        //initialement le tableau des articles est vide,
        //c.a.d on affiche les articles que lorsque l'utilisateur clique sur le bouton rechercher
        $events= [];

        if($form->isSubmitted() && $form->isValid()) {
            //on récupère le nom d'article tapé dans le formulaire
            $val = $Recherche->getNom();
            if ($val!="")
                //si on a fourni un nom d'article on affiche tous les articles ayant ce nom
                $events= $this->getDoctrine()->getRepository(Event::class)->findByNom($val);
            else
                //si si aucun nom n'est fourni on affiche tous les articles
                $events= $this->getDoctrine()->getRepository(Event::class)->findAll();
        }
        return  $this->render('Event/Recherche.html.twig',[ 'form' =>$form->createView(), 'events' => $events]);
    }
    /**
     * @Route("/", name="event_index", methods={"GET"})
     */
    public function index(EventRepository $eventRepository,PaginatorInterface $paginator,Request $request): Response
    {
        $u ="gmiza";
        $events =$eventRepository->findAll();
        $events = $paginator->paginate(
            $events, // Requête contenant les données à paginer (ici nos articles)
            $request->query->getInt('page', 1), // Numéro de la page en cours, passé dans l'URL, 1 si aucune page
            3 // Nombre de résultats par page
        );
        return $this->render('event/index.html.twig', [
            'u'=>$u,
            'events'=>$events

        ]);
       /* $u ="gmiza";
        return $this->render('event/index.html.twig', [
            'events' => $eventRepository->findAll(),
            'u'=>$u

        ]);*/


    }
    /**
     * @Route("back/", name="event_indexback", methods={"GET"})
     */
    public function indexback(EventRepository $eventRepository): Response
    {
        $u ="gmiza";
        return $this->render('event/indexback.html.twig', [
            'events' => $eventRepository->findAll(),
            'u'=>$u

        ]);
    }
    /**
     * @Route("/new", name="event_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $event = new Event();
        $form = $this->createForm(EventType::class, $event);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {


            $event->uploadProfilePicture();
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($event);
            $entityManager->flush();

            return $this->redirectToRoute('event_index');
        }

        return $this->render('event/new.html.twig', [
            'event' => $event,
            'form' => $form->createView(),
        ]);
    }


    /**
     * @Route("/{id}", name="event_show")
     *  Method({"GET", "POST"})
     */
    public function show(Request $request,Event $event): Response
    {
        



        $em = $this->getDoctrine()->getManager();
        $id= $event->getId();
        //$comments = $em->getRepository(Commentaire::class)->findByEvent(2);
        $comments = $em->getRepository(Commentaire::class)->findByevent($event);
        $comment = new Commentaire();
        $form = $this->createForm(CommentaireType::class, $comment);
        $form->handleRequest($request);
        //$userid = $this->getUser()->getId();
        $user = $em->getRepository(User::class)->find(1);
        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $comment->setEvent($event);
            $comment->setUser($user);
            $em->persist($comment);
            $em->flush();
            $entityManager->persist($comment);
            $entityManager->flush();
        }
            return $this->render('event/show.html.twig', array(

                'event' => $event,
                'comments' => $comments,
                'form' => $form->createView()


            ));
    }


    /**
     * @Route("/back/{id}", name="event_showback")
     *  Method({"GET", "POST"})
     */
    public function showback(Request $request,Event $event): Response
    {




        $em = $this->getDoctrine()->getManager();
        $id= $event->getId();
        //$comments = $em->getRepository(Commentaire::class)->findByEvent(2);
        $comments = $em->getRepository(Commentaire::class)->findByevent($event);
        $comment = new Commentaire();
        $form = $this->createForm(CommentaireType::class, $comment);
        $form->handleRequest($request);
        //$userid = $this->getUser()->getId();
        $user = $em->getRepository(User::class)->find(1);
        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $comment->setEvent($event);
            $comment->setUser($user);
            $em->persist($comment);
            $em->flush();
            $entityManager->persist($comment);
            $entityManager->flush();
        }
        return $this->render('event/showback.html.twig', array(

            'event' => $event,
            'comments' => $comments,
            'form' => $form->createView()


        ));
    }
    /**
     * @Route("/{id}/edit", name="event_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Event $event): Response
    {
        $form = $this->createForm(EventType::class, $event);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('event_index');
        }

        return $this->render('event/edit.html.twig', [
            'event' => $event,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="event_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Event $event): Response
    {
        if ($this->isCsrfTokenValid('delete'.$event->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($event);
            $entityManager->flush();
        }

        return $this->redirectToRoute('event_index');
    }

    /**
     * @Route("/back", name="back")
     */
    public function affiche()

    {

        return $this->render('event/back.html.twig');
    }

    /**
     * @Route("/{id}", name="event_signal")
     *  Method({"GET", "POST"})
     */

    public function signalAction($id){
        $em = $this->getDoctrine()->getManager();
        $event = $em->getRepository('Event::class')->find($id);
       // $userid = $this->getUser()->getId();
        $user = $em->getRepository(User::class)->find(3);
        $users = $event->getusersSignal();
        if($event->getusersSignal()->contains($user)) {
            $msg = "vous avez deja signalé cet evennement ";
            return $this->render('@event/front/participationFailed.html.twig', array('msg'=>$msg));

        }
        else {
            $nb = $event->getNbsignal() + 1;
            $event->setNbsignal($nb);
            $users->add($user);

            $event->setusersSignal($users);
            $em->flush();

            return $this->redirectToRoute('event_index');


        }
    }
}
