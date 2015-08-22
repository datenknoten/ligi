<?php
namespace Datenknoten\LigiBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Datenknoten\LigiBundle\Form\DataTransformer\ImageUploadTransformer;
use Doctrine\ORM\EntityManager;
use Doctrine\Common\Persistence\ManagerRegistry;

class ImageType extends AbstractType
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
        $transformer = new ImageUploadTransformer($this->managerRegistry);
        $builder->addModelTransformer($transformer);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'invalid_message' => 'The selected issue does not exist',
            'compound' => false,
        ));
    }    

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'ligi_image';
    }
}
