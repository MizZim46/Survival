<?php

namespace CoeurBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Items
 *
 * @ORM\Table(name="items")
 * @ORM\Entity(repositoryClass="CoeurBundle\Repository\ItemsRepository")
 */
class Items
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @ORM\OneToMany(targetEntity="CoeurBundle\Entity\Ressources", mappedBy="items")
     */
    private $ressources;

    /**
     * @ORM\OneToMany(targetEntity="CoeurBundle\Entity\Craft", mappedBy="items")
     */
    private $craft;

    /**
     * @ORM\OneToMany(targetEntity="CoeurBundle\Entity\Craft", mappedBy="besoin")
     */
    private $itemsneed;

    /**
     * @var string
     *
     * @ORM\Column(name="nom", type="string", length=255)
     */
    private $nom;

    /**
     * @var string
     *
     * @ORM\Column(name="stats", type="string", length=255)
     */
    private $stats;

    /**
     * @var float
     *
     * @ORM\Column(name="poids", type="float")
     */
    private $poids;

    /**
     * @var string
     *
     * @ORM\Column(name="classe", type="string", length=255)
     */
    private $classe;


    /**
     * Get id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set nom
     *
     * @param string $nom
     *
     * @return Items
     */
    public function setNom($nom)
    {
        $this->nom = $nom;

        return $this;
    }

    /**
     * Get nom
     *
     * @return string
     */
    public function getNom()
    {
        return $this->nom;
    }

    /**
     * Set stats
     *
     * @param string $stats
     *
     * @return Items
     */
    public function setStats($stats)
    {
        $this->stats = $stats;

        return $this;
    }

    /**
     * Get stats
     *
     * @return string
     */
    public function getStats()
    {
        return $this->stats;
    }

    /**
     * Set classe
     *
     * @param string $classe
     *
     * @return Items
     */
    public function setClasse($classe)
    {
        $this->classe = $classe;

        return $this;
    }

    /**
     * Get classe
     *
     * @return string
     */
    public function getClasse()
    {
        return $this->classe;
    }
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->ressources = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Add ressource
     *
     * @param \CoeurBundle\Entity\Ressources $ressource
     *
     * @return Items
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

    /**
     * Get ressources
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getRessources()
    {
        return $this->ressources;
    }

    /**
     * Set poids
     *
     * @param float $poids
     *
     * @return Items
     */
    public function setPoids($poids)
    {
        $this->poids = $poids;

        return $this;
    }

    /**
     * Get poids
     *
     * @return float
     */
    public function getPoids()
    {
        return $this->poids;
    }

    /**
     * Add craft
     *
     * @param \CoeurBundle\Entity\Craft $craft
     *
     * @return Items
     */
    public function addCraft(\CoeurBundle\Entity\Craft $craft)
    {
        $this->craft[] = $craft;

        return $this;
    }

    /**
     * Remove craft
     *
     * @param \CoeurBundle\Entity\Craft $craft
     */
    public function removeCraft(\CoeurBundle\Entity\Craft $craft)
    {
        $this->craft->removeElement($craft);
    }

    /**
     * Get craft
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getCraft()
    {
        return $this->craft;
    }

    /**
     * Add besoin
     *
     * @param \CoeurBundle\Entity\Craft $besoin
     *
     * @return Items
     */
    public function addBesoin(\CoeurBundle\Entity\Craft $besoin)
    {
        $this->besoin[] = $besoin;

        return $this;
    }

    /**
     * Remove besoin
     *
     * @param \CoeurBundle\Entity\Craft $besoin
     */
    public function removeBesoin(\CoeurBundle\Entity\Craft $besoin)
    {
        $this->besoin->removeElement($besoin);
    }

    /**
     * Get besoin
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getBesoin()
    {
        return $this->besoin;
    }

    /**
     * Add itemsneed
     *
     * @param \CoeurBundle\Entity\Craft $itemsneed
     *
     * @return Items
     */
    public function addItemsneed(\CoeurBundle\Entity\Craft $itemsneed)
    {
        $this->itemsneed[] = $itemsneed;

        return $this;
    }

    /**
     * Remove itemsneed
     *
     * @param \CoeurBundle\Entity\Craft $itemsneed
     */
    public function removeItemsneed(\CoeurBundle\Entity\Craft $itemsneed)
    {
        $this->itemsneed->removeElement($itemsneed);
    }

    /**
     * Get itemsneed
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getItemsneed()
    {
        return $this->itemsneed;
    }
}
