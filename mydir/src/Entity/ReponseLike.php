<?php

namespace App\Entity;

use App\Repository\ReponseLikeRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=ReponseLikeRepository::class)
 */
class ReponseLike
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;



    /**
     * @ORM\Column(type="integer")
     */
    private $reaction;

    /**
     * @ORM\ManyToOne(targetEntity=Reponse::class, inversedBy="reaction")
     * @ORM\JoinColumn(nullable=false)
     */
    private $reponse;

    public function getId(): ?int
    {
        return $this->id;
    }


    public function getReaction(): ?int
    {
        return $this->reaction;
    }

    public function setReaction(int $reaction): self
    {
        $this->reaction = $reaction;

        return $this;
    }

    public function getReponse(): ?Reponse
    {
        return $this->reponse;
    }

    public function setReponse(?Reponse $reponse): self
    {
        $this->reponse = $reponse;

        return $this;
    }

}
