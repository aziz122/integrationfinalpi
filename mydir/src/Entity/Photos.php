<?php

namespace App\Entity;

use App\Repository\PhotosRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=PhotosRepository::class)
 */
class Photos
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $name;

    /**
     * @ORM\ManyToOne(targetEntity=Exposee::class, inversedBy="photo")
     */
    private $exposee;

    /**
     * @ORM\ManyToOne(targetEntity=Blog::class, inversedBy="name")
     * @ORM\JoinColumn(onDelete="CASCADE")
     */
    private $Blog;

    public function getBlog(): ?Blog
    {
        return $this->Blog;
    }

    public function setBlog(?Blog $Blog): self
    {
        $this->Blog = $Blog;

        return $this;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(?string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getExposee(): ?Exposee
    {
        return $this->exposee;
    }

    public function setExposee(?Exposee $exposee): self
    {
        $this->exposee = $exposee;

        return $this;
    }
}
