<?php

namespace App\Entity;

use App\Repository\EcurieRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: EcurieRepository::class)]
class Ecurie
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $nom = null;

    #[ORM\Column(length: 255)]
    private ?string $marque = null;

    /**
     * @var Collection<int, Pilote>
     */
    #[ORM\OneToMany(targetEntity: Pilote::class, mappedBy: 'ecurie')]
    private Collection $pilotes;

    public function __construct()
    {
        $this->pilotes = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(string $nom): static
    {
        $this->nom = $nom;

        return $this;
    }

    public function getMarque(): ?string
    {
        return $this->marque;
    }

    public function setMarque(string $marque): static
    {
        $this->marque = $marque;

        return $this;
    }

    /**
     * @return Collection<int, Pilote>
     */
    public function getPilotes(): Collection
    {
        return $this->pilotes;
    }

    public function addPilote(Pilote $pilote): static
    {
        if (!$this->pilotes->contains($pilote)) {
            $this->pilotes->add($pilote);
            $pilote->setEcurie($this);
        }

        return $this;
    }

    public function removePilote(Pilote $pilote): static
    {
        if ($this->pilotes->removeElement($pilote)) {
            // set the owning side to null (unless already changed)
            if ($pilote->getEcurie() === $this) {
                $pilote->setEcurie(null);
            }
        }

        return $this;
    }
}
