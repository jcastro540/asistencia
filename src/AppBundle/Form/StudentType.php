<?php

namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class StudentType extends AbstractType {

    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options) {
        $builder
                ->add('name', TextType::class,array(
                    'label'=> 'Nombre',
                    'attr'=> array(
                        'class'=>'form-control'
                    )
                ))
                ->add('email', EmailType::class, array(
                    'label'=>'Email',
                    'attr'=> array(
                        'class'=>'form-control'
                    )
                ))
               
                ->add('Guardar', SubmitType::class, array(
                    "attr" => array(
                        "class" => "btn btn-success"
                    )
                ))
        ;
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver) {
        $resolver->setDefaults(array(
            'data_class' => 'BackendBundle\Entity\Student'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix() {
        return 'backendbundle_student';
    }

}
