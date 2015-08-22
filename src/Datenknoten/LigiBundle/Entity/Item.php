<?php

namespace Datenknoten\LigiBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Timestampable\Traits\TimestampableEntity;
use Gedmo\Blameable\Traits\BlameableEntity;

/**
 * Item
 *
 * @ORM\Table()
 * @ORM\Entity
 */
class Item
{
    use TimestampableEntity,BlameableEntity;

    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=255)
     */
    private $name;

    /**
     * @var string
     *
     * @ORM\Column(name="description", type="text")
     */
    private $description;

    /**
     * @var boolean
     *
     * @ORM\Column(name="is_request", type="boolean",options={"default"=false})
     */
    private $is_request;

    /**
     * @var array
     *
     * @ORM\ManyToMany(targetEntity="File")
     * @ORM\JoinTable(name="item2file",
     *      joinColumns={@ORM\JoinColumn(name="items_id", referencedColumnName="id")},
     *      inverseJoinColumns={@ORM\JoinColumn(name="files_id", referencedColumnName="id")}
     *      )
     */
    public $files = [];


    /**
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set name
     *
     * @param string $name
     * @return Item
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string 
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set description
     *
     * @param string $description
     * @return Item
     */
    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * Get description
     *
     * @return string 
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Set is_request
     *
     * @param boolean $is_request
     * @return Item
     */
    public function setIsRequest($is_request)
    {
        $this->is_request = $is_request;

        return $this;
    }

    /**
     * Get is_request
     *
     * @return string 
     */
    public function getIsRequest()
    {
        return $this->is_request;
    }
}
