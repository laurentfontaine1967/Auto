<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use FOS\CKEditorBundle\Form\Type\CKEditorType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;

class ContactTypeForm extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
        ->add('mail', EmailType::class, [
            'label' => 'Entrer votre email',
            'attr' => [
                'placeholder' => 'Votre adresse mail...'
            ],
        ])
       
        ->add('sujet', TextType::class, [
            'label' => 'Sujet du message',
            'attr' => [
                'placeholder' => 'Sujet de votre message'
            ],
            'constraints' => [

                new Length([
                    'min' => 10,
                    'minMessage' => 'Le sujet doit contenir au minimum 10 caractères'
                ]),
            ],
        ])

        ->add('message', CKEditorType::class, [
            'label' => 'Contenu du message',
            'attr' => [
                'placeholder' => 'Contenu de votre message'
            ],
            'constraints' => [

                new Length([
                    'min' => 10,
                    'minMessage' => 'Le contenu doit avoir au minimum 10 caractères'
                ]),
            ],
        ]);
        
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            // Configure your form options here
        ]);
    }
}