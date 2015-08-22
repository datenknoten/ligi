<?php
namespace Datenknoten\MessageCustomisationBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class NewThreadMessageFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('recipient', 'mc_message_recipient')
            ->add('item', 'mc_message_item')
            ->add('subject', 'text', [
                'disabled' => true,
            ])
            ->add('body', 'textarea')
            ;
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'intention'  => 'message',
        ));
    }

    public function getName()
    {
        return 'ligi_message_new_thread';
    }
}