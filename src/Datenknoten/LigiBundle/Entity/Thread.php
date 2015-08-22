<?php
// src/Acme/MessageBundle/Entity/Thread.php

namespace Datenknoten\LigiBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use FOS\MessageBundle\Entity\Thread as BaseThread;

/**
 * @ORM\Entity
 */
class Thread extends BaseThread
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ORM\ManyToOne(targetEntity="Datenknoten\LigiBundle\Entity\User")
     * @var \FOS\MessageBundle\Model\ParticipantInterface
     */
    protected $createdBy;

    /**
     * @ORM\OneToMany(
     *   targetEntity="Datenknoten\LigiBundle\Entity\Message",
     *   mappedBy="thread"
     * )
     * @var Message[]|\Doctrine\Common\Collections\Collection
     */
    protected $messages;

    /**
     * @ORM\OneToMany(
     *   targetEntity="Datenknoten\LigiBundle\Entity\ThreadMetadata",
     *   mappedBy="thread",
     *   cascade={"all"}
     * )
     * @var ThreadMetadata[]|\Doctrine\Common\Collections\Collection
     */
    protected $metadata;

    /**
     * @ORM\ManyToOne(targetEntity="Datenknoten\LigiBundle\Entity\Item",fetch="EAGER")
     * @var \Datenknoten\LigiBundle\Entity\Item
     */
    protected $item;


    public function getItem()
    {
        return $item;
    }

    public function setItem($item)
    {
        $this->item = $item;
        $this->subject = $item->getName();
        return $this;
    }
}