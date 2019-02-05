<?php
/**
 * Created by PhpStorm.
 * User: Benutzer
 * Date: 1/8/2019
 * Time: 1:43 AM
 */

namespace App\Form;


use App\Form\DataTransformer\IssueToNumberTransformer;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class IssueSelectorType extends AbstractType
{
    private $transformer;

    public function __construct(IssueToNumberTransformer $transformer)
    {
        $this->transformer = $transformer;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->addModelTransformer($this->transformer);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'invalid_message' => 'The selected issue does not exist',
        ));
    }

    public function getParent()
    {
        return TextType::class;
    }
}