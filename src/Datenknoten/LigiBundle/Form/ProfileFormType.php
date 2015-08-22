<?php
namespace Datenknoten\LigiBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

class ProfileFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('languages', 'language', [
                'placeholder' => 'Choose at least one language.',
                'multiple' => true,
            ])
            ->add('avatar', 'file', [
                'label' => 'Please select an image for your profile picture.'
            ])
            ;
    }

    public function getParent()
    {
        return 'fos_user_profile';
    }

    public function getName()
    {
        return 'ligi_user_profile';
    }
}