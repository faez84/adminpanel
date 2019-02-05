<?php

namespace App\Form;

use App\Entity\Application;
use App\Entity\Movie;
use App\Form\DataTransformer\IssueToNumberTransformer;
use App\Utils\HelperClass;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\CallbackTransformer;
use Symfony\Component\Form\Extension\Core\Type\ButtonType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\OptionsResolver\OptionsResolver;


class GeneralFormType extends AbstractType
{
    protected $data_class;
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $tranformer = new IssueToNumberTransformer();
        $this->data_class = get_class($options['data']);
        foreach ($options['configuration'] as $field) {
            $optionsArray = [
                'attr' => array('class' => 'general-form-r')
            ];
            if ($field['type'] === 'CollectionType') {
                $optionsArray = [
                    'entry_type' => $field['entry_type'],
                    'entry_options' => array('label' => false),
                    'allow_add' => true,
                    'by_reference' => false,
                    'allow_delete' => true,
                ];
            }
            $builder
                ->add($field['name'], $this->getTypeClass($field['type']), $optionsArray);
        }
        $builder->get('name')
            ->addModelTransformer($tranformer);

        $builder->add('save',SubmitType::class, array('label' => 'Create', 'attr' =>
            [
                'id' => 'button',
                'class' => 'btn btn-primary'
            ]
        ));
    }

    /**
     * @param string $typeName
     * @return mixed
     */
    public function getTypeClass(string $typeName)
    {
        switch ($typeName) {
            case 'TextType':
                return TextType::class;
            case 'IntegerType':
                return IntegerType::class;
            case 'CollectionType':
                return CollectionType::class;

            default: break;
        }
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'validation_groups' => HelperClass::getGroupValidation(),
            'configuration' => ''
        ));
        $resolver->setRequired(array(
            'configuration'
        ));
    }
}