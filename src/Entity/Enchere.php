<?php

namespace App\Entity;

use App\Repository\EnchereRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: EnchereRepository::class)]
 class Enchere
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    public $id;

    #[ORM\Column(type: 'datetime')]
    public $dateDebut;

    #[ORM\Column(type: 'datetime')]
    public $dateFin;

    #[ORM\OneToMany(mappedBy: 'idEnchere', targetEntity: EnchereFournisseur::class)]
    public $enchereFournisseurs;

    #[ORM\Column(type: 'float', nullable: true)]
    public $idPanierGlobal;

    public function __construct()
    {
        $this->enchereFournisseurs = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDateDebut(): ?\DateTimeInterface
    {
        return $this->dateDebut;
    }

    public function setDateDebut(\DateTimeInterface $dateDebut): self
    {
        $this->dateDebut = $dateDebut;

        return $this;
    }

    public function getDateFin(): ?\DateTimeInterface
    {
        return $this->dateFin;
    }

    public function setDateFin(\DateTimeInterface $dateFin): self
    {
        $this->dateFin = $dateFin;

        return $this;
    }

    /**
     * @return Collection<int, EnchereFournisseur>
     */
    public function getEnchereFournisseurs(): Collection
    {
        return $this->enchereFournisseurs;
    }

    public function addEnchereFournisseur(EnchereFournisseur $enchereFournisseur): self
    {
        if (!$this->enchereFournisseurs->contains($enchereFournisseur)) {
            $this->enchereFournisseurs[] = $enchereFournisseur;
            $enchereFournisseur->setIdEnchere($this);
        }

        return $this;
    }

    public function removeEnchereFournisseur(EnchereFournisseur $enchereFournisseur): self
    {
        if ($this->enchereFournisseurs->removeElement($enchereFournisseur)) {
            // set the owning side to null (unless already changed)
            if ($enchereFournisseur->getIdEnchere() === $this) {
                $enchereFournisseur->setIdEnchere(null);
            }
        }

        return $this;
    }

    public function getIdPanierGlobal(): ?float
    {
        return $this->idPanierGlobal;
    }

    public function setIdPanierGlobal(?float $idPanierGlobal): self
    {
        $this->idPanierGlobal = $idPanierGlobal;

        return $this;
    }
}
