<?php

namespace App\Entity;

use App\Repository\ProgrammeRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=ProgrammeRepository::class)
 */
class Programme
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=Session::class, inversedBy="programmes")
     * @ORM\JoinColumn(nullable=false)
     */
    private $Session;

    /**
     * @ORM\ManyToOne(targetEntity=Module::class, inversedBy="programmes")
     * @ORM\JoinColumn(nullable=false)
     */
    private $Module;

    /**
     * @ORM\Column(type="integer")
     */
    private $duration;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getSession(): ?Session
    {
        return $this->Session;
    }

    public function setSession(?Session $Session): self
    {
        $this->Session = $Session;

        return $this;
    }

    public function getModule(): ?Module
    {
        return $this->Module;
    }

    public function setModule(?Module $Module): self
    {
        $this->Module = $Module;

        return $this;
    }

    public function getDuration(): ?int
    {
        return $this->duration;
    }

    public function setDuration(int $duration): self
    {
        $this->duration = $duration;

        return $this;
    }
    public function __toString(){
        return $this->Module." dure ".$this->duration." jours";
    }
}
