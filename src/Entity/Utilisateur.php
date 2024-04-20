<?php

namespace App\Entity;

use App\Repository\UtilisateurRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: UtilisateurRepository::class)]
class Utilisateur
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id_utilisateur = null;

    #[ORM\Column(length: 50)]
    private ?string $Nom = null;

    #[ORM\Column(length: 50)]
    private ?string $Prenom = null;

    #[ORM\Column(length: 70)]
    private ?string $Adresse_Mail = null;

    #[ORM\Column(length: 255)]
    private ?string $Mot_De_Passe = null;

    #[ORM\Column(length: 255)]
    private ?string $Adresse_Postale = null;

    #[ORM\Column(length: 50)]
    private ?string $Numero_De_Telephone = null;

    public function getId(): ?int
    {
        return $this->id_utilisateur;
    }

    
    

    public function getNom(): ?string
    {
        return $this->Nom;
    }

    public function setNom(string $Nom): static
    {
        $this->Nom = $Nom;

        return $this;
    }

    public function getPrenom(): ?string
    {
        return $this->Prenom;
    }

    public function setPrenom(string $Prenom): static
    {
        $this->Prenom = $Prenom;

        return $this;
    }

    public function getAdresseMail(): ?string
    {
        return $this->Adresse_Mail;
    }

    public function setAdresseMail(string $Adresse_Mail): static
    {
        $this->Adresse_Mail = $Adresse_Mail;

        return $this;
    }

    public function getMotDePasse(): ?string
    {
        return $this->Mot_De_Passe;
    }

    public function setMotDePasse(string $Mot_De_Passe): static
    {
        $this->Mot_De_Passe = $Mot_De_Passe;

        return $this;
    }

    public function getAdressePostale(): ?string
    {
        return $this->Adresse_Postale;
    }

    public function setAdressePostale(string $Adresse_Postale): static
    {
        $this->Adresse_Postale = $Adresse_Postale;

        return $this;
    }

    public function getNumeroDeTelephone(): ?string
    {
        return $this->Numero_De_Telephone;
    }

    public function setNumeroDeTelephone(string $Numero_De_Telephone): static
    {
        $this->Numero_De_Telephone = $Numero_De_Telephone;

        return $this;
    }
}
