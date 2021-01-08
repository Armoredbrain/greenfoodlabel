<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\TypeRepository")
 */
class Type
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $name;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Facility", mappedBy="type")
     */
    private $facilities;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Criteria", mappedBy="type")
     */
    private $criterias;

    public function __construct()
    {
        $this->facilities = new ArrayCollection();
        $this->criterias = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
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
     * @return Collection|Facility[]
     */
    public function getFacilities(): Collection
    {
        return $this->facilities;
    }

    public function addFacility(Facility $facility): self
    {
        if (!$this->facilities->contains($facility)) {
            $this->facilities[] = $facility;
            $facility->setType($this);
        }

        return $this;
    }

    public function removeFacility(Facility $facility): self
    {
        if ($this->facilities->contains($facility)) {
            $this->facilities->removeElement($facility);
            // set the owning side to null (unless already changed)
            if ($facility->getType() === $this) {
                $facility->setType(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Criteria[]
     */
    public function getCriterias(): Collection
    {
        return $this->criterias;
    }

    public function addCriteria(Criteria $criteria): self
    {
        if (!$this->criterias->contains($criteria)) {
            $this->criterias[] = $criteria;
            $criteria->addType($this);
        }

        return $this;
    }

    public function removeCriteria(Criteria $criteria): self
    {
        if ($this->criterias->contains($criteria)) {
            $this->criterias->removeElement($criteria);
            $criteria->removeType($this);
        }

        return $this;
    }
}
