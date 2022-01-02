<?php

namespace App\Form;

use App\Entity\Annonces;
use Symfony\Component\Form\AbstractType;
use FOS\CKEditorBundle\Form\Type\CKEditorType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class AnnoncesType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title', TextType::class,[
                'label' =>'Titre'
            ])

            ->add('descriptionsimple', TextType::class,[
                'label' =>'description sommaire'
            ])

            ->add('content', CKEditorType::class, [
                'label' => 'Description'
            ])
            
            ->add('price', TextType::class, [
                'label' => 'Prix'
            ])


             //ajout champs image ( mapped=false)
             ->add('images',FileType::class,[
            'label' =>false,
            'multiple' =>true,
            'mapped' =>false,
            'required'=> false

             ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Annonces::class,
        ]);
    }
}