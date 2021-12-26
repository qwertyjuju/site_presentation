<?php
namespace App\Entity;

class Profile
{
    /**
     * @Assert\NotBlank
     */
    protected $prenom;
    /**
     * @Assert\NotBlank
     */
    protected $nom;
    /**
     * @Assert\NotBlank
     */
    protected $email;

    public function getPrenom(): string
    {
        return $this->prenom;
    }

    public function setPrenom(string $prenom): void
    {
        $this->prenom = $prenom;
    }

    public function getNom(): string
    {
        return $this->nom;
    }

    public function setNom(string $nom): void
    {
        $this->nom = $nom;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function setEmail(string $email): void
    {
        $this->email = $email;
    }
}