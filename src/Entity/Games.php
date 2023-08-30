<?php

namespace App\Entity;

use App\Repository\GamesRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: GamesRepository::class)]
#[ORM\HasLifecycleCallbacks]
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
    private ?int $score = null;

    #[ORM\ManyToOne(inversedBy: 'games')]
    #[ORM\JoinColumn(nullable: false)]
    private ?GameList $gameList = null;

    #[ORM\Column(type: Types::ARRAY)]
    private array $individualScores = [];

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

    #[ORM\PrePersist]
    public function setScoreValue(): void
    {
        $this->score = 0;
    }

    #[ORM\PreUpdate]
    public function updateScore(): void
    {
        // Sets score of a game to the sum of the individual scores each time a game gets updated
        $this->setScore(array_sum($this->getIndividualScores()));
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

    public function getIndividualScores(): array
    {
        return $this->individualScores;
    }

    public function setIndividualScores(array $individualScores): static
    {
        $this->individualScores = $individualScores;

        return $this;
    }
}
