<?php

namespace App\Controller;

use App\Entity\Users;
use App\Entity\Products;
use App\Form\RegistrationType;
use App\Form\RegistrationTypeProduct;
use App\Repository\UsersRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class SecurityController extends AbstractController
{

    /** 
    *   @Route ("/inscription", name="security_registration")
    **/
    public function registration(Request $request, EntityManagerInterface $manager, UserPasswordEncoderInterface $encoder, SessionInterface $session){

        $user= new Users(); 

        $form = $this->createForm(RegistrationType::class, $user);

        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            
            $hash = $encoder->encodePassword($user, $user->getPassword());
            $user->setCreatedAt(new \DateTime);
            $user->setPassword($hash);
            $user->setSession($session->getId());

            $manager->persist($user);
            $manager->flush();

            return $this->redirectToRoute('security_login'); 
        }
        
        return $this->render('security/inscription.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /** 
    *   @Route ("/login", name="security_login")
    **/

    public function login(AuthenticationUtils $authenticationUtils): Response {

        $error = $authenticationUtils->getLastAuthenticationError();
        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();
        return $this->render('security/login.html.twig', [
            'last_username' => $lastUsername,
            'error' => $error
            ]);
    }

    /**
     * @Route ("/deconnexion", name="security_logout")
     */

    public function logout(){
        
    }


    /**
     * @Route ("/admin", name="admin")
     */

    public function registrationProducts(Request $request, EntityManagerInterface $manager){

        $product= new Products(); 

        $form = $this->createForm(RegistrationTypeProduct::class, $product);

        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){

            $commentaire = $product->getCommentaire();
            $file = $product->getImage();
            $filename = md5(uniqid()).'.'.$file->guessExtension();
            $file->move($this->getParameter('upload_directory'), $filename);
            $product->setImage($filename);

            $manager->persist($product);
            $manager->flush();

            return $this->redirectToRoute('bougies'); 
        }
        
        return $this->render('security/admin.html.twig', [
            'form' => $form->createView()
        ]);
    }

}
