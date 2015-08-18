<?php

namespace Datenknoten\LigiBundle\Entity;

use FOS\UserBundle\Entity\User as BaseUser;
use Doctrine\ORM\Mapping as ORM;

/**
 * User
 *
 * @ORM\Table(name="fos_user")
 * @ORM\Entity
 */
class User extends BaseUser
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

    public function __construct()
    {
        parent::__construct();
        // your own logic
    }

}
