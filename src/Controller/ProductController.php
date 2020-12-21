<?php

namespace App\Controller;

use Stripe\Stripe;
use SessionIdInterface;
use App\Repository\ProductsRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;


class ProductController extends AbstractController
{
    
    /**
     * @Route("/panier", name="panier")
     */
    public function panier(SessionInterface $session, ProductsRepository $productsRepository)
    {
        $panier = $session->get('panier', []);
        $panierWithData = [];
        
        foreach($panier as $id=>$quantity){
            $panierWithData[] = [
                'product' => $productsRepository->find($id),
                'quantity' => $quantity
            ];
        }
        $total= 0;

        foreach($panierWithData as $item){
            $totalItem= $item['product']->getPrix() * $item['quantity'];
            $total += $totalItem; 
        }

        return $this->render('product/panier.html.twig', [
            'items'=>$panierWithData,
            'total'=>$total
        ]);
        }

}
