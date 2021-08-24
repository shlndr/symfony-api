<?php

namespace Application\Sonata\UserBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UserType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name')
            ->add('profilePic')
            ->add('email')
            ->add('about')
//            ->add('licenseType')
//            ->add('license')
            ->add('username')
            ->add('plainPassword')
            //->add('inviteCode')
            //->add('otpVerified')
            //->add('otp')
//            ->add('groups')
        ;
    }
    
    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Application\Sonata\UserBundle\Entity\User'
        ));
    }
}
