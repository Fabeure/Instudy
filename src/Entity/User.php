<?php

namespace App\Entity;

use App\Repository\UserRepository;
use DateTime;
use DateTimeImmutable;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Vich\UploaderBundle\Mapping\Annotation as Vich;
#[ORM\Entity(repositoryClass: UserRepository::class)]
#[UniqueEntity(fields: ['email'], message: 'There is already an account with this email')]
#[UniqueEntity(fields: ['username'], message: 'There is already an account with this username')]
#[Vich\Uploadable]
class User implements UserInterface, PasswordAuthenticatedUserInterface, \Serializable
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 180, unique: true)]
    private ?string $email = null;

    #[ORM\Column]
    private array $roles = [];

    /**
     * @var string The hashed password
     */
    #[ORM\Column]
    private ?string $password = null;

    #[ORM\Column]
    private ?string $username = null;


    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $personalEmail = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $name = null;



    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $bio = null;

    #[ORM\Column(nullable: true)]
    private ?int $phone = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $surname = null;

    #[ORM\OneToMany(targetEntity:"Participant", mappedBy:"user")]
    private $participants;

    #[ORM\OneToMany(targetEntity:"Message", mappedBy:"user")]
    private $messages;

    #[ORM\Column(nullable: true)]
    private ?string $imageName = null;
    #[Vich\UploadableField(mapping:"profile_pictures", fileNameProperty:'imageName')]
    private ?File $imageFile = null;

    #[ORM\OneToMany(targetEntity: 'Cours', mappedBy: 'teacher')]
    private Collection $cours;

    #[ORM\OneToMany(targetEntity: 'Homework', mappedBy: 'student')]
    private Collection $homework;

    #[ORM\OneToMany(targetEntity: 'Question', mappedBy: 'sender')]
    private Collection $questions;

    #[ORM\OneToMany(targetEntity: 'Notification', mappedBy: 'notif_sender' )]
    private Collection $sent_notifications;

    #[ORM\OneToMany(targetEntity: 'Notification', mappedBy: 'notif_recipient' )]
    private Collection $received_notifications;


    #[ORM\Column(nullable: true)]
    private ?DateTimeImmutable $updatedAt= null;
    public function __construct()
    {
        $this->participants = new ArrayCollection();
        $this->messages = new ArrayCollection();
        $this->cours = new ArrayCollection();
        $this->homework = new ArrayCollection();
        $this->questions = new ArrayCollection();
        $this->sent_notifications = new ArrayCollection();
        $this->received_notifications = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }
    public function getQuestions(): Collection{
        return $this->questions;
    }

    public function setQuestions(Collection $questions): self{
        $this->questions = $questions;
        return $this;
    }
    public function getHomework(): Collection
    {
        return $this->homework;

    }

    public function setHomework(Collection $homework): self
    {
        $this->homework = $homework;
        return $this;
    }
    public function getCours(): Collection
    {
        return $this->cours;

    }

    public function setCours(Collection $cours): self
    {
        $this->cours = $cours;
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
    public function getUsername(): ?string
    {
        return $this->username;
    }

    public function setUsername(string $username): self
    {
        $this->username = $username;
        return $this;
    }



    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUserIdentifier(): string
    {
        return (string) $this->email;
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // 
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see PasswordAuthenticatedUserInterface
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials()
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    public function getPersonalEmail(): ?string
    {
        return $this->personalEmail;
    }

    public function setPersonalEmail(?string $personalEmail): self
    {
        $this->personalEmail = $personalEmail;

        return $this;
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



    public function getBio(): ?string
    {
        return $this->bio;
    }

    public function setBio(?string $bio): self
    {
        $this->bio = $bio;

        return $this;
    }

    public function getPhone(): ?int
    {
        return $this->phone;
    }

    public function setPhone(?int $phone): self
    {
        $this->phone = $phone;

        return $this;
    }

    public function getSurname(): ?string
    {
        return $this->surname;
    }

    public function setSurname(?string $surname): self
    {
        $this->surname = $surname;

        return $this;
    }
    /**
     * Check if all attributes are not null
     *
     * @return bool
     */
    public function allNotNull(): bool
    {
        $properties = get_object_vars($this);
        foreach ($properties as $propertyValue) {
            if (null === $propertyValue) {
                return false;
            }
        }
        return true;
    }

    /**
     * @return Collection<int, Participant>
     */
    public function getParticipants(): Collection
    {
        return $this->participants;
    }

    public function addParticipant(Participant $participant): self
    {
        if (!$this->participants->contains($participant)) {
            $this->participants->add($participant);
            $participant->setUser($this);
        }

        return $this;
    }

    public function removeParticipant(Participant $participant): self
    {
        if ($this->participants->removeElement($participant)) {
            // set the owning side to null (unless already changed)
            if ($participant->getUser() === $this) {
                $participant->setUser(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Message>
     */
    public function getMessages(): Collection
    {
        return $this->messages;
    }

    public function addMessage(Message $message): self
    {
        if (!$this->messages->contains($message)) {
            $this->messages->add($message);
            $message->setUser($this);
        }

        return $this;
    }

    public function removeMessage(Message $message): self
    {
        if ($this->messages->removeElement($message)) {
            // set the owning side to null (unless already changed)
            if ($message->getUser() === $this) {
                $message->setUser(null);
            }
        }

        return $this;
    }
    public function setImageFile(?File $imageFile = null): void
    {
        $this->imageFile = $imageFile;

        if (null !== $imageFile) {
            $this->updatedAt = new \DateTimeImmutable();
        }
    }

    public function getImageFile(): ?File
    {
        return $this->imageFile;
    }

    public function setImageName(?string $imageName): void
    {
        $this->imageName = $imageName;
    }

    public function getImageName(): ?string
    {
        return $this->imageName;
    }

    public function serialize()
    {
        // TODO: Implement serialize() method.
    }

    public function unserialize(string $data)
    {
        // TODO: Implement unserialize() method.
    }

    public function __serialize(): array
    {
        return [
            'id' => $this->id,
            'email' => $this->email,
            'password' => $this->password
            //......
        ];
    }

    public function __unserialize(array $data): void
    {
        $this->id = $data['id'];
        $this->email = $data['email'];
        $this->password = $data['password'];
        // .....
    }

    public function getUpdatedAt(): ?\DateTimeImmutable
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(?\DateTimeImmutable $updatedAt): self
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    public function addCour(Cours $cour): self
    {
        if (!$this->cours->contains($cour)) {
            $this->cours->add($cour);
            $cour->setTeacher($this);
        }

        return $this;
    }

    public function removeCour(Cours $cour): self
    {
        if ($this->cours->removeElement($cour)) {
            // set the owning side to null (unless already changed)
            if ($cour->getTeacher() === $this) {
                $cour->setTeacher(null);
            }
        }

        return $this;
    }

    public function addHomework(Homework $homework): self
    {
        if (!$this->homework->contains($homework)) {
            $this->homework->add($homework);
            $homework->setStudent($this);
        }

        return $this;
    }

    public function removeHomework(Homework $homework): self
    {
        if ($this->homework->removeElement($homework)) {
            // set the owning side to null (unless already changed)
            if ($homework->getStudent() === $this) {
                $homework->setStudent(null);
            }
        }

        return $this;
    }

    public function addQuestion(Question $question): self
    {
        if (!$this->questions->contains($question)) {
            $this->questions->add($question);
            $question->setSender($this);
        }

        return $this;
    }

    public function removeQuestion(Question $question): self
    {
        if ($this->questions->removeElement($question)) {
            // set the owning side to null (unless already changed)
            if ($question->getSender() === $this) {
                $question->setSender(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Notification>
     */
    public function getSentNotifications(): Collection
    {
        return $this->sent_notifications;
    }

    public function addSentNotification(Notification $sentNotification): self
    {
        if (!$this->sent_notifications->contains($sentNotification)) {
            $this->sent_notifications->add($sentNotification);
            $sentNotification->setNotifSender($this);
        }

        return $this;
    }

    public function removeSentNotification(Notification $sentNotification): self
    {
        if ($this->sent_notifications->removeElement($sentNotification)) {
            // set the owning side to null (unless already changed)
            if ($sentNotification->getNotifSender() === $this) {
                $sentNotification->setNotifSender(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Notification>
     */
    public function getReceivedNotifications(): Collection
    {
        return $this->received_notifications;
    }

    public function addReceivedNotification(Notification $receivedNotification): self
    {
        if (!$this->received_notifications->contains($receivedNotification)) {
            $this->received_notifications->add($receivedNotification);
            $receivedNotification->setNotifRecipient($this);
        }

        return $this;
    }

    public function removeReceivedNotification(Notification $receivedNotification): self
    {
        if ($this->received_notifications->removeElement($receivedNotification)) {
            // set the owning side to null (unless already changed)
            if ($receivedNotification->getNotifRecipient() === $this) {
                $receivedNotification->setNotifRecipient(null);
            }
        }

        return $this;
    }
}
