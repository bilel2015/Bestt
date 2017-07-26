<?php

namespace BestTrip\UserBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class UserType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('nom', null, array('label' => 'nom', 'translation_domain' => 'FOSUserBundle'))
            ->add('prenom', null, array('label' => 'prenom', 'translation_domain' => 'FOSUserBundle'))
            ->add('dateNaissance', 'birthday')
            ->add('email', 'email', array('label' => 'email', 'translation_domain' => 'FOSUserBundle'))
            ->add('bio', null, array('label' => 'Bio', 'translation_domain' => 'FOSUserBundle'))
            ->add('sexe','choice',array('choices'=> array('Masculin'=>'Masculin','Féminin'=>'Féminin')))
        ;
    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'BestTrip\UserBundle\Entity\User',
            'csrf_protection' => false,
            'csrf_field_name' => '_token',
            // a unique key to help generate the secret token
            'intention' => 'task_item',
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'besttrip_userbundle_user';
    }
}
