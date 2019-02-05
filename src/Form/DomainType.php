<?php
/**
 * Created by PhpStorm.
 * User: Benutzer
 * Date: 12/24/2018
 * Time: 8:03 PM
 */

namespace App\Form;

use App\Entity\Domain;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class DomainType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('name');
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => Domain::class,
        ));
    }
}