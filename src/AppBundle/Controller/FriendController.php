<?php

namespace AppBundle\Controller;

use AppBundle\AppBundle;
use Doctrine\DBAL\Schema\View;
use EXSyst\Component\Swagger\Response;
use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\Routing\ClassResourceInterface;
use FOS\UserBundle\Model\UserInterface;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class FriendController extends FOSRestController implements ClassResourceInterface {

    /**
     * Gets friends of User
     *
     * @param int $id
     * @return mixed
     * @Route("/friend/{id}")
     * @Method("GET")
     * @ApiDoc(
     *     output="AppBundle\Entity\User",
     *     statusCodes={
     *         200 = "Returned when successful",
     *         404 = "Return when not found"
     *     }
     * )
     */
    public function getAction($id){
        return $this->get('crv.doctrine_entity_repository.user')->createFindFriendsQuery($id)->getSingleResult();
    }

    /**
     * @Route("/friends")
     * @Method("GET")
     */
    public function cgetAction(){
        return $this->get('crv.doctrine_entity_repository.user')->createFindAllQuery()->getResult();
    }



    public function deleteAction($id){

    }



    /**
     * @Route("/friends_old", name="friends")
     */
    public function indexAction(Request $request){

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
     * @Route("/friend/delete_old/{id}", name="friend_delete")
     */
    public function deleteOldAction($id){
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