<?php

namespace AppBundle\Controller;

use http\Env\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class LoginController extends Controller
{
    /**
     * @Route("/login" , name="login")
     */
    public function loginAction(AuthenticationUtils $authenticationUtils)
    {
        $errors = $authenticationUtils->getLastAuthenticationError();
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('login/login.html.twig', array(
            'errors' => $errors,
            'username' => $lastUsername
        ));
    }

    /**
     * @Route("/logout" , name="logout")
     */
    public function logoutAction(){

    }
}
