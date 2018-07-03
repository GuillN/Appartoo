<?php

namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\FormBuilderInterface;
use FOS\UserBundle\Form\Type\ProfileFormType as BaseProfileFormType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Security\Core\Validator\Constraints\UserPassword;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class ProfileFormType extends AbstractType{

    public function buildForm(FormBuilderInterface $builder, array $options) {
        $constraintsOptions = array(
            'message' => 'fos_user.current_password.invalid',
        );

        if (!empty($options['validation_groups'])) {
            $constraintsOptions['groups'] = array(reset($options['validation_groups']));
        }

        $builder
            ->add('username', null, array('label' => 'form.username', 'translation_domain' => 'FOSUserBundle', 'attr' => array('class' => 'form-control', 'style' => 'margin-bottom:15px')))
            ->add('email', EmailType::class, array('label' => 'form.email', 'translation_domain' => 'FOSUserBundle', 'attr' => array('class' => 'form-control', 'style' => 'margin-bottom:15px')))
            ->add('race', TextType::class, array('attr' => array('class' => 'form-control', 'style' => 'margin-bottom:15px')))
            ->add('status', TextType::class, array('label' => 'Comportement', 'attr' => array('class' => 'form-control', 'style' => 'margin-bottom:15px')))
            ->add('age', NumberType::class, array('label' => 'Âge', 'attr' => array('class' => 'form-control', 'style' => 'margin-bottom:15px')))
            ->add('planet', TextType::class, array('label' => "Planète d'origine", 'attr' => array('class' => 'form-control', 'style' => 'margin-bottom:15px')))
            ->add('current_password', PasswordType::class, array(
                'label' => 'form.current_password',
                'translation_domain' => 'FOSUserBundle',
                'mapped' => false,
                'constraints' => array(
                    new NotBlank(),
                    new UserPassword($constraintsOptions),
                ),
                'attr' => array(
                    'autocomplete' => 'current-password',
                    'class' => 'form-control',
                    'style' => 'margin-bottom:15px'
                ),
            ))
        ;

    }

    public function getParent() {
        return BaseProfileFormType::class;
    }


}