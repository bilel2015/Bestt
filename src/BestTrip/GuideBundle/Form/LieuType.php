<?php

namespace BestTrip\GuideBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class LieuType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('adresse')
            ->add('description')
            ->add('nom')
            ->add('histoire')
            ->add('nombreEtoiles')
            ->add('specialite')
            ->add('isHotel')
            ->add('isResto')
            ->add('isMonument')
            ->add('ville')
        ;
    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'BestTrip\GuideBundle\Entity\Lieu',
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
        return 'besttrip_guidebundle_lieu';
    }
}
