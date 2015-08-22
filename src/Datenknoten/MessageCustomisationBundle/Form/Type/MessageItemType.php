<?php

namespace Datenknoten\MessageCustomisationBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Datenknoten\LigiBundle\Form\DataTransformer\ImageUploadTransformer;
use Doctrine\ORM\EntityManager;
use Doctrine\Common\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;

class MessageItemType extends AbstractType
{
    private $managerRegistry;
    private $request;

    public function __construct(ManagerRegistry $managerRegistry, Request $request)
    {
        $this->managerRegistry = $managerRegistry;
        $this->request = $request;
    }

    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        //$transformer = new ImageUploadTransformer($this->managerRegistry);
        //$builder->addModelTransformer($transformer);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $entityManager = $this->managerRegistry->getManagerForClass("LigiBundle:Item");
        $id = $this->request->query->get('items_id',null);
        if (!is_null($id)) {
            $item = $entityManager
                  ->getRepository('LigiBundle:Item')
                  ->find();
        } else {
            $item = null;
        }
        $resolver->setDefaults(array(
            'invalid_message' => 'The selected issue does not exist',
            'compound' => false,
            'entity' => $item,
        ));
    }

    public function buildView(FormView $view, FormInterface $form, array $options)
    {
        $view->vars['entity'] = $options['entity'];
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'mc_message_item';
    }
}