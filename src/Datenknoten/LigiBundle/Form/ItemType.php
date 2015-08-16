<?php

namespace Datenknoten\LigiBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\Form\Extension\Core\ChoiceList\ChoiceList;

class ItemType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name','text',['label' => 'Name'])
            ->add('description','textarea',['label' => 'Description'])
            ->add('is_request','choice',[
                'label' => false,
                'choice_list' => new ChoiceList(
                    [true,false],
                    ['I need this.', 'I want to give this away.']
                ),
                'required' => true,
            ])
        ;
    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Datenknoten\LigiBundle\Entity\Item'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'datenknoten_ligibundle_item';
    }
}
