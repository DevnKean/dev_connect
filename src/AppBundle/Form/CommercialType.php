<?php

namespace AppBundle\Form;

use AppBundle\Entity\Commercial;
use AppBundle\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

class CommercialType extends AbstractType
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
            
        if(!$supplier->isConsultants()){
            $builder
                ->add('models', ChoiceType::class, [
                    'choices' => array_combine(Commercial::getCommercialModels($this->user->getSupplier()), Commercial::getCommercialModels($this->user->getSupplier())),
                    'label' => false,
                    'multiple' => true,
                    'expanded' => true,
                    'attr' => [
                        'data-placeholder' => 'Please select commercial modal'
                    ]
                ]);
        }else {
            $builder
            ->add('dailyrate', ChoiceType::class, [
                'label' => 'How would you describe your average daily rates in comparison to market averages?',
                'choices' => array_combine(Commercial::getDailyrateD(),Commercial::getDailyrateD()),
                'required' => false,
                'placeholder' => 'Select one from drop down',
                // 'attr' => [
                //     'class' => 'year-experience'
                // ]
            ])
            ->add('interestlead', ChoiceType::class, [
                'label' => "There are some situatiuons where customers are seeking some 'skin in the game' with some penalties/incentives based on the outcomes of your consulting. Are you interested in being referred these types of leads?",
                //'label' => 'Ther are some situatiuons',
                'choices' => Commercial::getInterestleadD(),
                'required' => false,
                'placeholder' => 'select Yes or No',
                'attr' => [
                    'class' => 'interestlead'
                ]
            ])
            ;
        }
    }
    
    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AppBundle\Entity\Commercial'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'appbundle_commercial';
    }
}
