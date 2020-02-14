<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ProprietaireRepository")
 * @UniqueEntity(fields={"email"}, message="L'email indiquée est déjà utilisée")
 * @ORM\HasLifecycleCallbacks()
 */
class Proprietaire implements UserInterface
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="json")
     */
    private $roles = [];

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $raison_social;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $telephone;

    /**
     * @ORM\Column(type="string", length=255)
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

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $password;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $email;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $statut;

    /**
     * @ORM\Column(type="boolean")
     */
    private $isConfirmed;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $securityToken;

    /**
     * @ORM\Column(type="datetime")
     */
    private $date_creation_compte;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $refus;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $refusToken;

    public function __construct()
    {
        $this->date_creation_compte = new \DateTime();
    }

    /**
     * Appelée lorsque l'objet est utilisé comme une chaine
     */
    public function __toString()
    {
        return $this->getUsername();
    }

    /**
     * @ORM\PrePersist()
     */
    public function prePersist()
    {
        // Si le statut isConfirmed n'est pas défini: mettre à false
        if ($this->isConfirmed === null) {
            $this->setIsConfirmed(false);
        }

        if ($this->refus === null) {
            $this->setRefus(false);
        }

        // Définir un jeton s'il n'y en a pas
        if ($this->securityToken === null) {
            $this->renewToken();
        }

        if ($this->refusToken === null){
            $this->renewRefusToken();
        }
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getRaisonSocial(): ?string
    {
        return $this->raison_social;
    }

    public function setRaisonSocial(string $raison_social): self
    {
        $this->raison_social = $raison_social;

        return $this;
    }

    public function getTelephone(): ?string
    {
        return $this->telephone;
    }

    public function setTelephone(string $telephone): self
    {
        $this->telephone = $telephone;

        return $this;
    }

    public function getAdresse(): ?string
    {
        return $this->adresse;
    }

    public function setAdresse(string $adresse): self
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

    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    public function getStatut(): ?string
    {
        return $this->statut;
    }

    public function setStatut(string $statut): self
    {
        $this->statut = $statut;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_PROPRIO';

        return array_unique($roles);
    }

    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUsername(): string
    {
        return (string) $this->raison_social;
    }

    /**
     * @see UserInterface
     */
    public function getSalt()
    {
        // not needed when using the "bcrypt" algorithm in security.yaml
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials()
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    public function getDateCreationCompte(): ?\DateTimeInterface
    {
        return $this->date_creation_compte;
    }

    public function setDateCreationCompte(\DateTimeInterface $date_creation_compte): self
    {
        $this->date_creation_compte = $date_creation_compte;

        return $this;
    }

    public function getIsConfirmed(): ?bool
    {
        return $this->isConfirmed;
    }

    public function setIsConfirmed(bool $isConfirmed): self
    {
        $this->isConfirmed = $isConfirmed;

        return $this;
    }

    public function getSecurityToken(): ?string
    {
        return $this->securityToken;
    }

    public function setSecurityToken(string $securityToken): self
    {
        $this->securityToken = $securityToken;

        return $this;
    }

    public function getRefusToken(): ?string
    {
        return $this->refusToken;
    }

    public function setRefusToken(string $refusToken): self
    {
        $this->refusToken = $refusToken;

        return $this;
    }

    public function getRefus(): ?bool
    {
        return $this->refus;
    }

    public function setRefus(?bool $refus): self
    {
        $this->refus = $refus;

        return $this;
    }

    /**
     * Renouveller le jeton de sécurité
     */
    public function renewToken() : self
    {
        // Création d'un jeton
        $token = bin2hex(random_bytes(16));

        return $this->setSecurityToken($token);
    }

    public function renewRefusToken() : self
    {
        // Création d'un jeton
        $token = bin2hex(random_bytes(16));

        return $this->setRefusToken($token);
    }




}
