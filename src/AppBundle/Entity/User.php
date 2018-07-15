<?php

namespace AppBundle\Entity;

use AppBundle\AppBundle;
use Doctrine\ORM\Mapping as ORM;
use FOS\UserBundle\Model\User as BaseUser;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * @ORM\Entity(repositoryClass="AppBundle\Repository\UserRepository")
 * @ORM\Table(name="`user`")
 */
class User extends BaseUser implements \Serializable, UserInterface {

    public function __construct() {
        parent::__construct();
        $this->friends = new ArrayCollection();
    }

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
     * @ORM\Column(type="string", nullable=true)
     */
    private $planet;

    /**
     * @ORM\ManyToMany(targetEntity="AppBundle\Entity\User")
     */
    private $friends;



    /*GETTERS / SETTERS*/




    /**
     * @return mixed
     */
    public function getUsername() {
        return $this->username;
    }

    /**
     * @param mixed $username
     */
    public function setUsername($username) {
        $this->username = $username;
    }

    /**
     * @return mixed
     */
    public function getPassword() {
        return $this->password;
    }

    /**
     * @param mixed $password
     */
    public function setPassword($password) {
        $this->password = $password;
    }


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



    public function getRoles() {
        return array('ROLE_USER');
    }

    public function getSalt() {
        return null;
    }

    public function eraseCredentials() {
    }

    public function serialize() {
        return serialize(array(
            $this->id,
            $this->username,
            $this->password,
            // see section on salt below
            // $this->salt,
        ));
    }

    public function unserialize($serialized) {
        list (
            $this->id,
            $this->username,
            $this->password,
            // see section on salt below
            // $this->salt
            ) = unserialize($serialized, array('allowed_classes' => false));
    }
}