<?php

namespace App\Entity;

use App\Repository\CommandeRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CommandeRepository::class)]
class Commande
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id_commande = null;

    
    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $Date_Commande = null;

    #[ORM\Column(length: 50)]
    private ?string $status = null;

    public function getId(): ?int
    {
        return $this->id_commande;
    }


    public function getDateCommande(): ?\DateTimeInterface
    {
        return $this->Date_Commande;
    }

    public function setDateCommande(\DateTimeInterface $Date_Commande): static
    {
        $this->Date_Commande = $Date_Commande;

        return $this;
    }

    public function getStatus(): ?string
    {
        return $this->status;
    }

    public function setStatus(string $status): static
    {
        $this->status = $status;

        return $this;
    }
}
