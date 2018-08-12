<?php

namespace AppBundle\Form;

use AppBundle\Entity\Customer;
use AppBundle\Entity\Location;
use AppBundle\Entity\Service;
use AppBundle\Entity\User;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\PercentType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

class CustomerType extends AbstractType
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
                'label' => 'Name',
                'required' => false,
                'attr' => [                    
                    'placeholder' => 'Optional: Business Name',
                ],
            ])
            ->add('industryVertical', ChoiceType::class, [
                'label' => 'Industry vertical',
                'choices' => array_combine(Customer::getIndustryVerticals(), Customer::getIndustryVerticals()),
                'placeholder' => 'Please select their industry vertical',
                'attr' => [
                    'class' => 'select2',
                    'data-placeholder' => 'Please select their industry vertical',
                ],
            ])
            ->add('functions', ChoiceType::class, [
                'choices' => array_combine(Service::getFunctions(), Service::getFunctions()),
                'label' => 'Functions (choose all functions that apply to this customer)',
                // 'placeholder' => 'Please select the service(s) you supply for this customer',
                'multiple' => true,
                'attr' => [
                    'class' => 'select2',
                    'data-placeholder' => ' Click here to select (you can add multiple)',
                ],
            ]);
        if ($supplier->isOutSourcing()) {
            $builder
                ->add('totalSeats', IntegerType::class, [
                    'label' => 'Total Active seats    ',                    
                    'attr'=> [
                        'help'=>'text help',
                        'placeholder' => 'Please enter number',                     
                    ],
                ])
                // ->add('percentageOfBusiness', PercentType::class, [
                //     'label' => 'Percentage of Current Total Business',
                //     'scale' => 2,
                // ]);
                ->add('percentageOfBusiness', ChoiceType::class, [
                    'label' => '% of Current Business',
                    'choices' => array_combine(Customer::getPercentageOfBusiness_S(), Customer::getPercentageOfBusiness_S()),
                    'placeholder' => 'Please select % of Current Total Business', 
                    'attr' => [
                        'class' => 'select2',
                        'data-placeholder' => 'Please select % of Current Total Business',
                    ],
                ]);
        }

        $builder
            ->add('locations', EntityType::class, [
                'class' => Location::class,
                'choice_label' => 'address',
                'label' => 'Locations',
                'query_builder' => function (EntityRepository $er) use ($options) {
                    return $er->createQueryBuilder('l')
                              ->where('l.supplier = :supplier')
                              ->orderBy('l.id', 'ASC')
                              ->setParameter('supplier', $options['supplier']);
                },
                'multiple' => true,
                'placeholder' => 'Please select which locations this customer is managed from',
                'attr' => [
                    'class' => 'select2',
                    'data-placeholder' => 'Please select which locations this customer is managed from',
                ],
            ])
            ->add('workPeriod', ChoiceType::class, [
                'label' => 'How long have they been a customer?',
                'choices' => array_combine(Customer::getYears(), Customer::getYears()),
                'placeholder' => 'Please select the duration',
                'attr' => [
                    'class' => 'select2',
                    'data-placeholder' => 'Please select the duration',
                ],
            ]);
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => 'AppBundle\Entity\Customer',
        ])
                 ->setRequired('supplier');
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'CustomerType';
    }


}
