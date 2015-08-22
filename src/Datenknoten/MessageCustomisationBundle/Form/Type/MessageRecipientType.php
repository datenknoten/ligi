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
use Symfony\Component\HttpFoundation\RequestStack;

class MessageRecipientType extends AbstractTypep
{
    private $managerRegistry;
    private $request;

    public function __construct(ManagerRegistry $managerRegistry, RequestStack $request_stack)
    {
        $this->managerRegistry = $managerRegistry;
        $this->request = $request->getCurrentRequest();
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $entityManager = $this->managerRegistry->getManagerForClass("LigiBundle:User");
        $recipient = $this->request->query->get('recipient',null);
        if (!is_null($recipient)) {
            $item = $entityManager
                  ->getRepository('LigiBundle:User')
                  ->findOneBy(["username" => $recipient]);
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
        return 'mc_message_recipient';
    }
}