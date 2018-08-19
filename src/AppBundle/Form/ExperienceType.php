<?php

namespace AppBundle\Form;

use AppBundle\Entity\Experience;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Callback;
use Symfony\Component\Validator\Context\ExecutionContextInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

class ExperienceType extends AbstractType
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
                ->add('function', HiddenType::class);
        if($supplier->isConsultants()){
            $builder
                ->add('haveExp', ChoiceType::class, [
                    'label' => 'Do you have experience?',
                    'choices' => Experience::getHaveExpD(),
                    'required' => false,
                    'placeholder' => 'Select Yes or No',
                    'attr' => [
                        'class' => 'have-experience'
                    ]
                ]);
        }
            $builder
                ->add('yearsExperience', ChoiceType::class, [
                    'label' => 'Years of Experience',
                    'choices' => Experience::getYears(),
                    'required' => false,
                    'placeholder' => 'Please select...',
                    'attr' => [
                        'class' => 'year-experience'
                    ]
                ])
                ->add('selfRating', ChoiceType::class, [
                    'label' => 'Self Rating',
                    'choices' => array_combine(Experience::getSelfRatings(), Experience::getSelfRatings()),
                    'placeholder' => 'Please select...',
                    'required' => false,
                    'attr' => [
                        'class' => 'self-rating'
                    ]
                ]);
        if($supplier->isConsultants()){
            $builder
                ->add('cxEnvironment', ChoiceType::class, [
                    'label' => 'Years working in a call centre/CX environment',
                    'choices' => Experience::getCxEnvironmentD(),
                    'required' => false,
                    'placeholder' => 'Please select',           
                    ])
                ->add('conExp', ChoiceType::class, [
                    'label' => 'Consulting Experience',
                    'choices' => Experience::getConExpD(),
                    'required' => false,
                    'placeholder' => 'Please select',           
                    ])
                ->add('wordbio', TextareaType::class, [
                    'label' => '100 Word Bio (describe your skills and experience)',                    
                    'attr' => [
                        'placeholder' => 'Please enter',
                        'rows' => 3,
                        'class' => 'resize-vertically'
                    ]
                    ]);
        }
        
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AppBundle\Entity\Experience',
            'constraints' => [
                new Callback(function (Experience $experience, ExecutionContextInterface $context, $payload) {
                    if (empty($experience->getYearsExperience())) {
                        $context->buildViolation('You must select an option from the drop-down box')
                                ->atPath('yearsExperience')->addViolation();
                    }

                    if ($experience->getYearsExperience() == 'Nil' && !empty($experience->getSelfRating())) {
                        $context->buildViolation('You must select an option from the drop-down box')
                                ->atPath('yearsExperience')->addViolation();
                    }

                    if ($experience->getYearsExperience() != 'Nil' && empty($experience->getSelfRating())) {
                        $context->buildViolation('Please select a self-rating from the drop-down list')
                                ->atPath('selfRating')->addViolation();
                    }
                })
            ]
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'appbundle_experience';
    }


}
