<?php

namespace App\Entity;
use ApiPlatform\Metadata\ApiResource;
use Doctrine\ORM\Mapping as ORM;

class HammerRest
{
    /**
     * @var string
     *
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue()
     *
     * Name of hammer
     */
    private string $name;

    /**
     * @var string
     *
     * Description of hammer
     *
     * @ORM\Column(type="text")
     */
    private string $description;

    /**
     * @var string
     *
     * CountryCode of hammer
     *
     * @ORM\Column(length=2)
     */
    private string $countryCode;

    /**
     * @var \DateTimeInterface|null
     *
     *@ORM\Column(type="datetime")
     */
    private ?\DateTimeInterface $listDate = null;

    /**
     * @var int
     * @ORM\Column(type="integer")
     */
    public int $price;

    /**
     * @return string
     */
    public function getDescription(): string
    {
        return $this->description;
    }

    /**
     * @param string $description
     */
    public function setDescription(string $description): void
    {
        $this->description = $description;
    }

    /**
     * @return string
     */
    public function getCountryCode(): string
    {
        return $this->countryCode;
    }

    /**
     * @param string $countryCode
     */
    public function setCountryCode(string $countryCode): void
    {
        $this->countryCode = $countryCode;
    }

    /**
     * @return \DateTimeInterface|null
     */
    public function getListDate(): ?\DateTimeInterface
    {
        return $this->listDate;
    }

    /**
     * @param \DateTimeInterface|null $listDate
     */
    public function setListDate(?\DateTimeInterface $listDate): void
    {
        $this->listDate = $listDate;
    }


    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @param string $name
     */
    public function setName(string $name): void
    {
        $this->name = $name;
    }


}
