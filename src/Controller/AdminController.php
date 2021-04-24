<?php

namespace App\Controller;

use App\Entity\CommandeQuantity;
use App\Entity\Commandes;
use App\Entity\Users;
use App\Entity\Products;
use App\Form\EditUserType;
use App\Form\ModifProductType;
use App\Form\RegistrationType;
use App\Repository\UsersRepository;
use App\Form\RegistrationTypeProduct;
use App\Repository\CommandeQuantityRepository;
use App\Repository\CommandesRepository;
use App\Repository\ProductsRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class AdminController extends AbstractController
{
    /**
     * @Route("admin/utilisateurs", name="admin_utilisateurs")
     */
    public function usersList(UsersRepository $users)
    {

        return $this->render('admin/users.html.twig', [
            'users' => $users->findAll(),
        ]);
    }

    /**
     * @Route("admin/listeCmd", name="listeCmd")
     */
    public function listeCmd(CommandeQuantityRepository $commandeQuantity, CommandesRepository $commandes, ProductsRepository $product)
    {
        return $this->render('admin/listeCmd.html.twig', [
            'commandes' => $commandes->findAll(),
        ]);
    }

    /**
     * @Route("admin/listeCmd/{id}", name="archiveCmd")
     */

     public function archiveCmd(CommandesRepository $commandesRepository, $id, EntityManagerInterface $manager){

        $commandesRepository= $this->getDoctrine()->getRepository('App\Entity\Commandes')->find($id);
        $commandesRepository->setArchive(true);
        $manager->persist($commandesRepository);
        $manager->flush();

        return $this->redirectToRoute('listeCmd');
     }

     /**
      * @Route("admin/listeArchive", name="listeArchive")
      */

     public function listeArchive(CommandesRepository $commandes){

        return $this->render('admin/listeArchive.html.twig', [
            'commandes' => $commandes->findAll(),
        ]);
     }

     /**
      * @Route("admin/listeArchive/{id}", name="suppArchive")
      */

      public function suppArchive(Commandes $commandes){


        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->remove($commandes);
        $entityManager->flush();
   
        return $this->redirectToRoute('listeArchive');
      }

    /**
     * @Route("admin/showCmd/{id}", name="showCmd")
     */
    public function showCmd($id, CommandeQuantityRepository $commandeQuantity, CommandesRepository $commandes, UsersRepository $user ){
        
        return $this->render('admin/showCmd.html.twig',[
            'commande' => $commandeQuantity->findBy(['numCmd' => $id]),
            'numCmde' => $id
        ]);
    }

 /**
 * @Route("admin/utilisateurs/modifier/{id}", name="admin_modifier_utilisateur")
 */
public function editUser(Users $user, Request $request)
{
    $form = $this->createForm(EditUserType::class, $user);
    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->persist($user);
        $entityManager->flush();

        $this->addFlash('message', 'Utilisateur modifié avec succès');
        return $this->redirectToRoute('admin_utilisateurs');
    }
    
    return $this->render('admin/edituser.html.twig', [
        'userForm' => $form->createView(),
    ]);
}

/**
 * @Route("admin/modif-produit/{id}", name="modif_produit")
 */

 public function modifProduit(Products $product, Request $request){

    $form = $this->createForm(ModifProductType::class, $product);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){

            $manager = $this->getDoctrine()->getManager();
            

            $manager->flush();

            return $this->redirectToRoute('bougies'); 
        }
        
        return $this->render('admin/modifProduit.html.twig', [
            'form' => $form->createView()
        ]);
    }
    
    /**
   * @Route("/admin/suppression/{id}", name="suppression_produit")
   */

  public function suppression(Products $product){

    $entityManager = $this->getDoctrine()->getManager();

    $entityManager->remove($product);

    $entityManager->flush();
   
    return $this->redirectToRoute('bougies');

  }

}
