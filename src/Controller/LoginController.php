<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
/**
 * Authentification
 */
class LoginController extends AbstractController
{
    /**
     * Affichage de la page de connexion 
     * @param AuthenticationUtils $authenticationUtils
     * @return Response
     */
    #[Route('/login', name: 'app_login')]
    public function index(AuthenticationUtils $authenticationUtils): Response
    {
        //recupération si erreur
        $error = $authenticationUtils->getLastAuthenticationError();
        //recupération éventuelle du dernier nom de login utilisé
        $lastUsername = $authenticationUtils->getLastUsername();
        return $this->render('login/index.html.twig', [
            'last_username'=> $lastUsername,
            'error'=>$error
        ]);
    }
    
    /**
     * Gestion de la deconnexion
     */
    #[Route('/logout',name:'logout')]
    public function logout(){
        
    }
}
