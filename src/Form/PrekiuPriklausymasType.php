<?php

namespace App\Form;

use App\Entity\Kokybe;
use App\Entity\ParduotuvesPreke;
use App\Entity\PrekiuPriklausymas;
use Symfony\Component\DependencyInjection\Tests\Compiler\C;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class PrekiuPriklausymasType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $prekes = $options['prekes'];
        $kokybes = $options['kokybes'];
        $builder
            ->add('kiekis', NumberType::class)
            ->add('fkParduotuvesPreke', ChoiceType::class, array(
                'choices' => $prekes,
                'choice_label' => function (ParduotuvesPreke $entity = null){
                    return $entity ? $entity->getPavadinimas():'';}
            ))
            ->add('fkKokybe', ChoiceType::class, array(
                'choices' => $kokybes,
                'choice_label' => function (Kokybe $entity = null){
                    return $entity ? $entity->getPavadinimas():'';}
            ))
            ->add('save', SubmitType::class, array('label' => 'Patvirtinti',
                'attr' => array('class' => 'btn btn-primary btn-outline-primary')))
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => PrekiuPriklausymas::class,
            'prekes' => ParduotuvesPreke::class,
            'kokybes' => Kokybe::class
        ]);
    }
}
