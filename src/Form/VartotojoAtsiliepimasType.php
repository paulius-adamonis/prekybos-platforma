<?php

namespace App\Form;

use App\Entity\VartotojoAtsiliepimas;
use Brokoskokoli\StarRatingBundle\Form\RatingType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class VartotojoAtsiliepimasType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('aprasymas', TextareaType::class, array(
                'label' => false,
                'attr' => array(
                    'placeholder' => 'Parašykite atsiliepimą apie šį vartotoją',
                    'class' => 'form-control rounded-0',
                    'style' => 'min-height: 100px; max-height: 300px; margin-top:15px;'
                )
            ))
            ->add('reitingas', RatingType::class, [
                'label' => 'Uždėkite vertinimą:'
            ])
            ->add('submit', SubmitType::class, array(
                'label' => 'Patalpinti atsiliepimą',
                'attr' => array(
                    'class' => 'btn btn-primary btn-outline-primary mt-2'
                )
            ))
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => VartotojoAtsiliepimas::class,
        ]);
    }
}
