<?php

namespace App\Form;

use App\Entity\PrekiuUzsakymas;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PrekiuUzsakymasType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('kiekis', IntegerType::class, ['label' => 'Kiekis: ', 'required' => true])
            ->add('sandelis', HiddenType::class, ['mapped' => false, 'required' => true])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => PrekiuUzsakymas::class,
        ]);
    }
}
