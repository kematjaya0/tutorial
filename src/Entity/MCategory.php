<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\MCategoryRepository")
 */
class MCategory
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank(message="wajib diisi") // tambahkan kode berikut
     */
    private $code;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank(message="wajib diisi") // tambahkan kode berikut
     */
    private $name;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\MNews", mappedBy="category")
     */
    private $mNews;

    public function __construct()
    {
        $this->mNews = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCode(): ?string
    {
        return $this->code;
    }

    public function setCode(string $code): self
    {
        $this->code = $code;

        return $this;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return Collection|MNews[]
     */
    public function getMNews(): Collection
    {
        return $this->mNews;
    }

    public function addMNews(MNews $mNews): self
    {
        if (!$this->mNews->contains($mNews)) {
            $this->mNews[] = $mNews;
            $mNews->setCategory($this);
        }

        return $this;
    }

    public function removeMNews(MNews $mNews): self
    {
        if ($this->mNews->contains($mNews)) {
            $this->mNews->removeElement($mNews);
            // set the owning side to null (unless already changed)
            if ($mNews->getCategory() === $this) {
                $mNews->setCategory(null);
            }
        }

        return $this;
    }
}
