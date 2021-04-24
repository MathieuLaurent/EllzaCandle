<?php

namespace App\Controller;

use App\Entity\CommandeQuantity;
use Stripe\Stripe;
use App\Entity\Users;
use App\Entity\Commandes;
use App\Entity\Products;
use Stripe\BillingPortal\Session;
use App\Repository\UsersRepository;
use App\service\panier\panierService;
use App\Repository\ProductsRepository;
use App\Repository\CommandesRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\User\User;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class CheckoutController extends AbstractController
{

    /**
     * @Route ("/validationPanier/{id}", name="validationPanier")
     */


     public function validationPanier($id, Request $request,  panierService $panierService, EntityManagerInterface $manager, UsersRepository $user, CommandesRepository $commandes, ProductsRepository $products){

      $panierWithData= $panierService->getFullCart();
      $total = $panierService->getTotal();

      $repository = $this->getDoctrine()->getRepository('App\Entity\Users');
      $products = $this->getDoctrine()->getRepository('App\Entity\Products');
      $user = $repository->find($id);

      $commandes = new Commandes();
      for ($i = 0; $i < count($panierWithData ); $i++) {
        $commandes->addProduct($panierWithData [$i]['product']);
      }
        $commandes->setTotal($total);
        $commandes->setEmail($user->getEmail());
        $commandes->setUsername($user->getUsername());
        $commandes->setCreatedAt(new \DateTime);
        $commandes->setPayment(false);
        $commandes->setArchive(false);
        $manager->persist($commandes);
        $manager->flush();

        //On ajoute une quantité sur chaque article qu'on lie a une commande
        for($i = 0; $i <count($panierWithData);$i++)
        {
          $commandeQuantity[$i] = new CommandeQuantity;
          $commandeQuantity[$i]->addCommande($commandes);
          $commandeQuantity[$i]->setProduit($panierWithData[$i]['product']);
          $commandeQuantity[$i]->setQuantity($panierWithData[$i]['quantity']);
          $commandeQuantity[$i]->setNumCmd($commandes->getId());
          $manager->persist($commandeQuantity[$i]);
        }
        for($i = 0; $i <count($panierWithData);$i++)
        {
          $products = $this->getDoctrine()->getRepository(Products::class)->findOneBy(['id' => $panierWithData[$i]['product']]);
          $stock = $products->getStock();
          $stock = $stock-$panierWithData[$i]['quantity'];
          $newStock = $products->setStock($stock);
          $manager->persist($newStock);
        }
        $manager->flush();


       return $this->render('product/validation.html.twig', [
          'user' => $user,
          'items' =>$panierWithData,
          'total' =>$total

       ]);
    }

    /**
     * @Route ("/create-checkout-session", name="checkout")
     */
    
    public function checkout(Request $request, ProductsRepository $productsRepository, panierService $panierService, UsersRepository $user){
      require '../vendor/autoload.php';
      header('Content-Type: application/json');

      \Stripe\Stripe::setApiKey('sk_test_51HxwguL2rG2L8ViQ9C0Yy31w9UT25bx9vdJWUhi9ZGREfvlOiiYcNFRb9ctWSy3FBGEvjkmmrpaUOue3se2C4B4y00yMTUpCHb');
      //\Stripe\Stripe::setApiKey('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');

      $total = $panierService->getTotal();

      $promotion_code = \Stripe\PromotionCode::create([
       'coupon' => 'xM1h2cdl',
       // 'coupon' => 'MhJzKO9a',
      ]);

      $sessionStripe = \Stripe\Checkout\Session::create([
          'payment_method_types' => ['card'],
          'line_items' => [[
            'price_data' => [
              'currency' => 'eur',
              'product_data' => [
                'name' => 'Prix total des bougies:',
              ],
              'unit_amount' => $total*100,
            ],  
            'quantity' => 1,
          ]],
          'mode' => 'payment',
          'allow_promotion_codes' => true,
          'success_url' => $this->generateUrl('success', [], UrlGeneratorInterface::ABSOLUTE_URL),
          'cancel_url' => $this->generateUrl('cancel', [], UrlGeneratorInterface::ABSOLUTE_URL),
        ]);
      return new JsonResponse([ 'id' => $sessionStripe->id ]);
  }

  /**
  * @Route("/checkout/success", name="success")
  */
    public function success(){

      return $this->render('checkout/success.html.twig', [

      ]);
  }

  /**
  * @Route("/checkout/cancel", name="cancel")
  */
    public function cancel(){
      return $this->render('checkout/cancel.html.twig', [

      ]);
  }

  /**
  * @Route("/panier/add/{id}", name="panier_add")
  */

    public function add($id, panierService $panierService){

      $panierService->add($id);

      return $this->redirectToRoute('panier');

  }

  /**
   * @Route("/panier/remove/{id}", name="panier_remove")
   */

  public function remove($id, panierService $panierService){

    $panierService->remove($id);

      return $this->redirectToRoute('panier');

  }

  /**
   * @Route("/panier/retirer/{id}", name="panier_retirer")
   */

  public function retirer($id, panierService $panierService){

      $panierService->retirer($id);

      return $this->redirectToRoute('panier');
      
  }
  
    
}
