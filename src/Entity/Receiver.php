<?php

declare(strict_types=1);

namespace App\Entity;

use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
class Receiver
{
    #[ORM\Id, ORM\Column(type: 'integer'), ORM\GeneratedValue(strategy: 'AUTO')]
    private int $id;

    #[ORM\Column(type: 'string', length: 255)]
    private string $name;

    #[ORM\Column(type: 'text')]
    private string $description;

    #[ORM\Column(type: 'string', length: 255)]
    private string $bankAccount;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private ?string $imagePath = null;

    #[ORM\ManyToOne(targetEntity: Location::class)]
    #[ORM\JoinColumn(name: 'location_id', referencedColumnName: 'id', nullable: true)]
    private ?Location $location;

    #[ORM\ManyToOne(targetEntity: Industry::class)]
    #[ORM\JoinColumn(name: 'industry_id', referencedColumnName: 'id', nullable: true)]
    private Industry $industry;

    #[ORM\Column(type: 'boolean')]
    private bool $internationalShipping;

    #[ORM\OneToMany(mappedBy: 'receiver', targetEntity: Link::class)]
    private Collection $links;

    #[ORM\Column(type: 'string', length: 255)]
    private string $donationDescription;

    public function getId(): int
    {
        return $this->id;
    }

    public function setId(int $id): void
    {
        $this->id = $id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): void
    {
        $this->name = $name;
    }

    public function getDescription(): string
    {
        return $this->description;
    }

    public function setDescription(string $description): void
    {
        $this->description = $description;
    }

    public function getBankAccount(): string
    {
        return $this->bankAccount;
    }

    public function setBankAccount(string $bankAccount): void
    {
        $this->bankAccount = $bankAccount;
    }

    public function getImagePath(): ?string
    {
        return $this->imagePath;
    }

    public function setImagePath(?string $imagePath): void
    {
        $this->imagePath = $imagePath;
    }

    public function getLocation(): ?Location
    {
        return $this->location;
    }

    public function setLocation(?Location $location): void
    {
        $this->location = $location;
    }

    public function getIndustry(): Industry
    {
        return $this->industry;
    }

    public function setIndustry(Industry $industry): void
    {
        $this->industry = $industry;
    }

    public function isInternationalShipping(): bool
    {
        return $this->internationalShipping;
    }

    public function setInternationalShipping(bool $internationalShipping): void
    {
        $this->internationalShipping = $internationalShipping;
    }

    /**
     * @return Collection<Link>
     */
    public function getLinks(): Collection
    {
        return $this->links;
    }

    public function setLinks(Collection $links): void
    {
        $this->links = $links;
    }

    public function addLink(Link $link): void
    {
        $this->links->add($link);
    }

    public function getDonationDescription(): string
    {
        return $this->donationDescription;
    }

    public function setDonationDescription(string $donationDescription): void
    {
        $this->donationDescription = $donationDescription;
    }
}
