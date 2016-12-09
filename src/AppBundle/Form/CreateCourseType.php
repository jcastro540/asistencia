<?php

namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;

class CreateCourseType extends AbstractType {

    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options) {
        $builder
                ->add('name', ChoiceType::class, array(
                    'label' => 'Nombre del Curso',
                    'choices'=>array(
                        'HTML5 y CSS3'=>'HTML5 Y CSS3',
                        'JavaScript'=>'JavaScript',
                        'Diseño de Interfaces'=>'Diseño de Interfaces',
                        'WordPress Básico'=>'WordPress Básico',
                        'WordPress Avanzado'=>'WordPress Avanzado',
                        'Gerencia de Proyectos Web'=> 'Gerencia de Proyectos Web',
                        'Desarrollo Web con Laravel' => 'Desarrollo Web con Laravel'
                    ),
                    'attr' => array(
                        'class' => 'form-control'
                    )
                ))
                ->add('startdate', DateType::class, array(
                    'label'=>'Fecha de Inicio',
                   // 'format'=> 'integer',
                    'widget' => 'single_text',
                    // do not render as type="date", to avoid HTML5 date pickers
                    //'html5' => false,
                    // add a class that can be selected in JavaScript
                    'attr' => array(
                        'class' => 'date-picker fechaInicio form-control'
                    )                    
                ))
                ->add('enddate', DateType::class, array(
                    'label'=>'Fecha de Culminación',
                    'widget' => 'single_text',
                    //'html5' => false,
                    'attr' => array(
                        'class' => 'date-picker fechaInicio form-control'
                    )
                ))
                ->add('type', ChoiceType::class, array(
                    'label'=> 'Tipo de Curso',
                    
                    'choices'=>array(
                        'Vespertino'=>'Vespertino',
                        'Nocturno'=>'Nocturno',
                        'Sábatino'=>'Sábatino'
                    ),
                    'expanded'=>true,
                    'multiple'=>false,
                ))
                ->add('numClass', ChoiceType::class, array(
                    'label'=>'Número de Clases',
                    'choices'=> array(
                        '3'=>3,
                        '4'=>4,
                        '5'=>5,
                        '6'=>6,
                        '7'=>7,
                        '8'=>8,
                        '9'=>9,
                        '10'=>10,
                        '11'=>11,
                        '12'=>12
                    ),
                    'attr'=>array(
                        'class'=>'form-control'
                    )
                ))
                ->add('user', EntityType::class, array(
                    'class' => 'BackendBundle:User',
                    // use the User.username property as the visible option string
                    'choice_label' => 'username',
                        // used to render a select box, check boxes or radios
                        // 'multiple' => true,
                        // 'expanded' => true,
                    'label' => 'Profesor',
                    'attr' => array(
                        'class' => 'form-control'
                    )
                ))
                ->add('Guardar', SubmitType::class, array(
                    'attr'=> array(
                        'class'=> "btn btn-success"
                    )
                ))
                ;
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver) {
        $resolver->setDefaults(array(
            'data_class' => 'BackendBundle\Entity\Course'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix() {
        return 'backendbundle_course';
    }

}
