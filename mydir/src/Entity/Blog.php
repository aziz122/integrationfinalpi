<?php

namespace App\Entity;

use App\Repository\BlogRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Form\FormTypeInterface;
use Symfony\Component\Validator\Constraints as Assert;
//use Knp\Snappy\Pdf;

/**
 * @ORM\Entity(repositoryClass=BlogRepository::class)
 */
class Blog
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @Assert\Type("string")
     * @ORM\Column(type="string", length=60, nullable=true)
     */
    private $titre;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $contenu;

    /**
     * @ORM\Column(type="string", length=60, nullable=true)
     */
    private $tags;

    /**
     * @Assert\Type("string")
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $date_creation;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $date_update;

    /**
     * @ORM\OneToMany(targetEntity=BlogComment::class, mappedBy="blog_id",cascade={"remove"})
     * orphanRemoval=true
     */
    private $blogComments;

    /**
     * @ORM\Column(type="boolean", options={"default":"0"})
     */
    private $Flush = false;

    /**
     * @ORM\Column(type="integer")
     */
    private $vues=0;

    /**
     * @ORM\OneToMany(targetEntity=Photos::class, mappedBy="Blog",orphanRemoval=true,cascade={"persist"})
     */

    private $photo;

    private $snappy;


    public function __construct()
    {
        $this->blogComments = new ArrayCollection();
        $this->photo = new ArrayCollection();
    }


    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitre(): ?string
    {
        return $this->titre;
    }

    public function setTitre(?string $titre): self
    {
        $this->titre = $titre;

        return $this;
    }

    public function getContenu(): ?string
    {
        return $this->contenu;
    }

    public function setContenu(?string $contenu): self
    {
        $this->contenu = $contenu;

        return $this;
    }

    public function getTags(): ?string
    {
        return $this->tags;
    }

    public function setTags(?string $tags): self
    {
        $this->tags = $tags;

        return $this;
    }

    public function getDateCreation(): ?\DateTimeInterface
    {
        return $this->date_creation;
    }

    public function setDateCreation(?\DateTimeInterface $date_creation): self
    {
        $this->date_creation = $date_creation;

        return $this;
    }

    public function getDateUpdate(): ?\DateTimeInterface
    {
        return $this->date_update;
    }

    public function setDateUpdate(?\DateTimeInterface $date_update): self
    {
        $this->date_update = $date_update;

        return $this;
    }

    /**
     * @return Collection|BlogComment[]
     */
    public function getBlogComments(): Collection
    {
        return $this->blogComments;
    }

    public function addBlogComment(BlogComment $blogComment): self
    {
        if (!$this->blogComments->contains($blogComment)) {
            $this->blogComments[] = $blogComment;
            $blogComment->setBlogId($this);
        }

        return $this;
    }

    public function removeBlogComment(BlogComment $blogComment): self
    {
        if ($this->blogComments->removeElement($blogComment)) {
            // set the owning side to null (unless already changed)
            if ($blogComment->getBlogId() === $this) {
                $blogComment->setBlogId(null);
            }
        }

        return $this;
    }
    public function __toString()
{
    // to show the name of the Category in the select
    return(string) $this->id;
    // to show the id of the Category in the select
    // return $this->id;
}
    public function toInt(): ?int
    {
        // to show the name of the Category in the select
        return $this->id;
        // to show the id of the Category in the select
        // return $this->id;
    }

    public function getFlush(): ?bool
    {
        return $this->Flush;
    }

    public function setFlush(bool $Flush): self
    {
        $this->Flush = $Flush;
        return $this;
    }


    public function getVues(): ?int
    {
        return $this->vues;
    }

    public function setVues(int $vues): self
    {
        $this->vues = $vues;

        return $this;
    }

    /**
     * @return Collection|Photos[]
     */
    public function getName(): Collection
    {
        return $this->name;
    }

    public function addName(Photos $name): self
    {
        if (!$this->name->contains($name)) {
            $this->name[] = $name;
            $name->setBlog($this);
        }

        return $this;
    }

    public function removeName(Photos $name): self
    {
        if ($this->name->removeElement($name)) {
            // set the owning side to null (unless already changed)
            if ($name->getBlog() === $this) {
                $name->setBlog(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Photos[]
     */
    public function getPhoto(): Collection
    {
        return $this->photo;
    }

    public function addPhoto(Photos $photo): self
    {
        if (!$this->photo->contains($photo)) {
            $this->photo[] = $photo;
            $photo->setBlog($this);
        }

        return $this;
    }

    public function removePhoto(Photos $photo): self
    {
        if ($this->photo->removeElement($photo)) {
            // set the owning side to null (unless already changed)
            if ($photo->getBlog() === $this) {
                $photo->setBlog(null);
            }
        }

        return $this;
    }


}

