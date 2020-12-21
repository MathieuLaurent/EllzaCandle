<?php

namespace App\Controller;

use App\Entity\Users;
use App\Form\ModifInfoType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class UserController extends AbstractController
{
    /**
    * @Route ("/user/informations/{id}", name="informations")
    */

    public function informations($id, Users $user, Request $request){

        $form = $this->createForm(ModifInfoType::class, $user);

            $form->handleRequest($request);

            if($form->isSubmitted() && $form->isValid()){
                

                $this->addFlash('success', 'Modification validée! :)');
                
                $manager = $this->getDoctrine()->getManager();
                

                $manager->flush();

            }
            
            return $this->render('user/information.html.twig', [
                'form' => $form->createView()
            ]);

    }
}
