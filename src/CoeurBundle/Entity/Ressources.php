<?php

namespace CoeurBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Ressources
 *
 * @ORM\Table(name="ressources")
 * @ORM\Entity(repositoryClass="CoeurBundle\Repository\RessourcesRepository")
 */
class Ressources
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
     * @ORM\ManyToOne(targetEntity="CoeurBundle\Entity\Utilisateurs", inversedBy="ressources")
     * @ORM\JoinColumn(name="id_utilisateurs", referencedColumnName="id")
     */
    private $utilisateurss;

    /**
     * @ORM\ManyToOne(targetEntity="CoeurBundle\Entity\Items", inversedBy="ressources")
     * @ORM\JoinColumn(name="id_items", referencedColumnName="id")
     */
    private $items;

    /**
     * @var int
     *
     * @ORM\Column(name="qty", type="integer")
     */
    private $qty;


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
     * Set item
     *
     * @param integer $item
     *
     * @return Ressources
     */
    public function setItem($item)
    {
        $this->item = $item;

        return $this;
    }

    /**
     * Get item
     *
     * @return int
     */
    public function getItem()
    {
        return $this->item;
    }

    /**
     * Set qty
     *
     * @param integer $qty
     *
     * @return Ressources
     */
    public function setQty($qty)
    {
        $this->qty = $qty;

        return $this;
    }

    /**
     * Get qty
     *
     * @return int
     */
    public function getQty()
    {
        return $this->qty;
    }
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->items = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Set utilisateurs
     *
     * @param \CoeurBundle\Entity\Utilisateurs $utilisateurs
     *
     * @return Ressources
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

    /**
     * Add item
     *
     * @param \CoeurBundle\Entity\Items $item
     *
     * @return Ressources
     */
    public function addItem(\CoeurBundle\Entity\Items $item)
    {
        $this->items[] = $item;

        return $this;
    }

    /**
     * Remove item
     *
     * @param \CoeurBundle\Entity\Items $item
     */
    public function removeItem(\CoeurBundle\Entity\Items $item)
    {
        $this->items->removeElement($item);
    }

    /**
     * Get items
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getItems()
    {
        return $this->items;
    }

    /**
     * Add utilisateur
     *
     * @param \CoeurBundle\Entity\Utilisateurs $utilisateur
     *
     * @return Ressources
     */
    public function addUtilisateur(\CoeurBundle\Entity\Utilisateurs $utilisateur)
    {
        $this->utilisateurs[] = $utilisateur;

        return $this;
    }

    /**
     * Remove utilisateur
     *
     * @param \CoeurBundle\Entity\Utilisateurs $utilisateur
     */
    public function removeUtilisateur(\CoeurBundle\Entity\Utilisateurs $utilisateur)
    {
        $this->utilisateurs->removeElement($utilisateur);
    }

    /**
     * Set utilisateurss
     *
     * @param \CoeurBundle\Entity\Utilisateurs $utilisateurss
     *
     * @return Ressources
     */
    public function setUtilisateurss(\CoeurBundle\Entity\Utilisateurs $utilisateurss = null)
    {
        $this->utilisateurss = $utilisateurss;

        return $this;
    }

    /**
     * Get utilisateurss
     *
     * @return \CoeurBundle\Entity\Utilisateurs
     */
    public function getUtilisateurss()
    {
        return $this->utilisateurss;
    }

    /**
     * Set items
     *
     * @param \CoeurBundle\Entity\Items $items
     *
     * @return Ressources
     */
    public function setItems(\CoeurBundle\Entity\Items $items = null)
    {
        $this->items = $items;

        return $this;
    }
}
