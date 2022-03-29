<?php

namespace App\Entity;

use App\Repository\VilleRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=VilleRepository::class)
 */
class Ville
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @Assert\NotBlank(message="Vous devez renseigner une ville")
     * @ORM\Column(type="string", length=100, unique=true)
     */
    private $nom;

    /**
     * @Assert\NotBlank(message="Vous devez renseigner le Code Postal")
     * @Assert\Regex(pattern="/^[0-9]+$/",message="Le code postal doit comporter des chiffres uniquement")
     * @Assert\Length(
     *      min = 5,
     *      max = 5,
     *     exactMessage="Votre code postal doit comporter {{ limit }} chiffres"
     * )
     * @ORM\Column(type="string", length=10)
     */
    private $codePostal;

    /**
     * @ORM\OneToMany(targetEntity=Lieu::class, mappedBy="noVille")
     */
    private $lieus;

    public function __construct()
    {
        $this->lieus = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(string $nom): self
    {
        $this->nom = $nom;

        return $this;
    }

    public function getCodePostal(): ?string
    {
        return $this->codePostal;
    }

    public function setCodePostal(string $codePostal): self
    {
        $this->codePostal = $codePostal;

        return $this;
    }

    /**
     * @return Collection<int, Lieu>
     */
    public function getLieus(): Collection
    {
        return $this->lieus;
    }

    public function addLieu(Lieu $lieu): self
    {
        if (!$this->lieus->contains($lieu)) {
            $this->lieus[] = $lieu;
            $lieu->setNoVille($this);
        }

        return $this;
    }

    public function removeLieu(Lieu $lieu): self
    {
        if ($this->lieus->removeElement($lieu)) {
            // set the owning side to null (unless already changed)
            if ($lieu->getNoVille() === $this) {
                $lieu->setNoVille(null);
            }
        }

        return $this;
    }
}
