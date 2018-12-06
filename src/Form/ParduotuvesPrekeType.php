<?php

namespace App\Form;

use App\Entity\ParduotuvesPreke;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ParduotuvesPrekeType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('pavadinimas', TextType::class, ['label' => 'Pavadinimas: '])
            ->add('aprasymas', TextareaType::class, ['label' => 'Aprašymas: '])
            ->add('nuotrauka', FileType::class, [
                'label' => 'Nuotrauka: ',
                'mapped' => false,
                'required' => false,
                'validation_groups' => $options
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => ParduotuvesPreke::class,
        ]);
    }
}
