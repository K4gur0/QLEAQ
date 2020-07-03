<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;


/**
 * @ORM\Entity(repositoryClass="App\Repository\AnnonceRepository")
 * @UniqueEntity(fields={"titre"}, message="Ce titre d'annonce existe déjà, veuillez en choisir un autre.")
 */
class Annonce
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $titre;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $type_logement;

    /**
     * @ORM\Column(type="integer")
     */
    private $nombre_max_residents;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $description;

    /**
     * @ORM\Column(type="float")
     */
    private $superficie;

    /**
     * @ORM\Column(type="integer")
     */
    private $tarif;

    /**
     * @ORM\Column(type="date")
     */
    private $date_disponible;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $adresse;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $cp;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $ville;

//    /**
//     * @ORM\ManyToOne(targetEntity="App\Entity\Proprietaire", inversedBy="annonces")
//     * @ORM\JoinColumn(nullable=false)
//     */
//    private $proprio;

    /**
     * @ORM\Column(type="datetime")
     */
    private $date_creation;

    /**
     * @ORM\Column(type="boolean")
     */
    private $publicationAuth;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Proprietaire", inversedBy="annonces")
     * @ORM\JoinColumn(nullable=false)
     */
    private $proprio;


    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $datePublication;

    /**
     * @ORM\ManyToOne(targetEntity=Nomade::class, inversedBy="favorie")
     */
    private $nomade;

    public function __construct()
    {
        $this->date_creation = new \DateTime();
        $this->publicationAuth = false;
        $this->nomade = new ArrayCollection();
    }


    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitre(): ?string
    {
        return $this->titre;
    }

    public function setTitre(string $titre): self
    {
        $this->titre = $titre;

        return $this;
    }

    public function getTypeLogement(): ?string
    {
        return $this->type_logement;
    }

    public function setTypeLogement(string $type_logement): self
    {
        $this->type_logement = $type_logement;

        return $this;
    }

    public function getNombreMaxResidents(): ?int
    {
        return $this->nombre_max_residents;
    }

    public function setNombreMaxResidents(int $nombre_max_residents): self
    {
        $this->nombre_max_residents = $nombre_max_residents;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getSuperficie(): ?float
    {
        return $this->superficie;
    }

    public function setSuperficie(float $superficie): self
    {
        $this->superficie = $superficie;

        return $this;
    }

    public function getTarif(): ?int
    {
        return $this->tarif;
    }

    public function setTarif(int $tarif): self
    {
        $this->tarif = $tarif;

        return $this;
    }

    public function getDateDisponible(): ?\DateTimeInterface
    {
        return $this->date_disponible;
    }

    public function setDateDisponible(\DateTimeInterface $date_disponible): self
    {
        $this->date_disponible = $date_disponible;

        return $this;
    }

    public function getAdresse(): ?string
    {
        return $this->adresse;
    }

    public function setAdresse(?string $adresse): self
    {
        $this->adresse = $adresse;

        return $this;
    }

    public function getCp(): ?string
    {
        return $this->cp;
    }

    public function setCp(string $cp): self
    {
        $this->cp = $cp;

        return $this;
    }

    public function getVille(): ?string
    {
        return $this->ville;
    }

    public function setVille(string $ville): self
    {
        $this->ville = $ville;

        return $this;
    }



    public function getDateCreation(): ?\DateTimeInterface
    {
        return $this->date_creation;
    }

    public function setDateCreation(\DateTimeInterface $date_creation): self
    {
        $this->date_creation = $date_creation;

        return $this;
    }

    public function getPublicationAuth(): ?bool
    {
        return $this->publicationAuth;
    }

    public function setPublicationAuth(bool $publicationAuth): self
    {
        $this->publicationAuth = $publicationAuth;

        return $this;
    }

    public function getProprio(): ?Proprietaire
    {
        return $this->proprio;
    }

    public function setProprio(?Proprietaire $proprio): self
    {
        $this->proprio = $proprio;

        return $this;
    }

    public function getDatePublication(): ?\DateTimeInterface
    {
        return $this->datePublication;
    }

    public function setDatePublication(?\DateTimeInterface $datePublication): self
    {
        $this->datePublication = $datePublication;

        return $this;
    }

    public function getNomade(): ?Nomade
    {
        return $this->nomade;
    }

    public function setNomade(?Nomade $nomade): self
    {
        $this->nomade = $nomade;

        return $this;
    }

    //    public function getProprio(): ?Proprietaire
//    {
//        return $this->proprio;
//    }
//
//    public function setProprio(?Proprietaire $proprio): self
//    {
//        $this->proprio = $proprio;
//
//        return $this;
//    }





}
