<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class UsersController extends Controller
{
    /**
     * @Route("/users", name="user_list")
     */
    public function indexAction(Request $request)
    {
        $users = $this->getDoctrine()
            ->getRepository('AppBundle:User')
            ->findAll();

        return $this->render('users/index.html.twig', array('users' => $users));
    }

    /**
     * @Route("/user/delete/{id}", name="user_delete")
     */
    public function deleteAction($id){
        $em = $this->getDoctrine()->getManager();
        $user = $em->getRepository('AppBundle:User')->find($id);
        $em->remove($user);
        $em->flush();

        $this->addFlash(
            'notice',
            'Utilisateur supprimé'
        );
        return $this->redirectToRoute('user_list');
    }
}
