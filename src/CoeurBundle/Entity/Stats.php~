<?php

namespace CoeurBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Stats
 *
 * @ORM\Table(name="stats")
 * @ORM\Entity(repositoryClass="CoeurBundle\Repository\StatsRepository")
 */
class Stats
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
     * @ORM\OneToOne(targetEntity="CoeurBundle\Entity\Utilisateurs", inversedBy="stats")
     * @ORM\JoinColumn(name="id_utilisateurs", referencedColumnName="id")
     */
    private $utilisateurs;


    /**
     * @var float
     *
     * @ORM\Column(name="vie", type="float")
     */
    private $vie;

    /**
     * @var float
     *
     * @ORM\Column(name="faim", type="float")
     */
    private $faim;

    /**
     * @var float
     *
     * @ORM\Column(name="soif", type="float")
     */
    private $soif;

    /**
     * @var float
     *
     * @ORM\Column(name="fatigue", type="float")
     */
    private $fatigue;

    /**
     * @var float
     *
     * @ORM\Column(name="temperature", type="float")
     */
    private $temperature;


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
     * Set vie
     *
     * @param float $vie
     *
     * @return Stats
     */
    public function setVie($vie)
    {
        $this->vie = $vie;

        return $this;
    }

    /**
     * Get vie
     *
     * @return float
     */
    public function getVie()
    {
        return $this->vie;
    }

    /**
     * Set faim
     *
     * @param float $faim
     *
     * @return Stats
     */
    public function setFaim($faim)
    {
        $this->faim = $faim;

        return $this;
    }

    /**
     * Get faim
     *
     * @return float
     */
    public function getFaim()
    {
        return $this->faim;
    }

    /**
     * Set soif
     *
     * @param float $soif
     *
     * @return Stats
     */
    public function setSoif($soif)
    {
        $this->soif = $soif;

        return $this;
    }

    /**
     * Get soif
     *
     * @return float
     */
    public function getSoif()
    {
        return $this->soif;
    }

    /**
     * Set fatigue
     *
     * @param float $fatigue
     *
     * @return Stats
     */
    public function setFatigue($fatigue)
    {
        $this->fatigue = $fatigue;

        return $this;
    }

    /**
     * Get fatigue
     *
     * @return float
     */
    public function getFatigue()
    {
        return $this->fatigue;
    }

    /**
     * Set temperature
     *
     * @param float $temperature
     *
     * @return Stats
     */
    public function setTemperature($temperature)
    {
        $this->temperature = $temperature;

        return $this;
    }

    /**
     * Get temperature
     *
     * @return float
     */
    public function getTemperature()
    {
        return $this->temperature;
    }

    /**
     * Set utilisateurs
     *
     * @param \CoeurBundle\Entity\Utilisateurs $utilisateurs
     *
     * @return Stats
     */
    public function setUtilisateurs(\CoeurBundle\Entity\Utilisateurs $utilisateurs = null)
    {
        $this->utilisateurs = $utilisateurs;

        return $this;
    }

    /**
     * Get utilisateurs
     *
     * @return \CoeurBundle\Entity\Utilisateurs
     */
    public function getUtilisateurs()
    {
        return $this->utilisateurs;
    }
}
