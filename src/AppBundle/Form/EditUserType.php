<?php

namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\FileType;


class EditUserType extends AbstractType {

    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options) {
        $builder
                ->add('name', TextType::class, array(
                    'label' => 'Nombre',
                    'required' => 'required',
                    'attr' => array(
                        'class' => 'form-control'
                    )
                ))
                ->add('surname', TextType::class, array(
                    'label' => 'Apellido',
                    'required' => 'required',
                    'attr' => array(
                        'class'=> 'form-control'
                    ))
                )
                ->add('username',TextType::class, array(
                    'label'=>'Nombre de Usuario',
                    'required'=> 'required',
                    'attr'=> array(
                        'class'=> 'form-control username'
                    ))
                )
                ->add('email',EmailType::class, array(
                    'label'=>'Email',
                    'required'=> 'required',
                    'attr'=> array(
                        'class'=> 'form-control'
                    ))
                        )
                ->add('password',PasswordType::class, array(
                    'label'=>'Password',
                    'required'=> 'required',
                    'attr'=> array(
                        'class'=> 'form-control'
                    ))
                  )
                ->add('imame', FileType::class, array(
                    'label' => 'Avatar',
                    'required' => false,
                    'data_class' => null,
                    'attr' => array(
                        'class' => 'form form-control preview',
                        'placeholder' => 'Subir Imagen',
                        'onchange'=> 'readURL(this);'
                    )
                ))                
                ->add('Actualizar', SubmitType::class, array(
                    'attr'=> array(
                        'class'=> "btn btn-success"
                    )
                ))
                ;
    }
    
    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'BackendBundle\Entity\User'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'backendbundle_user';
    }


}
