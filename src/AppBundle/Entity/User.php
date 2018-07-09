<?php

namespace AppBundle\Entity;

use AppBundle\AppBundle;
use Doctrine\ORM\Mapping as ORM;
use FOS\UserBundle\Model\User as BaseUser;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * @ORM\Entity(repositoryClass="AppBundle\Repository\UserRepository")
 * @ORM\Table(name="`user`")
 */
class User extends BaseUser{

    /**
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(type="integer")
     */
    protected $id;

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    private $race;

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    private $status;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $age;

    /**
     * @return mixed
     */
    public function getAge() {
        return $this->age;
    }

    /**
     * @param mixed $age
     */
    public function setAge($age) {
        $this->age = $age;
    }

    /**
     * @return mixed
     */
    public function getPlanet() {
        return $this->planet;
    }

    /**
     * @param mixed $planet
     */
    public function setPlanet($planet) {
        $this->planet = $planet;
    }

    /**
     * @return mixed
     */
    public function getStatus() {
        return $this->status;
    }

    /**
     * @param mixed $status
     */
    public function setStatus($status) {
        $this->status = $status;
    }

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    private $planet;

    /**
     * @ORM\ManyToMany(targetEntity="AppBundle\Entity\User")
     */
    private $friends;

    public function __construct() {
        parent::__construct ();
        $this->friends = new ArrayCollection();
    }

    /**
     * @return mixed
     */
    public function getFriends() {
        return $this->friends;
    }

    /**
     * @param mixed $friends
     */
    public function setFriends($friends) {
        $this->friends = $friends;
    }

    /**
     * Add friend
     *
     * @param \AppBundle\Entity\User $friend
     *
     * @return User
     */
    public function addFriend(User $friend){
        $this->friends[] = $friend;
        return $this;
    }

    /**
     * Remove friend
     *
     * @param \AppBundle\Entity\User $friend
     */
    public function removeFriend(User $friend){
        $this->friends->removeElement($friend);
    }

    /**
     * @return mixed
     */
    public function getId() {
        return $this->id;
    }

    /**
     * @return mixed
     */
    public function getRace() {
        return $this->race;
    }

    /**
     * @param mixed $race
     */
    public function setRace($race) {
        $this->race = $race;
    }

}