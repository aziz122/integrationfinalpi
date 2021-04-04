<?php

namespace App\Form;

use App\Entity\Event;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\FileType;


class EventType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('Nom',TextType::class, array('attr' => array('class' => 'form-control')))
            ->add('Date_debut', DateType::class,array('years' => range(2020, 2030)))
            ->add('date_fin', DateType::class,array('years' => range(2020, 2030)))
            ->add('description',TextareaType::class, array('attr' => array('class' => 'form-control')))
            ->add('lieu',TextType::class, array('attr' => array('class' => 'form-control')))
            ->add('prix', IntegerType::class,array('attr' => array('class' => 'form-control')))
            ->add('nb_places', IntegerType::class,array('attr' => array('class' => 'form-control')))
            ->add('type',TextType::class, array('attr' => array('class' => 'form-control')))
            ->add('nb_signal', IntegerType::class,array('attr' => array('class' => 'form-control')))
            ->add('creator')
            ->add('file',FileType::class, array('attr' => array('class' => 'form-control')));

        ;}

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Event::class,
        ]);
    }

}
