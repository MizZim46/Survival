<?php

namespace CoeurBundle\Entity;

use FOS\UserBundle\Model\User as BaseUser;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="utilisateurs")
 */
class Utilisateurs extends BaseUser
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ORM\OneToOne(targetEntity="CoeurBundle\Entity\Stats", mappedBy="utilisateurs")
     */
    private $stats;

    /**
     * @ORM\OneToMany(targetEntity="CoeurBundle\Entity\Ressources", mappedBy="utilisateurss")
     */
    private $ressources;

    public function __construct()
    {
        parent::__construct();
        // your own logic
    }

    /**
     * Set stats
     *
     * @param \CoeurBundle\Entity\Stats $stats
     *
     * @return Utilisateurs
     */
    public function setStats(\CoeurBundle\Entity\Stats $stats = null)
    {
        $this->stats = $stats;

        return $this;
    }

    /**
     * Get stats
     *
     * @return \CoeurBundle\Entity\Stats
     */
    public function getStats()
    {
        return $this->stats;
    }

    /**
     * Set ressources
     *
     * @param \CoeurBundle\Entity\Ressources $ressources
     *
     * @return Utilisateurs
     */
    public function setRessources(\CoeurBundle\Entity\Ressources $ressources = null)
    {
        $this->ressources = $ressources;

        return $this;
    }

    /**
     * Get ressources
     *
     * @return \CoeurBundle\Entity\Ressources
     */
    public function getRessources()
    {
        return $this->ressources;
    }

    /**
     * Add ressource
     *
     * @param \CoeurBundle\Entity\Ressources $ressource
     *
     * @return Utilisateurs
     */
    public function addRessource(\CoeurBundle\Entity\Ressources $ressource)
    {
        $this->ressources[] = $ressource;

        return $this;
    }

    /**
     * Remove ressource
     *
     * @param \CoeurBundle\Entity\Ressources $ressource
     */
    public function removeRessource(\CoeurBundle\Entity\Ressources $ressource)
    {
        $this->ressources->removeElement($ressource);
    }
}
