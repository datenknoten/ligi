<?php
namespace Datenknoten\LigiBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Datenknoten\LigiBundle\Form\DataTransformer\ImageUploadTransformer;
use Doctrine\ORM\EntityManager;

class ImageType extends AbstractType
{
    private $entityManager;

    public function __construct(EntityManager $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $transformer = new ImageUploadTransformer($this->entityManager);
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
