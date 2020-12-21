<?php

namespace App\service\panier;

use App\Repository\ProductsRepository;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class panierService{

    protected $session;
    protected $productsRepository;

    public function __construct(SessionInterface $session, ProductsRepository $productsRepository){
        $this->session = $session;
        $this->productsRepository = $productsRepository;
    }

    public function add(int $id){
        $panier = $this->session->get('panier',[]);

        if(!empty($panier[$id])){
            $panier[$id]++;
        }
        else{
        $panier[$id] = 1;
        }
        
        $this->session->set('panier', $panier);
    }

    public function retirer(int $id){
        $panier = $this->session->get('panier', []);

      if(!empty($panier[$id])){
          if(!empty($quantity)){
              unset($panier[$id]);
          }
          else{
            $panier[$id]--;   
          }
      }
      else{
            unset($panier[$id]);
          }

      $this->session->set('panier', $panier);
    }

    public function remove(int $id){
        $panier = $this->session->get('panier', []);

        if(!empty($panier[$id])){
            unset($panier[$id]);
        }
  
        $this->session->set('panier', $panier);
    }

    public function getFullCart(): array{
        $panier=$this->session->get('panier', []);
        $panierWithData=[];
        foreach($panier as $id=>$quantity){
        $panierWithData[] = [
            'product' => $this->productsRepository->find($id),
            'quantity' => $quantity
        ];
      }
      return $panierWithData;
    }

    public function getTotal(): float{
        $total=0;
    
        foreach($this->getFullCart() as $item){
          $total += $item['product']->getPrix() * $item['quantity']; 
        }
        return $total;
    }
}