<?php

namespace App\Entity;
use Symfony\Component\Security\Core\Validator\Constraints as SecurityAssert;
use App\Repository\ChangePasswordRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=ChangePasswordRepository::class)
 */
class ChangePassword
{
    /**
     * @SecurityAssert\UserPassword(
     *     message = "Mauvais mot de passe"
     * )
     */
    protected $oldPassword;
    /**
     * @ORM\Column(type="string", length=255)
     */
    protected $newPassword;
    /**
     * @ORM\Column(type="string", length=255)
     */
    protected $newConfirm;
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getOldPassword(): ?string
    {
        return $this->oldPassword;
    }

    public function setOldPassword(string $oldPassword): self
    {
        $this->oldPassword = $oldPassword;

        return $this;
    }

    public function getNewPassword(): ?string
    {
        return $this->newPassword;
    }

    public function setNewPassword(string $newPassword): self
    {
        $this->newPassword = $newPassword;

        return $this;
    }

    public function getNewConfirm(): ?string
    {
        return $this->newConfirm;
    }

    public function setNewConfirm(string $newConfirm): self
    {
        $this->newConfirm = $newConfirm;

        return $this;
    }
}
