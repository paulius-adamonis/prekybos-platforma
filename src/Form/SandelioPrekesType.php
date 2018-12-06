<?php

namespace App\Form;

use App\Entity\ParduotuvesPreke;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;


class SandelioPrekesType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('pavadinimas', TextType::class)
            ->add('aprasymas', TextType::class)
            ->add('nuotrauka', FileType::class, array(
                'required' => false
            ))
            ->add('ikelimoData', DateType::class)
            ->add('save', SubmitType::class, array('label' => 'Patvirtinti',
                'attr' => array('class' => 'btn btn-primary btn-outline-primary')))
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => ParduotuvesPreke::class,
        ]);
    }
}
