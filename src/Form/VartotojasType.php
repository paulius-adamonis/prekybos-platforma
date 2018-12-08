<?php

namespace App\Form;

use App\Entity\Vartotojas;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class VartotojasType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('vardas', TextType::class, ['label' => 'Vardas:'])
            ->add('pavarde', TextType::class, ['label' => 'Pavardė:'])
            ->add('elPastas', EmailType::class, ['label' => 'El. paštas:'])
            ->add('telNr', IntegerType::class, ['label' => 'Tel. nr.:'])
            ->add('plainPassword', RepeatedType::class, array(
                'type' => PasswordType::class,
                'first_options'  => array('label' => 'Slaptažodis:'),
                'second_options' => array('label' => 'Pakartoti slaptažodį:'),
            ))
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Vartotojas::class,
        ]);
    }
}
