<?php

namespace App\Form;

use App\Entity\Categorie;
use App\Entity\Formation;
use App\Entity\Playlist;
use DateTime;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class FormationType extends AbstractType {

    public function buildForm(FormBuilderInterface $builder, array $options): void {
        $builder
                ->add('publishedAt', DateType::class, [
                    'label' => 'Date de publication',
                    'widget' => 'single_text', // Utiliser un champ de texte unique
                    'data' => isset($options['data']) &&
                    $options['data']->getPublishedAt() != null ? $options['data']->getPublishedAt() : new DateTime('now'),
                    'html5' => true, // Utiliser le sélecteur de date natif HTML5
                    'required' => true,
                    'attr' => [
                        'placeholder' => 'jj/mm/aaaa', // Texte d'invite
                        'onkeydown' => 'return false;', // Empêche la saisie de texte
                    ],
                ])
                ->add('title', TextType::class, [
                    'label' => 'Titre',
                    'required' => true,
                        ]
                )
                ->add('description')
                ->add('categories', EntityType::class, [
                    'class' => Categorie::class,
                    'choice_label' => 'name',
                    'multiple' => true,
                    'expanded' => true,
                    'required' => false,
                ])
                
                ->add('videoId', TextType::class, [
                'required' => false,
           
            ])
                ->add('playlist', EntityType::class, [
                    'class' => Playlist::class,
                    'choice_label' => 'name',
                    'multiple' => false,
                    'required' => true,
                ])
                ->add('submit', SubmitType::class, [
                    'label' => 'Enregistrer'
                ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void {
        $resolver->setDefaults([
            'data_class' => Formation::class,
        ]);
    }
}
