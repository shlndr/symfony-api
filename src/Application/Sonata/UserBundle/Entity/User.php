<?php

namespace Application\Sonata\UserBundle\Entity;

use FOS\UserBundle\Entity\User as AbstractedUser;
use FOS\UserBundle\Model\UserInterface;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use JMS\Serializer\Annotation as JMS;
use Gedmo\Timestampable\Traits\TimestampableEntity;
use Gedmo\Blameable\Traits\BlameableEntity;

/**
 * User
 *
 * @ORM\Table(name="fos_user_user")
 * @ORM\Entity(repositoryClass="Application\Sonata\UserBundle\Repository\UserRepository")
 * @JMS\ExclusionPolicy("all")
 */
class User extends AbstractedUser implements UserInterface
{
    use TimestampableEntity;
    use BlameableEntity;


    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @JMS\Expose
     */
    protected $id;

    /**
     * @var string $name
     *
     * @ORM\Column(name="name", type="string", length=255, nullable=true)
     */
    private $name;

    /**
     * @var string $about
     *
     * @ORM\Column(name="about", type="text", nullable=true)
     */
    private $about;


    /**
     * @var string $profilePic
     *
     * @ORM\Column(name="profile_pic", type="text", nullable=true)
     */
    private $profilePic;


    /**
     * Get id
     *
     * @return integer $id
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return array
     */
    public function getRealRoles()
    {
        return $this->roles;
    }

    /**
     * @param array $roles
     */
    public function setRealRoles(array $roles)
    {
        $this->setRoles($roles);
    }


    /**
     * Set name
     *
     * @param string $name
     *
     * @return User
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
     * Set about
     *
     * @param string $about
     *
     * @return User
     */
    public function setAbout($about)
    {
        $this->about = $about;

        return $this;
    }

    /**
     * Get about
     *
     * @return string
     */
    public function getAbout()
    {
        return $this->about;
    }

    /**
     * Set profilePic
     *
     * @param string $profilePic
     *
     * @return User
     */
    public function setProfilePic($profilePic)
    {
        $this->profilePic = $profilePic;

        return $this;
    }

    /**
     * Get profilePic
     *
     * @return string
     */
    public function getProfilePic()
    {
        return $this->profilePic;
    }
}
