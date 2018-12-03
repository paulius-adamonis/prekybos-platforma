<?php

namespace App\Form;

use App\Entity\Klausimas;
use App\Entity\KlausimoTipas;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class KlausimasType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $questionsTypes = $options['questionsTypes'];
        $builder
            ->add('fkKlausimoTipas', ChoiceType::class, array(
                'choices' => $questionsTypes,
                'choice_label' => function (KlausimoTipas $entity = null) {
                    return $entity ? $entity->getPavadinimas() .'   -   '. $entity->getAprasas() : '';
                },
                'placeholder' => 'Pasirinkite klausimo tipą',
                'attr' => array(
                    'class' => 'custom-select d-block w-100'
                ),
                'label' => false

            ))
            ->add('klausimas', TextareaType::class, array(
                'label' => false,
                'attr' => array(
                    'placeholder' => 'Klausimas',
                    'class' => 'form-control rounded-0',
                    'style' => 'min-height: 100px; max-height: 300px; margin-top:15px;'
                )
            ))
            ->add('submit', SubmitType::class, array(
                'label' => 'Siųsti',
                'attr' => array(
                    'class' => 'btn btn-primary btn-outline-primary mt-2'
                )
            ))
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Klausimas::class,
            'questionsTypes' => KlausimoTipas::class
        ]);
    }
}
