<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use AppBundle\Entity\Name;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Request;

class NamesController extends Controller
{
    /**
     * @Route("/names", name="names")
     */
    public function indexAction(Request $request)
    {
        $name = new Name;
        $form = $this->createFormBuilder($name)
            ->add('name', TextType::class, array('attr' => array('class' => 'form-control', 'style' => 'margin-bottom:15px')))
            ->add('save', SubmitType::class, array('label' => 'Add Name', 'attr' => array('class' => 'btn btn-primary')))
            ->getForm();
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {
            $label = $form['name']->getData();
            $name->setName($label);
            $em = $this->getDoctrine()->getManager();
            $em->persist($name);
            $em->flush();

            $this->addFlash('notice', 'Name Added');
        }

        $names = $this->getDoctrine()
            ->getRepository('AppBundle:Name')
            ->findAll();

        return $this->render('names/names.html.twig', array('form' => $form->createView(), 'names' => $names));

    }

    /**
     * @Route("/name/delete/{id}", name="name_delete")
     */
    public function deleteAction($id){
        $em = $this->getDoctrine()->getManager();
        $name = $em->getRepository('AppBundle:Name')->find($id);
        $em->remove($name);
        $em->flush();

        $this->addFlash(
            'notice',
            'Name Removed'
        );
        return $this->redirectToRoute('names');
    }
}