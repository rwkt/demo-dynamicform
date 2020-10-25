<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\PostRepository")
 */
class Post
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     *
     * @Assert\NotBlank(message="Title must not be blank.")
     */
    private $title;

    /**
     * @ORM\Column(type="boolean")
     */
    private $hasRating;

    /**
     * @ORM\Column(type="integer")
     *
     * @Assert\NotBlank(message="Rating must be 0 or more.")
     * @Assert\PositiveOrZero(message="Rating must be 0 or more.")
     */
    private $rating;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;

        return $this;
    }

    public function getHasRating(): ?bool
    {
        return $this->hasRating;
    }

    public function setHasRating(bool $hasRating): self
    {
        $this->hasRating = $hasRating;

        return $this;
    }

    public function getRating(): ?int
    {
        return $this->rating;
    }

    public function setRating(int $rating): self
    {
        $this->rating = $rating;

        return $this;
    }
}
