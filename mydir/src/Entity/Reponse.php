<?php

namespace App\Entity;

use App\Repository\ReponseRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=ReponseRepository::class)
 */
class Reponse
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $reponse;

    /**
     * @ORM\OneToMany(targetEntity=ReponseLike::class, mappedBy="reponse")
     */
    private $likes;

    /**
     * @ORM\ManyToOne(targetEntity=Question::class)
     * @ORM\JoinColumn(nullable=false)
     */
    private $question;

    /**
     * @ORM\OneToMany(targetEntity=ReponseLike::class, mappedBy="reponse", orphanRemoval=true)
     */
    private $reaction;

    /**
     * @ORM\Column(type="datetime")
     */
    private $datereponse;



    public function __construct()
    {
        $this->likes = new ArrayCollection();
        $this->reaction = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getReponse(): ?string
    {
        return $this->reponse;
    }

    public function setReponse(string $reponse): self
    {
        $this->reponse = $reponse;

        return $this;
    }



    public function getQuestion(): ?Question
    {
        return $this->question;
    }

    public function setQuestion(?Question $question): self
    {
        $this->question = $question;

        return $this;
    }

    /**
     * @return Collection|ReponseLike[]
     */
    public function getReaction(): Collection
    {
        return $this->reaction;
    }

    public function addReaction(ReponseLike $reaction): self
    {
        if (!$this->reaction->contains($reaction)) {
            $this->reaction[] = $reaction;
            $reaction->setReponse($this);
        }

        return $this;
    }

    public function removeReaction(ReponseLike $reaction): self
    {
        if ($this->reaction->removeElement($reaction)) {
            // set the owning side to null (unless already changed)
            if ($reaction->getReponse() === $this) {
                $reaction->setReponse(null);
            }
        }

        return $this;
    }

    public function getDatereponse(): ?\DateTimeInterface
    {
        return $this->datereponse;
    }

    public function setDatereponse(?\DateTimeInterface $datereponse): self
    {
        $this->datereponse = $datereponse;

        return $this;
    }



}
