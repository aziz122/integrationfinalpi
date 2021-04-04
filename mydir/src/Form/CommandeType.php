<?php

namespace App\Form;

use App\Entity\Commande;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\RadioType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
class CommandeType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('datecomm', DateTimeType::class,[
                'label'=>'Date',
                'format' => \IntlDateFormatter::SHORT,
                'input' => 'datetime',
                'widget' => 'single_text',
                'data' => new \DateTime("now")])







            ->add('modepaiement',ChoiceType::class,[
                'label'=>'Payment Method',
                    'choices'=>[
                        'At Delivery'=>'At Delivery',
                        'Online'=>'Online'

                        ]

                ]
            )


            ->add('addressecom',TextType::class,[

                'label'=>'Delivery address'

            ])

            ->add('numtel',NumberType::class,[

                'label'=>'Phone Number'])

            ->add('mail',EmailType::class,[

                'label'=>'Mail Address'

            ])
            ->add('prixtot', NumberType::class, array(

                )
            )
        ;



        ;



    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Commande::class,
        ]);
    }
}
