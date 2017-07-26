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
use Symfony\Component\Security\Core\Validator\Constraints\UserPassword;

class ProfileFormType extends AbstractType
{

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $this->buildUserForm($builder, $options);
        $builder

            ->add('current_password', 'password', array(
                'label' => 'form.current_password',
                'translation_domain' => 'FOSUserBundle',
                'mapped' => false,
                'constraints' => new UserPassword(),
            ));
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => $this->class,
            'intention'  => 'profile',
        ));
    }
    public function getParent()
    {
        return 'fos_user_profile';
    }

    public function getName()
    {
        return 'user_profile';
    }

    /**
     * Builds the embedded form representing the user.
     *
     * @param FormBuilderInterface $builder
     * @param array                $options
     */
    protected function buildUserForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('username', null, array('label' => 'username', 'translation_domain' => 'FOSUserBundle'))

            ->add('nom', null, array('label' => 'nom', 'translation_domain' => 'FOSUserBundle'))
            ->add('prenom', null, array('label' => 'prenom', 'translation_domain' => 'FOSUserBundle'))


            ->add('email', 'email', array('label' => 'email', 'translation_domain' => 'FOSUserBundle'))
            ->add('bio', null, array('label' => 'Bio', 'translation_domain' => 'FOSUserBundle'))
            ->add('sexe','choice',array('choices'=> array('Masculin'=>'Masculin','Féminin'=>'Féminin')))
            ->add('nom', null, array('label' => 'nom', 'translation_domain' => 'FOSUserBundle'))
            ->add('current_password', 'password', array(
                'label' => 'saisir mot de passe courant','translation_domain' => 'FOSUserBundle',
                'mapped' => false,
                'constraints' => new UserPassword(),
            ));
    }
}
