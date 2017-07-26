<?php

/*
 * This file is part of the FOSUserBundle package.
 *
 * (c) FriendsOfSymfony <http://friendsofsymfony.github.com/>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace BestTrip\UserBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class RegistrationFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            
            ->add('nom', null, array('label' => 'nom', 'translation_domain' => 'FOSUserBundle'))
            ->add('prenom', null, array('label' => 'prenom', 'translation_domain' => 'FOSUserBundle'))
            ->add('username', null, array('label' => 'username', 'translation_domain' => 'FOSUserBundle'))
            ->add('email', 'email', array('label' => 'email', 'translation_domain' => 'FOSUserBundle'))
            ->add('bio', null, array('label' => 'Bio', 'translation_domain' => 'FOSUserBundle'))
            ->add('sexe','choice',array('choices'=> array('Masculin'=>'Masculin','Féminin'=>'Féminin')))
            ->add('plainPassword', 'repeated', array(
               
                'type' => 'password',
                'options' => array('translation_domain' => 'FOSUserBundle'),
                'first_options' => array('label' => 'password'), 
                
                'second_options' => array('label' => 'password_confirmation'),
                'invalid_message' => 'password.mismatch',
            ))
                
        ;
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'intention'  => 'registration',
            //
            'csrf_protection' => false,
            'csrf_field_name' => '_token',
        ));
    }
    public function getParent()
    {
        return 'fos_user_registration';
    }

    public function getName()
    {
        return 'user_registration';
    }
}
