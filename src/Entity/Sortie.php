<?php

namespace App\Entity;

use App\Repository\SortieRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=SortieRepository::class)
 */
class Sortie
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @Assert\NotBlank(message="Veuillez donner un nom à votre sortie")
     * @ORM\Column(type="string", length=100)
     */
    private $nom;

    /**
     * @Assert\Expression(
     *     "this.getDateHeureDebut() > this.getDateFinInscription()",
     *     message="La date de fin d'inscription ne doit pas être antérieure à la date de début de la sortie")
     * @Assert\NotBlank(message="Veuillez donner une date et une heure de début à votre sortie")
     * @ORM\Column(type="datetime")
     */
    private $dateHeureDebut;

    /**
     * @Assert\NotBlank(message="Veuillez donner une durée à votre sortie")
     * @ORM\Column(type="integer", nullable=true)
     */
    private $duree;

    /**
     * @Assert\NotBlank(message="Veuillez donner un nombre maximum de participants d'inscription à votre sortie")
     * @Assert\Regex(pattern="/^[0-9]+$/",message="Votre numéro de téléphone doit comporter des chiffres uniquement")
     * @Assert\Range(
     *      min = 5,
     *      max = 30,
     *      notInRangeMessage = "Le nombre de participants doit être compris entre {{ min }} et {{ max }}.",
     * )
     * @ORM\Column(type="integer")
     */
    private $nbInscriptionMax;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $description;

    /**
     * @Assert\GreaterThan("today",message="La date de fin d'inscription doit être supérieur à celle d'aujourd'hui")
     * @Assert\NotBlank(message="Veuillez donner une date de fin d'inscription à votre sortie")
     * @ORM\Column(type="datetime")
     */
    private $dateFinInscription;

    /**
     * @ORM\ManyToOne(targetEntity=Etat::class, inversedBy="sorties")
     */
    private $etat;

    /**
     * @ORM\ManyToOne(targetEntity=Participant::class, inversedBy="sorties")
     * @ORM\JoinColumn(nullable=false)
     */
    private $organisateur;

    /**
     * @ORM\ManyToMany(targetEntity=Participant::class, mappedBy="inscription")
     */
    private $participants;

    /**
     * @ORM\ManyToOne(targetEntity=Site::class, inversedBy="sorties")
     * @ORM\JoinColumn(nullable=false)
     */
    private $siteOrganisateur;

    /**
     * @Assert\NotBlank(message="Veuillez choisir un lieu pour votre sortie")
     * @ORM\ManyToOne(targetEntity=Lieu::class, inversedBy="sorties")
     * @ORM\JoinColumn(nullable=false)
     */
    private $noLieu;

    public function __construct()
    {
        $this->participants = new ArrayCollection();
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

    public function getDateHeureDebut(): ?\DateTimeInterface
    {
        return $this->dateHeureDebut;
    }

    public function setDateHeureDebut(\DateTimeInterface $dateHeureDebut): self
    {
        $this->dateHeureDebut = $dateHeureDebut;

        return $this;
    }

    public function getDuree(): ?int
    {
        return $this->duree;
    }

    public function setDuree(?int $duree): self
    {
        $this->duree = $duree;

        return $this;
    }

    public function getNbInscriptionMax(): ?int
    {
        return $this->nbInscriptionMax;
    }

    public function setNbInscriptionMax(int $nbInscriptionMax): self
    {
        $this->nbInscriptionMax = $nbInscriptionMax;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getDateFinInscription(): ?\DateTimeInterface
    {
        return $this->dateFinInscription;
    }

    public function setDateFinInscription(\DateTimeInterface $dateFinInscription): self
    {
        $this->dateFinInscription = $dateFinInscription;

        return $this;
    }

    public function getEtat(): ?Etat
    {
        return $this->etat;
    }

    public function setEtat(?Etat $etat): self
    {
        $this->etat = $etat;

        return $this;
    }

    public function getOrganisateur(): ?Participant
    {
        return $this->organisateur;
    }

    public function setOrganisateur(?Participant $organisateur): self
    {
        $this->organisateur = $organisateur;

        return $this;
    }

    /**
     * @return Collection<int, Participant>
     */
    public function getParticipants(): Collection
    {
        return $this->participants;
    }

    public function addParticipant(Participant $participant): self
    {
        if (!$this->participants->contains($participant)) {
            $this->participants[] = $participant;
            $participant->addInscription($this);
        }

        return $this;
    }

    public function removeParticipant(Participant $participant): self
    {
        if ($this->participants->removeElement($participant)) {
            $participant->removeInscription($this);
        }

        return $this;
    }

    public function getSiteOrganisateur(): ?Site
    {
        return $this->siteOrganisateur;
    }

    public function setSiteOrganisateur(?Site $siteOrganisateur): self
    {
        $this->siteOrganisateur = $siteOrganisateur;

        return $this;
    }

    public function getNoLieu(): ?Lieu
    {
        return $this->noLieu;
    }

    public function setNoLieu(?Lieu $noLieu): self
    {
        $this->noLieu = $noLieu;

        return $this;
    }
}
