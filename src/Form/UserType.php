<?php

namespace App\Form;

use App\Entity\User;
use App\Entity\UserInfo;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;

class UserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('email')
            
            // FIX: roles must be a multiple-choice field
            ->add('roles', ChoiceType::class, [
                'choices' => [
                    'Admin' => 'ROLE_ADMIN',
                    'Client' => 'ROLE_USER',
                ],
                'multiple' => true,   // roles is an array
                'expanded' => false,  // dropdown
            ])

            // OPTIONAL: If you don't want to show hashed password, remove this
            ->add('password', PasswordType::class, [
                'required' => false,
                'mapped' => false, // avoid replacing password unless user types a new one
            ])

            ->add('isVerified')

            ->add('userInfo', EntityType::class, [
                'class' => UserInfo::class,
                'choice_label' => 'id',
                'required' => false,
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
