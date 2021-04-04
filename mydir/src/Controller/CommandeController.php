<?php

namespace App\Controller;

use App\Entity\Commande;
use App\Entity\Produit;
use App\Form\CommandeType;
use App\Repository\CommandeRepository;
use App\Repository\ProduitRepository;
use phpDocumentor\Reflection\Types\Float_;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\ExpressionLanguage\Token;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\HttpKernel\Event\GetResponseEvent;
use Symfony\Component\Routing\Annotation\Route;
use Dompdf\Dompdf;
use Dompdf\Options;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Stripe\Stripe;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;


/**
 * @Route("/commande")
 */
class CommandeController extends AbstractController

{


    /**
     * @return Response
     * @Route ("/payonline" , name="payonline")
     */
    public function pay(CommandeRepository $commandeRepository){

        return $this->render('commande/pay.html.twig', [
            'commandes' => $commandeRepository->findAll(),
        ]);    }

    /**
     * @Route("/success", name="success")
     */
    public function success(){
        return $this->render('commande/success.html.twig');
    }

    /**
     * @Route("/error", name="error")
     */
    public function error(){
        return $this->render('commande/error.html.twig');
    }

    /**
     * @return JsonResponse
     * @throws \Stripe\Exception\ApiErrorException
     * @Route ("/create-checkout-session", name="checkout")
     */
    public function checkout() {

        \Stripe\Stripe::setApiKey('sk_test_51ITAkCA1lUiT8XfWfH5neoZ2jEX2Yp5kAUYrTyZwg2OjVOFbqnHZE4X0GVmCdWuRpDOf6pXsHxd9iLPYGS2KN3Cn00MQ0n80Sc');
        $session = \Stripe\Checkout\Session::create([
            'payment_method_types' => ['card'],
            'line_items' => [[
                'price_data' => [
                    'currency' => 'usd',
                    'product_data' => [
                        'name' => 'A&M Shop',
                    ],
                    'unit_amount' => 100,
                ],
                'quantity' => 1,
            ]],
            'mode' => 'payment',
            'success_url' => $this->generateUrl('success',[],UrlGeneratorInterface::ABSOLUTE_URL),
            'cancel_url' => $this->generateUrl('error',[],UrlGeneratorInterface::ABSOLUTE_URL),

            ]);
        return new JsonResponse([ 'id' => $session->id]);




    }



    /**
     * @Route("/test1", name="test1")
     */
    public function back(EntityManagerInterface $manager)
    {
        $produit = $manager->createQuery('SELECT COUNT(p) FROM App\Entity\Produit p')->getSingleScalarResult();
        $commande = $manager->createQuery('SELECT COUNT(c) FROM App\Entity\Commande c')->getSingleScalarResult();

        $topproduit = $manager->createQuery(
            'SELECT count(c) as qte_prod, a.nom_prod,a.img_prod
             FROM App\Entity\Commande c
             JOIN c.produit a
             GROUP BY a
             ORDER BY qte_prod DESC'
        )
            ->setMaxResults(3)
            ->getResult();
        $worstproduit = $manager->createQuery(
            'SELECT count(c) as qte_prod, a.nom_prod,a.img_prod
             FROM App\Entity\Commande c
             JOIN c.produit a
             GROUP BY a
             ORDER BY qte_prod ASC'
        )
            ->setMaxResults(3)
            ->getResult();

        return $this->render('commande/stat.html.twig',[
            "produit"=>$produit,
            'commande'=>$commande,
            "topproduit"=>$topproduit,
            "worstproduit"=>$worstproduit
        ]);
    }


    /**
     * @Route("/imprimer", name="impp", methods={"GET"})
     */
    public function DownloadCmd(CommandeRepository $commandeRepository){


        $pdfOptions = new Options();
        $pdfOptions->set('defaultFont', 'Arial');
        $com = $commandeRepository->findAll();
        // Instantiate Dompdf with our options
        $dompdf = new Dompdf($pdfOptions);

        // Retrieve the HTML generated in our twig file
        $html = $this->renderView('commande/imprimercom.html.twig', array('commandes' => $com));

        // Load HTML to Dompdf
        $dompdf->loadHtml($html);

        // (Optional) Setup the paper size and orientation 'portrait' or 'portrait'
        $dompdf->setPaper('A4', 'portrait');

        // Render the HTML as PDF
        $dompdf->render();

        // Output the generated PDF to Browser (force download)
        $dompdf->stream("mypdf.pdf", [
            "Attachment" => true
        ]);





    }



    /**
 * @Route("/showback", name="commande_showback", methods={"GET"})
 */
    public function showback(CommandeRepository $commandeRepository): Response
    {
        return $this->render('commande/showback.html.twig', [
            'commandes' => $commandeRepository->findAll(),
        ]);
    }



    /**
     * @Route("/", name="commande_index", methods={"GET"})
     */
    public function index(CommandeRepository $commandeRepository): Response
    {
        return $this->render('commande/show.html.twig', [
            'commandes' => $commandeRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new{id}", name="commande_new", methods={"GET","POST"})
     * @Security("is_granted('ROLE_USER')")
     */
    public function new (Request $request, \Swift_Mailer $mailer,$id) :Response
    {

        $commande = new Commande();

        $produit=$this->getDoctrine()->getRepository(Produit::class)->find($id);
        $commande->addProduit($produit);
        $form = $this->createForm(CommandeType::class, $commande);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $message = (new \Swift_Message('A&M Order'))
            ->setFrom('museumartapp@gmail.com')
            ->setTo($commande->getMail())
            ->setBody(
                $this->renderView('commande/mail.html.twig',compact('commande')
                ),

                'text/html'
            )
            ;
            $mailer->send($message);
            $this->addFlash('massage','Your Order Is Registered');


            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($commande);
            $entityManager->flush();

            return $this->redirectToRoute('commande_index');
        }
        return $this->render('commande/new.html.twig', [
            'commande' => $commande,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/newback", name="commande_newback", methods={"GET","POST"})
     */
    public function newback(Request $request): Response
    {
        $commande = new Commande();
        $form = $this->createForm(CommandeType::class, $commande);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($commande);
            $entityManager->flush();

            return $this->redirectToRoute('commande_showback');
        }

        return $this->render('commande/newback.html.twig', [
            'commande' => $commande,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="commande_show", methods={"GET"})
     */
    public function show(Commande $commande): Response
    {
        return $this->render('commande/show.html.twig', [
            'commande' => $commande,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="commande_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Commande $commande): Response
    {
        $form = $this->createForm(CommandeType::class, $commande);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('commande_index');
        }

        return $this->render('commande/edit.html.twig', [
            'commande' => $commande,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}/editback", name="commande_editback", methods={"GET","POST"})
     */
    public function editback(Request $request, Commande $commande): Response
    {
        $form = $this->createForm(CommandeType::class, $commande);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('commande_showback');
        }

        return $this->render('commande/editback.html.twig', [
            'commande' => $commande,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="commande_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Commande $commande): Response
    {
        if ($this->isCsrfTokenValid('delete'.$commande->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($commande);
            $entityManager->flush();
        }

        return $this->redirectToRoute('commande_index');
    }
    /**
     * @Route("/test", name="test")
     */
public function test(){
        return $this->render('commande/test.html.twig');
}







}
