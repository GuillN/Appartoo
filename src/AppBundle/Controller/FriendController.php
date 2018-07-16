<?php

namespace AppBundle\Controller;

use AppBundle\AppBundle;
use AppBundle\Entity\User;
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
use AppBundle\Form\Type\UserType;

class FriendController extends FOSRestController implements ClassResourceInterface{

    /**
     * Gets friends of User
     *
     *
     * @return mixed
     * @Route("/friendlist")
     * @Method("GET")
     *
     */
    public function getAction(){
        $id = $this->getUser()->getId();
        return $this->get('crv.doctrine_entity_repository.user')->createFindFriendsQuery($id)->getResult();
    }

    /**
     * Get all users id and username
     *
     * @Route("/friends")
     * @Method("GET")
     */
    public function cgetAction(){
        return $this->get('crv.doctrine_entity_repository.user')->createFindAllQuery()->getResult();
    }

    /**
     * New user
     *
     * @Route("/friend")
     * @Method("POST")
     * @param Request $request
     * @return User
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function postAction(Request $request)
    {
        $user = new User();
        $encoder = $this->get('security.password_encoder');
        $encoded = $encoder->encodePassword($user, $user->getPlainPassword());
        $user->setPassword($encoded);

        $em = $this->get('doctrine.orm.entity_manager');
        $user->setUsername($request->request->get('username'));
        $user->setEmail($request->request->get('email'));
        $em->persist($user);
        $em->flush();
        return $user;

    }

    /**
     * Add friend
     *
     * @param $id
     *
     */

    public function addFriendAction($id){

        $em = $this->getDoctrine()->getManager();
        /*$userManager = $this->get('fos_user.user_manager');
        $friend = $userManager->findUserBy(array('id' => $id));*/
        $friend = $this->getDoctrine()->getRepository('AppBundle:User')->find($id);

        $user = $this->getUser();
        if (null === $friend) {
            /*$this->addFlash('error', "Cet utilisateur n'existe pas");
            return $this->redirectToRoute('friend_add');*/
        }elseif ($user === $friend){
            /*$this->addFlash('error', "Vous ne pouvez pas vous ajouter vous même!");
            return $this->redirectToRoute('friend_add');*/
        }
        elseif($user->getFriends()->contains($friend)) {
            /*$this->addFlash('error', "Cet utilisateur est deja votre ami");
            return $this->redirectToRoute('friend_add');*/
        }else{
            //add friend
            $user->addFriend($friend);
            $friend->addFriend($user);
            $this->addFlash('notice', 'Ami ajouté');
            $em->persist($user);
            $em->flush();

        }
        return;
    }

    /**
     *
     *
     */
    public function deleteAction($id){
        $em = $this->getDoctrine()->getManager();
        $friend = $this->getDoctrine()
            ->getRepository('AppBundle:User')
            ->find($id);
        $this->getUser()->removeFriend($friend);
        $friend->removeFriend($this->getUser());

        $em->flush();
        $this->addFlash(
            'notice',
            'Ami supprimé'
        );
        return;
    }

    /**
     * @Route("/friend/details/{id}", name="friend_details")
     * @Method("GET")
     */
    public function detailsAction($id){

        return $this->get('crv.doctrine_entity_repository.user')->createFindInfoQuery($id)->getSingleResult();
    }
}