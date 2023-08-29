<?php

namespace App\Entity;

use App\Repository\GamesRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: GamesRepository::class)]
class Games
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank]
    private ?string $name = null;

    #[ORM\Column(options: ['default' => 0])]
    #[Assert\NotBlank]
    #[Assert\Range(min: 0, max: 10, notInRangeMessage: 'Please enter a Number between {{ min }} and {{ max }}')]
    #[Assert\DivisibleBy(1, message: 'Please enter an integer')]
    private ?int $score = 0;

    #[ORM\ManyToOne(inversedBy: 'games')]
    #[ORM\JoinColumn(nullable: false)]
    private ?GameList $gameList = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }

    public function getScore(): ?int
    {
        return $this->score;
    }

    public function setScore(int $score): static
    {
        $this->score = $score;

        return $this;
    }

    public function getGameList(): ?GameList
    {
        return $this->gameList;
    }

    public function setGameList(?GameList $gameList): static
    {
        $this->gameList = $gameList;

        return $this;
    }
}
