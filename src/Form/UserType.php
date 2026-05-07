<?php

namespace App\Form;

use App\Entity\Club;
use App\Entity\Team;
use App\Entity\User;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('firstname')
            ->add('lastname')
            ->add('birthdate')
            ->add('phone')
            // ->add('photoUrl')
            ->add('role', ChoiceType::class, [
                'label' => 'Role',
                'choices' => [
                    'Administrateur' => 'ADMIN',
                    'Coach'          => 'COACH',
                    'Joueur'         => 'PLAYER',
                    'Parent'         => 'PARENT',
                ]
            ])
            ->add('status', ChoiceType::class, [
                'label' => 'Status',
                'choices' => [
                    'Actif' => 'ACTIVE',
                    'Inactif' => 'INACTIVE',
                    'Suspendu' => 'SUSPENDED'
                ]
            ])
            ->add('emergencyContactName')
            ->add('emergencyContactPhone')
            ->add('email')
            ->add('team', EntityType::class, [
                'class' => Team::class,
                'choice_label' => 'name',
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
