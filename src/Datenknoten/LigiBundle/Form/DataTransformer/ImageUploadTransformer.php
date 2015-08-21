<?php
namespace Datenknoten\LigiBundle\Form\DataTransformer;

use Datenknoten\LigiBundle\Entity\File;
use Symfony\Component\Form\DataTransformerInterface;
use Symfony\Component\Form\Exception\TransformationFailedException;
use Doctrine\ORM\EntityManager;

class ImageUploadTransformer implements DataTransformerInterface
{
    private $entityManager;

    public function __construct(EntityManager $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    /**
     * Dont transform anything
     */
    public function transform($value) {
        return $value;
    }


    public function reverseTransform($value) {
        $items = json_decode($value);
        $retval = [];
        foreach($items as $item) {
            if (!$item->deleted) {
                $file = $this->entityManager
                      ->getRepository('LigiBundle:File')
                      // query for the issue with this id
                      ->find($item->id);
                if ($file instanceof File) {
                    $retval[] = $file;
                }
            }
        }
        return $retval;
    }
}