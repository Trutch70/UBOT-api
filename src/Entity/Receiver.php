<?php

declare(strict_types=1);

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
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

    #[ORM\ManyToMany(targetEntity: Location::class)]
    private iterable $locations;

    #[ORM\ManyToOne(targetEntity: Industry::class)]
    #[ORM\JoinColumn(name: 'industry_id', referencedColumnName: 'id', nullable: true)]
    private Industry $industry;

    #[ORM\Column(type: 'boolean')]
    private bool $internationalShipping;

    #[ORM\OneToMany(mappedBy: 'receiver', targetEntity: Link::class)]
    private Collection $links;

    #[ORM\Column(type: 'string', length: 255)]
    private string $donationDescription;

    #[ORM\Column(type: 'simple_array', nullable: true)]
    private array $images;

    #[ORM\Column(type: 'integer', nullable: true)]
    private ?int $order;

    public function __construct()
    {
        $this->locations = new ArrayCollection();
    }

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

    public function getImages(): array
    {
        return $this->images;
    }

    public function setImages(array $images): void
    {
        $this->images = $images;
    }

    public function addImage(string $image): void
    {
        $this->images[] = $image;
    }

    public function getLocations(): iterable
    {
        return $this->locations;
    }

    public function setLocations(iterable $locations): void
    {
        $this->locations = $locations;
    }

    public function getOrder(): ?int
    {
        return $this->order;
    }

    public function setOrder(?int $order): void
    {
        $this->order = $order;
    }
}
