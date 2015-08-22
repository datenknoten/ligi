<?php
namespace Datenknoten\LigiBundle\Form\DataTransformer;

use Datenknoten\LigiBundle\Entity\File;
use Symfony\Component\Form\DataTransformerInterface;
use Symfony\Component\Form\Exception\TransformationFailedException;
use Doctrine\Common\Persistence\ManagerRegistry;
use Doctrine\ORM\EntityManager;

class ImageUploadTransformer implements DataTransformerInterface
{
    private $managerRegistry;

    public function __construct(ManagerRegistry $managerRegistry)
    {
        $this->managerRegistry = $managerRegistry;
    }

    /**
     * Dont transform anything
     */
    public function transform($value) {
        return $value;
    }


    /**
     * Transform the JSON string into full blown File-objects
     */
    public function reverseTransform($value) {
        $items = json_decode($value);
        $retval = [];
        foreach($items as $item) {
            if (!$item->deleted) {
                $entityManager = $this->managerRegistry->getManagerForClass("LigiBundle:File");
                $file = $entityManager
                      ->getRepository('LigiBundle:File')
                      ->find($item->id);
                if ($file instanceof File) {
                    $retval[] = $file;
                }
            }
        }
        return $retval;
    }
}