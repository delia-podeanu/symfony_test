<?php

namespace App\Form;

use App\Entity\User;
use App\Entity\Type;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;




class UserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->setMethod('POST')
            ->add('email', EmailType::class, [
                'label' => 'Email',
                'attr' => array('class' => 'form-control mb-2')
            ])
            ->add('password', PasswordType::class, [
                'label' => 'Password',
                'attr' => array('class' => 'form-control mb-2')
            ])
            ->add('submit', SubmitType::class, [
                'attr' => array('class' => 'btn btn-primary my-2')
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
