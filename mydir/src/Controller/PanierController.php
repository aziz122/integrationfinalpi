<?php

namespace App\Controller;

use App\Repository\ProduitRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;

class PanierController extends AbstractController
{
    /**
     * @Route("/panier", name="showcart")
     */
    public function index(SessionInterface $session, ProduitRepository $produitRepository): Response
    {
        $panier=$session->get('panier',[]);

        $panierWithData=[];

        foreach ($panier as $id =>$quantity){
            $panierWithData[]=[
                'product'=>$produitRepository->find($id),
                'quantity'=>$quantity

            ];

        }
        $total=0;

        foreach ($panierWithData as $item){
            $totalItem = $item ['product']->getPrixProd() * $item['quantity'];
            $total +=$totalItem;

        }
        $session->set('total',$total);





        return $this->render('panier/index.html.twig', [
            'items'=>$panierWithData,
            'total'=>$total

        ]);




    }

    /**
     *
     * @Route ("/panier/add/{id}", name= "Paniers")
     */
    public function add($id, SessionInterface $session ) {



        $panier = $session->get('panier',[]);

        if (!empty($panier[$id])){
            $panier[$id]++;
        }
        else {
            $panier[$id]=1;
        }


        $session->set('panier',$panier);

    return $this->redirectToRoute('showcart');





    }

    /**
     * @param $id
     * @param SessionInterface $session
     * @Route ("/panier/remove/{id}",name="cart_remove")
     */
    public function removeP($id, SessionInterface $session){
        $panier = $session->get('panier',[]);
        if (!empty($panier[$id])){
            unset($panier[$id]);
        }
        $session->set('panier',$panier);

        return $this->redirectToRoute("showcart");
    }





}
