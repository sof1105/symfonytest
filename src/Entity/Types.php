<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity()
 */
class Types
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=50)
     */
    private $slug;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Cases", mappedBy="casesType", cascade={"persist", "remove", "merge"}, orphanRemoval=true)
     */
    public $cases;

    public function __construct()
    {
        $this->cases = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getSlug(): ?string
    {
        return $this->slug;
    }

    public function setSlug(string $slug): self
    {
        $this->slug = $slug;

        return $this;
    }

    /**
     * @return Collection|Cases[]
     */
    public function getCases(): Collection
    {
        return $this->cases;
    }

    public function addCase(Cases $case): self
    {
        if (!$this->cases->contains($case)) {
            $this->cases[] = $case;
            $case->setCasesType($this);
        }

        return $this;
    }

    public function removeCase(Cases $case): self
    {
        if ($this->cases->removeElement($case)) {
            // set the owning side to null (unless already changed)
            if ($case->getCasesType() === $this) {
                $case->setCasesType(null);
            }
        }

        return $this;
    }

    public function __toString()
    {
        return $this->slug;
    }


}
