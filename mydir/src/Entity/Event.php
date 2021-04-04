<?php

namespace App\Entity;

use App\Repository\EventRepository;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\HttpFoundation\File\UploadedFile;


/**
 * @ORM\Entity(repositoryClass=EventRepository::class)
 */
class Event
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
    private $Nom;

    /**
     * @ORM\Column(type="date")
     */
    private $Date_debut;

    /**
     * @ORM\Column(type="date")
     */
    private $date_fin;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $description;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $lieu;

    /**
     * @ORM\Column(type="integer")
     * @Assert\Positive
     */

    private $prix;

    /**
     * @return mixed
     */
    public function getFile()
    {
        return $this->file;
    }

    /**
     * @param mixed $file
     */
    public function setFile($file): void
    {
        $this->file = $file;
    }

    /**
     * @Assert\File(maxSize="700k")
     */
    public $file;
    /**
     * @return mixed;
     */


    /**
     * @ORM\Column(type="integer")
     */
    private $nb_places;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $type;

    /**
     * @var int
     * @ORM\Column(name="nb_signal",type="integer")
     */
    private $nb_signal;
    /**
     * @var
     * @ORM\ManyToMany(targetEntity="App\Entity\User")
     * @ORM\JoinTable(name="user_signal",
     *     joinColumns={
     *          @ORM\JoinColumn(name="event_id", referencedColumnName="id")
     *     },
     *     inverseJoinColumns={
     *          @ORM\JoinColumn(name="user_id", referencedColumnName="id")
     *     }
     * )
     */
    private $usersSignal;

    /**
     * @return mixed
     */
    public function getUsersSignal()
    {
        return $this->usersSignal;
    }

    /**
     * @param mixed $usersSignal
     */
    public function setUsersSignal($usersSignal): void
    {
        $this->usersSignal = $usersSignal;
    }


    /**
     * @return mixed
     */
    public function getNbSignal()
    {
        return $this->nb_signal;
    }

    /**
     * @param mixed $nb_signal
     */
    public function setNbSignal($nb_signal): void
    {
        $this->nb_signal = $nb_signal;
    }

    /**
     * @ORM\Column(type="string", length=255)
     */
    public $nom_image;

    /**
     * @return mixed
     */
    public function getNomImage()
    {
        return $this->nom_image;
    }

    /**
     * @param mixed $nom_image
     */
    public function setNomImage($nom_image): void
    {
        $this->nom_image = $nom_image;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNom(): ?string
    {
        return $this->Nom;
    }

    public function setNom(string $Nom): self
    {
        $this->Nom = $Nom;

        return $this;
    }

    public function getDateDebut(): ?\DateTimeInterface
    {
        return $this->Date_debut;
    }

    public function setDateDebut(\DateTimeInterface $Date_debut): self
    {
        $this->Date_debut = $Date_debut;

        return $this;
    }

    public function getDateFin(): ?\DateTimeInterface
    {
        return $this->date_fin;
    }

    public function setDateFin(\DateTimeInterface $date_fin): self
    {
        $this->date_fin = $date_fin;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getLieu(): ?string
    {
        return $this->lieu;
    }

    public function setLieu(string $lieu): self
    {
        $this->lieu = $lieu;

        return $this;
    }

    public function getPrix(): ?int
    {
        return $this->prix;
    }

    public function setPrix(int $prix): self
    {
        $this->prix = $prix;

        return $this;
    }

    public function getNbPlaces(): ?int
    {
        return $this->nb_places;
    }

    public function setNbPlaces(int $nb_places): self
    {
        $this->nb_places = $nb_places;

        return $this;
    }

    public function getType(): ?string
    {
        return $this->type;
    }

    public function setType(string $type): self
    {
        $this->type = $type;

        return $this;
    }




    /**
     * @var
     * @ORM\ManyToMany( targetEntity="App\Entity\User",inversedBy="events")
     * @ORM\JoinTable(name="user_event",
     *     joinColumns={
     *          @ORM\JoinColumn(name="event_id", referencedColumnName="id")
     *     },
     *     inverseJoinColumns={
     *          @ORM\JoinColumn(name="user_id", referencedColumnName="id")
     *     }
     * )
     */
    private $users;


    /**
     * @param mixed $users
     */
    public function setUsers($users)
    {
        $this->users = $users;
    }

    public function __construct()
    {
        $this->users= new ArrayCollection();
        $this->events= new ArrayCollection();

    }
    /**
     * @return ArrayCollection|users[]
     *
     */
    public function getUsers()
    {
        return $this->users;
    }




    public function addUsers( $user)
    {
        if ($this->users->contains($user)) {
            return;
        }
        $this->users->add($user);
        $user->addEvent($this);
    }



    public function removeUsers( $user)
    {
        if (!$this->users->contains($user)) {
            return;
        }

        $this->users->removeElement($user);
        $user->removeevent($this);
    }


    public function __toString()
    {

        return $this->Nom;
    }

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="events")
     */
    private $creator;



    /**
     * @return mixed
     */
    public function getCreator()
    {
        return $this->creator;
    }

    /**
     * @param mixed $creator
     */
    public function setCreator($creator)
    {
        $this->creator = $creator;
    }

    /**
     * @return int
     */


    public function getWebPath(){

        return null===$this->nom_image ? null :$this->getUploadDir().'/'.$this->nom_image;
    }
    protected function getUploadRootDir()
    {

        return (dirname(__FILE__). '/../../../assets/' . $this->getUploadDir());
    }
    protected function getUploadDir(){

        return 'images';
    }


    public function uploadProfilePicture(){
        $this->file->move($this->getUploadRootDir(),$this->file->getClientOriginalName());
        $this->nom_image=$this->file->getClientOriginalName();
        $this->file=null;
    }
}
