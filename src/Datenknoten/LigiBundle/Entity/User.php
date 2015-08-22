<?php

namespace Datenknoten\LigiBundle\Entity;

use FOS\UserBundle\Entity\User as BaseUser;
use FOS\UserBundle\Model\UserInterface;
use FOS\MessageBundle\Model\ParticipantInterface;
use Doctrine\ORM\Mapping as ORM;

/**
 * User
 *
 * @ORM\Table(name="fos_user")
 * @ORM\Entity
 */
class User extends BaseUser implements ParticipantInterface,UserInterface
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @var array
     *
     * @ORM\Column(name="languages", type="json_array",options={"default"=""})
     */
    public $languages;

    /**
     * @ORM\ManyToOne(targetEntity="Datenknoten\LigiBundle\Entity\File")
     * @var File
     */
    protected $avatar;

    public function __construct()
    {
        parent::__construct();
        // your own logic
    }

    public function getId()
    {
        return $this->id;
    }

    public function getAvatar()
    {
        return $this->avatar;
    }

    public function setAvatar($file)
    {
        $this->avatar = $file;

        return $this;
    }

}
