<?php

namespace App\Entity;

use App\Repository\EnchereFournisseurRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: EnchereFournisseurRepository::class)]
class EnchereFournisseur
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\ManyToOne(targetEntity: Enchere::class, inversedBy: 'enchereFournisseurs')]
    #[ORM\JoinColumn(nullable: true)]
    public $idEnchere;

    #[ORM\Column(type: 'float')]
    public $prix;

    #[ORM\Column(type: 'string', length: 60)]
    public $fournisseur;

    #[ORM\Column(type: 'string', length: 100, nullable: true)]
    public $produit;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getIdEnchere(): ?Enchere
    {
        return $this->idEnchere;
    }

    public function setIdEnchere(?Enchere $idEnchere): self
    {
        $this->idEnchere = $idEnchere;

        return $this;
    }

    public function getPrix(): ?float
    {
        return $this->prix;
    }

    public function setPrix(float $prix): self
    {
        $this->prix = $prix;

        return $this;
    }

    public function getFournisseur(): ?string
    {
        return $this->fournisseur;
    }

    public function setFournisseur(string $fournisseur): self
    {
        $this->fournisseur = $fournisseur;

        return $this;
    }

    public function getProduit(): ?string
    {
        return $this->produit;
    }

    public function setProduit(?string $produit): self
    {
        $this->produit = $produit;

        return $this;
    }
}
