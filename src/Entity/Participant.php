<?php

namespace App\Entity;

use App\Repository\ParticipantRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=ParticipantRepository::class)
 * @UniqueEntity(fields={"pseudo"}, message="Ce pseudo est déjà utilisé")
 * @UniqueEntity(fields={"mail"}, message="Un compte avec cet email existe déjà")
 */
class Participant implements UserInterface, PasswordAuthenticatedUserInterface
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @Assert\Type(type="alnum",message="Votre pseudo ne doit contenir uniquement des chiffres et des lettres")
     * @Assert\Length(
     *     min = 2,
     *     max = 30,
     *     minMessage="Votre pseudo doit contenir {{ limit }} caractères minimum",
     *     maxMessage="Votre pseudo ne doit pas contenir plus de {{ limit }} caractères")
     * @Assert\NotBlank(message="Vous devez saisir un pseudo")
     * @ORM\Column(type="string", length=180, unique=true)
     */
    private $pseudo;

    /**
     * @ORM\Column(type="json")
     */
    private $roles = [];

    /**
     * @var string The hashed password
     * @ORM\Column(type="string")
     */
    private $password;

    /**
     * @Assert\Length(
     *     min = 2,
     *     max = 30,
     *     minMessage="Votre nom doit contenir {{ limit }} caractères minimum",
     *     maxMessage="Votre nom ne doit pas contenir plus de {{ limit }} caractères")
     * @Assert\NotBlank(message="Vous devez saisir votre nom")
     * @ORM\Column(type="string", length=30)
     */
    private $nom;

    /**
     * @Assert\Length(
     *     min = 2,
     *     max = 30,
     *     minMessage="Votre prenom doit contenir {{ limit }} caractères minimum",
     *     maxMessage="Votre prenom ne doit pas contenir plus de {{ limit }} caractères")
     * @Assert\NotBlank(message="Vous devez saisir votre prenom")
     * @ORM\Column(type="string", length=30)
     */
    private $prenom;

    /**
     * @Assert\NotBlank(message="Vous devez saisir votre numéro de téléphone")
     * @Assert\Regex(pattern="/^[0-9]+$/",message="Votre numéro de téléphone doit comporter des chiffres uniquement")
     * @Assert\Length(
     *      min = 10,
     *      max = 10,
     *     exactMessage="Votre numéro de téléphone doit comporter {{ limit }} chiffres"
     * )
     * @ORM\Column(type="string", length=15)
     */
    private $telephone;

    /**
     * @Assert\Email(message="Votre email n'est pas valide")
     * @Assert\NotBlank(message="Vous devez saisir votre email")
     * @ORM\Column(type="string", length=50, unique=true)
     */
    private $mail;

    /**
     * @ORM\Column(type="boolean")
     */
    private $actif;

    /**
     * @ORM\ManyToOne(targetEntity=Site::class, inversedBy="participants")
     * @ORM\JoinColumn(nullable=false)
     */
    private $noSite;

    /**
     * @ORM\OneToMany(targetEntity=Sortie::class, mappedBy="organisateur")
     */
    private $sorties;

    /**
     * @ORM\ManyToMany(targetEntity=Sortie::class, inversedBy="participants")
     */
    private $inscription;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $photo;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $resetToken;

    public function __construct()
    {

        $this->setActif(true);
        $this->sorties = new ArrayCollection();
        $this->inscription = new ArrayCollection();

    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getPseudo(): ?string
    {
        return $this->pseudo;
    }

    public function setPseudo(string $pseudo): self
    {
        $this->pseudo = $pseudo;

        return $this;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUserIdentifier(): string
    {
        return (string)$this->pseudo;
    }

    /**
     * @deprecated since Symfony 5.3, use getUserIdentifier instead
     */
    public function getUsername(): string
    {
        return (string)$this->pseudo;
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        if (!$this->getActif()) {
            $roles = ['ROLE_NON'];
        }else{
            $roles = $this->roles;
            // guarantee every user at least has ROLE_USER
            $roles[] = 'ROLE_USER';
        }



        return array_unique($roles);
    }

    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see PasswordAuthenticatedUserInterface
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    /**
     * Returning a salt is only needed, if you are not using a modern
     * hashing algorithm (e.g. bcrypt or sodium) in your security.yaml.
     *
     * @see UserInterface
     */
    public function getSalt(): ?string
    {
        return null;
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials()
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
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

    public function getPrenom(): ?string
    {
        return $this->prenom;
    }

    public function setPrenom(string $prenom): self
    {
        $this->prenom = $prenom;

        return $this;
    }

    public function getTelephone(): ?string
    {
        return $this->telephone;
    }

    public function setTelephone(string $telephone): self
    {
        $this->telephone = $telephone;

        return $this;
    }

    public function getMail(): ?string
    {
        return $this->mail;
    }

    public function setMail(string $mail): self
    {
        $this->mail = $mail;

        return $this;
    }

    public function getActif(): ?bool
    {
        return $this->actif;
    }

    public function setActif(bool $actif): self
    {
        $this->actif = $actif;

        return $this;
    }

    public function getNoSite(): ?Site
    {
        return $this->noSite;
    }

    public function setNoSite(?Site $noSite): self
    {
        $this->noSite = $noSite;

        return $this;
    }

    /**
     * @return Collection<int, Sortie>
     */
    public function getSorties(): Collection
    {
        return $this->sorties;
    }

    public function addSorty(Sortie $sorty): self
    {
        if (!$this->sorties->contains($sorty)) {
            $this->sorties[] = $sorty;
            $sorty->setOrganisateur($this);
        }

        return $this;
    }

    public function removeSorty(Sortie $sorty): self
    {
        if ($this->sorties->removeElement($sorty)) {
            // set the owning side to null (unless already changed)
            if ($sorty->getOrganisateur() === $this) {
                $sorty->setOrganisateur(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Sortie>
     */
    public function getInscription(): Collection
    {
        return $this->inscription;
    }

    public function addInscription(Sortie $inscription): self
    {
        if (!$this->inscription->contains($inscription)) {
            $this->inscription[] = $inscription;
        }

        return $this;
    }

    public function removeInscription(Sortie $inscription): self
    {
        $this->inscription->removeElement($inscription);

        return $this;
    }

    public function getPhoto(): ?string
    {
        return $this->photo;
    }

    public function setPhoto(?string $photo): self
    {
        $this->photo = $photo;

        return $this;
    }

    public function getResetToken(): ?string
    {
        return $this->resetToken;
    }

    public function setResetToken(?string $resetToken): self
    {
        $this->resetToken = $resetToken;

        return $this;
    }
}
