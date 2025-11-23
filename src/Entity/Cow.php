<?php

namespace App\Entity;

use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use App\Repository\CowRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: CowRepository::class)]
class Cow
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $code = null;

    #[ORM\Column]
    private ?float $milkperweek = null;

    #[ORM\Column]
    private ?float $foodperweek = null;

    #[ORM\Column]
    private ?float $weight = null;

    // Validação: data não pode ser futura
    #[Assert\LessThanOrEqual("today", message: "A data de nascimento não pode ser futura.")]
    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTime $birthdate = null;

    #[ORM\ManyToOne(inversedBy: 'cows')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Farm $farm = null;

    #[ORM\Column]
    private ?bool $isslaughtered = null;

    #[ORM\Column(type: Types::DATE_MUTABLE, nullable: true)]
    private ?\DateTime $slaughterdate = null;

    #[ORM\Column]
    private ?bool $isalive = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTime $createdat = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTime $updatedat = null;

    public function __construct()
    {
        $this->createdat = new \DateTime();
        $this->updatedat = new \DateTime();
        $this->isalive = true;         // animal nasce vivo por padrão
        $this->isslaughtered = false;  // não abatido por padrão
    }


    // Getters e Setters
    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCode(): ?string
    {
        return $this->code;
    }

    public function setCode(string $code): static
    {
        $this->code = $code;
        return $this;
    }

    public function getMilkperweek(): ?float
    {
        return $this->milkperweek;
    }

    public function setMilkperweek(float $milkperweek): static
    {
        $this->milkperweek = $milkperweek;
        return $this;
    }

    public function getFoodperweek(): ?float
    {
        return $this->foodperweek;
    }

    public function setFoodperweek(float $foodperweek): static
    {
        $this->foodperweek = $foodperweek;
        return $this;
    }

    public function getWeight(): ?float
    {
        return $this->weight;
    }

    public function setWeight(float $weight): static
    {
        $this->weight = $weight;
        return $this;
    }

    public function getBirthdate(): ?\DateTime
    {
        return $this->birthdate;
    }

    public function setBirthdate(\DateTime $birthdate): static
    {
        $this->birthdate = $birthdate;
        return $this;
    }

    public function getFarm(): ?Farm
    {
        return $this->farm;
    }

    public function setFarm(?Farm $farm): static
    {
        $this->farm = $farm;
        return $this;
    }

    public function isSlaughtered(): ?bool
    {
        return $this->isslaughtered;
    }

    public function setIsslaughtered(bool $isslaughtered): static
    {
        $this->isslaughtered = $isslaughtered;
        return $this;
    }

    public function getSlaughterdate(): ?\DateTime
    {
        return $this->slaughterdate;
    }

    public function setSlaughterdate(?\DateTime $slaughterdate): static
    {
        $this->slaughterdate = $slaughterdate;
        return $this;
    }

    public function isAlive(): ?bool
    {
        return $this->isalive;
    }

    public function setIsalive(bool $isalive): static
    {
        $this->isalive = $isalive;
        return $this;
    }

    public function getCreatedat(): ?\DateTime
    {
        return $this->createdat;
    }

    public function setCreatedat(\DateTime $createdat): static
    {
        $this->createdat = $createdat;
        return $this;
    }

    public function getUpdatedat(): ?\DateTime
    {
        return $this->updatedat;
    }

    public function setUpdatedat(\DateTime $updatedat): static
    {
        $this->updatedat = $updatedat;
        return $this;
    }

    public function canBeSlaughtered(): bool
    {
        $age = $this->getBirthdate()->diff(new \DateTime())->y;
        $foodPerDay = $this->foodperweek / 7;
        $weightArroba = $this->weight / 15;

        return
            $age > 5 ||
            $this->milkperweek < 40 ||
            ($this->milkperweek < 70 && $foodPerDay > 50) ||
            $weightArroba > 18;
    }

}
