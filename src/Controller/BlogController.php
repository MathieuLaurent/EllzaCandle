<?php

namespace App\Controller;

use App\Entity\Products;
use App\Repository\ProductsRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class BlogController extends AbstractController
{
    

    /**
     * @Route("/", name="home")
     */
    public function home(ProductsRepository $products){
        return $this->render('blog/home.html.twig',[
            'products' =>$products->findAll(),
        ]);
    }

    /**
     * @Route("/bougies", name="bougies")
     */
    public function bougies(ProductsRepository $productRepository){
        $products = $this->getDoctrine()->getRepository(Products::class)->findBy([],['id' => 'asc']);
        return $this->render('blog/bougies.html.twig', [
            'products' => $products,
        ]);
    }

    /**
     * @Route("/bougies/{id}", name="bougies_show")
     */
    public function bougiesShow($id){
       
        $repo= $this->getDoctrine()->getRepository(products::class);
        
        $product = $repo->find($id);


        return $this->render('blog/show.html.twig', [
            'product' => $product
        ]);
    }

    /**
     * @Route("/bougies-evenement", name="bougies-evenement")
     */
    public function bougiesEvenement(){
        return $this->render('blog/bougiesevenement.html.twig');
    }
    
}

