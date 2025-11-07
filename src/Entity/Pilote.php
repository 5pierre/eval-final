<?php

namespace App\Entity;

use App\Repository\PiloteRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: PiloteRepository::class)]
class Pilote
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $prenom = null;

    #[ORM\Column(length: 255)]
    private ?string $nom = null;

    #[ORM\Column]
    private ?int $pointsLicence = null;

    #[ORM\Column]
    private ?bool $poste = null;

    #[ORM\ManyToOne(inversedBy: 'pilotes')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Ecurie $ecurie = null;

    /**
     * @var Collection<int, Infraction>
     */
    #[ORM\OneToMany(targetEntity: Infraction::class, mappedBy: 'pilote')]
    private Collection $infraction;

    public function __construct()
    {
        $this->infraction = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getPrenom(): ?string
    {
        return $this->prenom;
    }

    public function setPrenom(string $prenom): static
    {
        $this->prenom = $prenom;

        return $this;
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

    public function getPointsLicence(): ?int
    {
        return $this->pointsLicence;
    }

    public function setPointsLicence(int $pointsLicence): static
    {
        $this->pointsLicence = $pointsLicence;

        return $this;
    }

    public function isPoste(): ?bool
    {
        return $this->poste;
    }

    public function setPoste(bool $poste): static
    {
        $this->poste = $poste;

        return $this;
    }

    public function getEcurie(): ?Ecurie
    {
        return $this->ecurie;
    }

    public function setEcurie(?Ecurie $ecurie): static
    {
        $this->ecurie = $ecurie;

        return $this;
    }

    /**
     * @return Collection<int, Infraction>
     */
    public function getInfraction(): Collection
    {
        return $this->infraction;
    }

    public function addInfraction(Infraction $infraction): static
    {
        if (!$this->infraction->contains($infraction)) {
            $this->infraction->add($infraction);
            $infraction->setPilote($this);
        }

        return $this;
    }

    public function removeInfraction(Infraction $infraction): static
    {
        if ($this->infraction->removeElement($infraction)) {
            // set the owning side to null (unless already changed)
            if ($infraction->getPilote() === $this) {
                $infraction->setPilote(null);
            }
        }

        return $this;
    }
}
