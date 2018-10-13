<?php
/**
 * Created by PhpStorm.
 * User: bibouille
 * Date: 14/11/17
 * Time: 12:17
 */
namespace WTFBundle\Entity;

use FOS\UserBundle\Model\User as BaseUser;
use Doctrine\ORM\Mapping as ORM;
use WTFBundle\Entity\Conference;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * @ORM\Entity
 * @ORM\Table(name="fos_user")
 */
class User extends BaseUser
{
    /*
     * @ORM\OneToMany(targetEntity="Conference", mappedBy="user")
     */
    private $conferences;

    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    public function __construct()
    {
        parent::__construct();
        $this->conferences = new ArrayCollection();
    }

    /**
     * Get conferences
     *
     * @return integer
     */
    public function getConferences()
    {
        return $this->conferences;
    }
}
