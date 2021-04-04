<?php

namespace App\Entity;

use App\Repository\BlogCommentRepository;
use Doctrine\ORM\Mapping as ORM;
use phpDocumentor\Reflection\Types\Integer;
use Symfony\Component\Form\FormTypeInterface;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=BlogCommentRepository::class)
 */
class BlogComment
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $Contenu;


    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $Time;

    /**
     * @Assert\Type("string")
     * @ORM\Column(type="string", length=255)
     */
    private $Name;

    /**
     * @Assert\Email()
     * @ORM\Column(type="string", length=100)
     */
    private $email;

    /**
     * @ORM\ManyToOne(targetEntity=Blog::class, inversedBy="blogComments")
     * @ORM\JoinColumn(onDelete="CASCADE")
     */
    public $blog_id;


    public function getId(): ?int
    {
        return $this->id;
    }

    public function getContenu(): ?string
    {
        return $this->Contenu;
    }

    public function setContenu(?string $Contenu): self
    {
        $this->Contenu = $Contenu;

        return $this;
    }



    public function getTime(): ?\DateTimeInterface
    {
        return $this->Time;
    }

    public function setTime(?\DateTimeInterface $Time): self
    {
        $this->Time = $Time;

        return $this;
    }

    public function getName(): ?string
    {
        return $this->Name;
    }

    public function setName(string $Name): self
    {
        $this->Name = $Name;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    public function getBlogId(): ?Blog
    {
        return $this->blog_id;
    }

    public function setBlogId(?Blog $BlogId): self
    {
        $this->blog_id = $BlogId;

        return $this;
    }

    public function __toString()
    {
        // to show the name of the Category in the select
        return(string) $this->id;
        // to show the id of the Category in the select
        // return $this->id;
    }


}
