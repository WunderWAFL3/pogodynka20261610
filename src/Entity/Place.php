<?php

namespace App\Entity;

use App\Repository\PlaceRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: PlaceRepository::class)]
class Place
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $city = null;

    #[ORM\Column(length: 2)]
    private ?string $country = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 10, scale: 7)]
    private ?string $latitude = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 10, scale: 7)]
    private ?string $longitude = null;

    /**
     * @var Collection<int, WeatherRecord>
     */
    #[ORM\OneToMany(targetEntity: WeatherRecord::class, mappedBy: 'place')]
    private Collection $weatherRecords;

    public function __construct()
    {
        $this->weatherRecords = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCity(): ?string
    {
        return $this->city;
    }

    public function setCity(string $city): static
    {
        $this->city = $city;

        return $this;
    }

    public function getCountry(): ?string
    {
        return $this->country;
    }

    public function setCountry(string $country): static
    {
        $this->country = $country;

        return $this;
    }

    public function getLatitude(): ?string
    {
        return $this->latitude;
    }

    public function setLatitude(string $latitude): static
    {
        $this->latitude = $latitude;

        return $this;
    }

    public function getLongitude(): ?string
    {
        return $this->longitude;
    }

    public function setLongitude(string $longitude): static
    {
        $this->longitude = $longitude;

        return $this;
    }

    /**
     * @return Collection<int, WeatherRecord>
     */
    public function getWeatherRecords(): Collection
    {
        return $this->weatherRecords;
    }

    public function addWeatherRecord(WeatherRecord $weatherRecord): static
    {
        if (!$this->weatherRecords->contains($weatherRecord)) {
            $this->weatherRecords->add($weatherRecord);
            $weatherRecord->setPlace($this);
        }

        return $this;
    }

    public function removeWeatherRecord(WeatherRecord $weatherRecord): static
    {
        if ($this->weatherRecords->removeElement($weatherRecord)) {
            // set the owning side to null (unless already changed)
            if ($weatherRecord->getPlace() === $this) {
                $weatherRecord->setPlace(null);
            }
        }

        return $this;
    }
}
