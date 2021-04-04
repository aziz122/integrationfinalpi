<?php

namespace App\Form;

use App\Entity\Exposee;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ExposeeType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('Photos', FileType::class, [
                'label' => false,
                'multiple' => true,
                'mapped'=> false,
                'required' =>false
            ])
            ->add('dateC',DateType::class,[
                'data' => new \DateTime(),
            ])
            ->add('nom')
            ->add('description')
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Exposee::class,
        ]);
    }
}
