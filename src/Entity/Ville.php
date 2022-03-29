<?php

namespace App\Entity;

use App\Repository\VilleRepository;
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
}
