<?php

namespace App\Entity;

use App\Repository\AppointmentRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: AppointmentRepository::class)]
class Appointment
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $dateRendezVous = null;


    #[ORM\Column(length: 255)]
    private ?string $serviceDemande = null;

    #[ORM\Column(length: 255)]
    private ?string $statut = null;

    #[ORM\ManyToOne(targetEntity: User::class, inversedBy: "appointments")]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $client = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(?int $id): void
    {
        $this->id = $id;
    }

    public function getDateRendezVous(): ?\DateTimeInterface
    {
        return $this->dateRendezVous;
    }

    public function setDateRendezVous(?\DateTimeInterface $dateRendezVous): void
    {
        $this->dateRendezVous = $dateRendezVous;
    }

    public function getServiceDemande(): ?string
    {
        return $this->serviceDemande;
    }

    public function setServiceDemande(?string $serviceDemande): void
    {
        $this->serviceDemande = $serviceDemande;
    }

    public function getStatut(): ?string
    {
        return $this->statut;
    }

    public function setStatut(?string $statut): void
    {
        $this->statut = $statut;
    }

    public function getClient(): ?User
    {
        return $this->client;
    }

    public function setClient(?User $client): void
    {
        $this->client = $client;
    }
}
