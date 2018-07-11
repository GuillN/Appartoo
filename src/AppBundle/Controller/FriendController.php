<?php

namespace AppBundle\Controller;

use AppBundle\AppBundle;
use FOS\UserBundle\Model\UserInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class FriendController extends Controller{

    /**
     * @Route("/friends", name="friends")
     */
    public function indexAction(Request $request/*, UserInterface $current_user*/){

        /*//create form
        $form = $this->createFormBuilder()
            ->add('name', TextType::class, array('label' => 'Nom', 'attr' => array('class' => 'form-control', 'style' => 'margin-bottom:15px')))
            ->add('search', SubmitType::class, array('label' => 'Ajouter', 'attr' => array('class' => 'btn btn-primary')))
            ->getForm();
        $form->handleRequest($request);

        $em = $this->getDoctrine()->getManager();
        $current_user = $this->getUser();
        if($form->isSubmitted() && $form->isValid()) {
            //get friend
            $username = $form['name']->getData();
            $userManager = $this->get('fos_user.user_manager');
            $user = $userManager->findUserBy(array('username' => $username));
            //if not exists
            if (null === $user) {
                $this->addFlash('error', "Cet utilisateur n'existe pas");
            }elseif ($current_user === $user){
                $this->addFlash('error', "Vous ne pouvez pas vous ajouter vous même!");
            }
            elseif($current_user->getFriends()->contains($user)) {
                $this->addFlash('error', "Cet utilisateur est deja votre ami");
            }else{
                //add friend
                $current_user->addFriend($user);
                $user->addFriend($current_user);
                $this->addFlash('notice', 'Ami ajouté');
            }
            $em->persist($current_user);
            $em->flush();
        }*/



        //get friends
        $current_user = $this->getUser();
        $friends = $current_user->getFriends();


        return $this->render('friend/index.html.twig',  array('friends' => $friends));
    }

    /**
     * @Route("/friend/add", name="friend_add")
     */
    public function addAction(){
        return $this->render('friend/add.html.twig');
    }

    public function addFriendAction($id){

        $em = $this->getDoctrine()->getManager();
        $userManager = $this->get('fos_user.user_manager');
        $friend = $userManager->findUserBy(array('id' => $id));
        $user = $this->getUser();
        if (null === $friend) {
            $this->addFlash('error', "Cet utilisateur n'existe pas");
            return $this->redirectToRoute('friend_add');
        }elseif ($user === $friend){
            $this->addFlash('error', "Vous ne pouvez pas vous ajouter vous même!");
            return $this->redirectToRoute('friend_add');
        }
        elseif($user->getFriends()->contains($friend)) {
            $this->addFlash('error', "Cet utilisateur est deja votre ami");
            return $this->redirectToRoute('friend_add');
        }else{
            //add friend
            $user->addFriend($friend);
            $friend->addFriend($user);
            $this->addFlash('notice', 'Ami ajouté');
            $em->persist($user);
            $em->flush();
            return $this->redirectToRoute('friends');
        }
    }

    /**
     * @Route("/friend/delete/{id}", name="friend_delete")
     */
    public function deleteAction($id){
        $em = $this->getDoctrine()->getManager();

        $friend = $em->getRepository('AppBundle:User')->find($id);
        $this->getUser()->removeFriend($friend);
        $friend->removeFriend($this->getUser());

        $em->flush();
        $this->addFlash(
            'notice',
            'Ami supprimé'
        );
        return $this->redirectToRoute('friends');
    }

    /**
     * @Route("/friend/details/{id}", name="friend_details")
     */
    public function detailsAction($id){
        $user = $this->getDoctrine()
            ->getRepository('AppBundle:User')
            ->find($id);
        return $this->render('friend/details.html.twig', array(
            'user' => $user
        ));
    }

    /**
     * @Route("/usersjson", name="users_json")
     * @Method({"GET"})
     */
    public function getUsersAction(Request $request)
    {
        $users = $this->getDoctrine()->getManager()
            ->getRepository('AppBundle:User')
            ->findBy(array(), array('username' => 'asc'));
        $formatted = [];
        foreach ($users as $user) {
            $formatted[] = [
                'id' => $user->getId(),
                'username' => $user->getUsername(),
                'email' => $user->getEmail(),
            ];
        }
        return new JsonResponse($formatted);
    }
}