<?php

namespace AppBundle\Form;

use AppBundle\Entity\Experience;
use AppBundle\Entity\Reference;
use AppBundle\Entity\Service;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

class ReferenceType extends AbstractType
{
    /**
     * @var User
     */
    private $user;

    public function __construct(TokenStorageInterface $storage)
    {
        $this->user = $storage->getToken()->getUser();
    }

    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $supplier = $this->user->getSupplier();
        $builder
            ->add('name', TextType::class, [
                'label' => 'Name'
            ])
            ->add('companyName', TextType::class, [
                'label' => 'Company name'
                ])
            ->add('title', TextType::class, [
                'label' => 'Job Title'
            ])
            ->add('email', TextType::class, [
                'label' => 'Email'
            ])
            ->add('workPhone', TextType::class, [
                'label' => 'Best Contact Number'
            ]);
            //->add('mobilePhone')
        if ($supplier->isOutSourcing()) {
            $builder
                ->add('functions', ChoiceType::class, [
                    'choices' => array_combine(Service::getFunctions(), Service::getFunctions()),
                    'placeholder' => 'Please select functions',
                    'multiple' => true,
                    'attr' => [
                        'class' => 'select2',
                        'data-placeholder' => 'Please select functions',
                    ]
                ]);
        }
        if ($supplier->isVirtualAssistant()) {
            $builder
                ->add('functions', ChoiceType::class, [
                    'choices' => array_combine(Service::getFunctions(), Service::getFunctions()),
                    'placeholder' => 'Please select the VA functions you performed for this client',
                    'multiple' => true,
                    'attr' => [
                        'class' => 'select2',
                        'data-placeholder' => 'Please select the VA functions you performed for this client',
                    ]
                ]);
        }
        $builder
            // ->add('campaign', TextType::class, [
            //     'label' => 'Campaign Name',
            //     'attr' => [
            //         'placeholder' => 'What is the campaign called internally?'
            //     ]
            // ])
         
            ->add('lengthOFtimes', ChoiceType::class, [
                'label' => 'Length of time they were a customer?',
                'choices' => array_combine(Reference::getlengthOFtimes_S(), Reference::getlengthOFtimes_S()),
                'placeholder' => 'Please select',
                'attr' => [
                    'class' => 'select2',
                    'data-placeholder' => 'Please select',
                ]
            ])
            ->add('campaignDescription', TextareaType::class, [
                'label' => 'Notes',
                'required' => false,
                'attr' => [
                    'placeholder' => 'Enter any notes about the work you did with the client that you would like to mention',
                    'rows' => 3,
                    'class' => 'resize-vertically'
                ]
            ]);
        $builder->addEventListener(FormEvents::POST_SET_DATA, function (FormEvent $event) {
            $reference = $event->getData();
            $form = $event->getForm();

            if ($reference->getType() === Reference::TYPE_PAST) {
                $form->add('cessationReason', TextareaType::class, [
                    'attr' => [
                        'placeholder' => 'Please describe why they are no longer your customer',
                        'rows' => 3,
                        'class' => 'resize-vertically'
                    ]
                ]);
            }
        });

    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AppBundle\Entity\Reference'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'appbundle_reference';
    }


}
