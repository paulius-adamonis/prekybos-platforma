<?php

namespace App\Form;

use App\Entity\Sandelis;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class SandelisType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('adresas', TextType::class)
            ->add('save', SubmitType::class, array('label' => 'Patvirtinti',
                'attr' => array('class' => 'btn btn-primary btn-outline-primary')))
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Sandelis::class,
        ]);
    }
}
