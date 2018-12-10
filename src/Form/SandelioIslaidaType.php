<?php

namespace App\Form;

use App\Entity\Islaidos;
use App\Entity\IslaidosTipas;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SandelioIslaidaType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $tipai = $options['tipai'];
        $builder
            ->add('kaina', NumberType::class)
            ->add('fkIslaidosTipas',  ChoiceType::class, array(
                'choices' => $tipai,
                'choice_label' => function (IslaidosTipas $entity = null){
                    return $entity ? $entity->getPavadinimas():'';}
            ))
            ->add('save', SubmitType::class, array('label' => 'Patvirtinti',
                'attr' => array('class' => 'btn btn-primary btn-outline-primary')))
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Islaidos::class,
            'tipai' => IslaidosTipas::class
        ]);
    }
}
