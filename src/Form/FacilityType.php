<?php

namespace App\Form;

use App\Entity\Facility;
use App\Entity\Type;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Length;

class FacilityType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options) : void
    {
        $builder
            ->add('legalEntity', TextType::class)
            ->add('legalStatus', TextType::class)
            ->add('capital', NumberType::class)
            ->add('adress', TextType::class)
            ->add('matriculation', TextType::class, ['constraints' => [
                new Length([
                    'min' => 14,
                    'minMessage' => 'Le siret doit faire {{ limit }} caractères',
                    'max' => 4096])]])
            ->add('managerFirstname', TextType::class)
            ->add('managerLastname', TextType::class)
            ->add('role', TextType::class)
            ->add('matriculationCity', TextType::class)
            ->add('city', TextType::class)
            ->add('zip', TextType::class)
            ->add('country', TextType::class)
            ->add('type', EntityType::class, [
                'choice_label' => 'name',
                'class' => Type::class
            ])
            ->add('user', EntityType::class, [
                'choice_label' => 'email',
                'class' => Type::class
            ]);
    }

    public function configureOptions(OptionsResolver $resolver) : void
    {
        $resolver->setDefaults([
            'data_class' => Facility::class,
        ]);
    }
}
