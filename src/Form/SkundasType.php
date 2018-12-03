<?php

namespace App\Form;

use App\Entity\Skundas;
use App\Entity\SkundoTipas;
use App\Form\DataTransformer\NumberToUserTransformer;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SkundasType extends AbstractType
{
    private $transformer;

    public function __construct(NumberToUserTransformer $transformer)
    {
        $this->transformer = $transformer;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $complaints = $options['complaints'];
        $builder
            ->add('fkSkundoTipas', ChoiceType::class, array(
                'choices' => $complaints,
                'choice_label' => function (SkundoTipas $entity = null) {
                    return $entity ? $entity->getPavadinimas() .'   -   '. $entity->getAprasas() : '';
                },
                'placeholder' => 'Pasirinkite skundo tipą',
                'attr' => array(
                    'class' => 'custom-select d-block w-100'
                ),
                'label' => false

            ))
            ->add('fkGavejas', TextType::class, array(
                'label' => false,
                'attr' => array(
                    'placeholder' => 'Skundžiamo gaidžio ID',
                    'class' => 'form-control'
                )
            ))
            ->add('skundas', TextareaType::class, array(
                'label' => false,
                'attr' => array(
                    'placeholder' => 'Skundas',
                    'class' => 'form-control rounded-0',
                    'style' => 'min-height: 100px; max-height: 300px; margin-top: 15px;'
                )
            ))
            ->add('submit', SubmitType::class, array(
                'label' => 'Pateikti',
                'attr' => array(
                    'class' => 'btn btn-primary btn-outline-primary mt-2'
                )
            ))

        ;

        $builder->get('fkGavejas')
            ->addModelTransformer($this->transformer);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Skundas::class,
            'complaints' => SkundoTipas::class
        ]);
    }
}
