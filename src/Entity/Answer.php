<?php

namespace App\Entity;

use ApiPlatform\Doctrine\Common\Filter\SearchFilterInterface;
use ApiPlatform\Doctrine\Orm\Filter\OrderFilter;
use ApiPlatform\Doctrine\Orm\Filter\SearchFilter;
use ApiPlatform\Metadata\ApiFilter;
use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Link;
use ApiPlatform\Metadata\Post;
use ApiPlatform\Metadata\Put;
use App\Repository\AnswerRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Timestampable\Traits\TimestampableEntity;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\NotNull;
use function Symfony\Component\String\u;

#[ORM\Entity(repositoryClass: AnswerRepository::class)]
#[Get]
#[Put(security: "is_granted('ROLE_ADMIN') or object.getOwner() == user", securityMessage: 'You must be admin or owner of this answer')]
#[GetCollection]
#[Post(security: "is_granted('ROLE_ADMIN')")]

#[ApiResource(
    normalizationContext: ['groups' => 'answer:read'],
    denormalizationContext: ['groups' => 'answer:write-field'],
    paginationClientItemsPerPage: 2
)]

#[ApiFilter(
    SearchFilter::class,
    properties: [
        'username' => SearchFilterInterface::STRATEGY_PARTIAL,
        'question.name' => SearchFilterInterface::STRATEGY_PARTIAL,
        'question.id' => SearchFilterInterface::STRATEGY_EXACT
    ]
)]
#[ApiFilter(
    OrderFilter::class,
    properties: ['votes']
)]
class Answer
{
    use TimestampableEntity;

    public const STATUS_NEEDS_APPROVAL = 'needs_approval';
    public const STATUS_SPAM = 'spam';
    public const STATUS_APPROVED = 'approved';

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['answer:read'])]
    private ?int $id = null;

    #[ORM\Column(type: Types::TEXT)]
    #[Groups(['answer:read', 'answer:write-field'])]
    private ?string $content = null;

    #[ORM\Column]
    #[Groups(['answer:read', 'answer:write-field'])]
    private ?int $votes = 0;

    #[ORM\Column(length: 255)]
    #[Groups(['answer:read', 'answer:write-field'])]
    #[NotNull]
    #[NotBlank]
    private ?string $username = null;

    #[ORM\ManyToOne(inversedBy: 'answers')]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups(['answer:read', 'answer:write-field'])]
    #[NotNull]
    #[NotBlank]
    private ?Question $question = null;

    #[ORM\Column(length: 15)]
    #[Groups(['answer:read'])]
    private ?string $status = self::STATUS_NEEDS_APPROVAL;

    #[ORM\ManyToOne(inversedBy: 'answers')]
    private ?User $owner = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getContent(): ?string
    {
        return $this->content;
    }

    public function setContent(string $content): self
    {
        $this->content = $content;

        return $this;
    }

    public function getVotes(): ?int
    {
        return $this->votes;
    }

    public function setVotes(int $votes): self
    {
        $this->votes = $votes;

        return $this;
    }

    public function upVote(): self
    {
        $this->votes++;

        return $this;
    }
    public function downVote(): self
    {
        $this->votes--;

        return $this;
    }

    public function getVotesString(): string
    {
        $sign = '';
        if ($this->votes > 0) {
            $sign = '+';
        } elseif ($this->votes < 0) {
            $sign = '-';
        }
        $votesValue = abs($this->votes);

        return "$sign $votesValue";
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

    public function getQuestion(): ?Question
    {
        return $this->question;
    }

    public function setQuestion(?Question $question): self
    {
        $this->question = $question;

        return $this;
    }

    public function getStatus(): ?string
    {
        return $this->status;
    }

    public function setStatus(string $status): self
    {
        if (!in_array($status, [self::STATUS_NEEDS_APPROVAL, self::STATUS_SPAM, self::STATUS_APPROVED])) {
            throw new \InvalidArgumentException(sprintf('Invalid status "%s"', $status));
        }

        $this->status = $status;

        return $this;
    }

    public function getQuestionText(): string
    {
        if (!$this->getQuestion()) {
            return '';
        }

        return u((string) $this->getQuestion()->getQuestion())->truncate(80, '...');
    }

    public function getOwner(): ?User
    {
        return $this->owner;
    }

    public function setOwner(?User $user): self
    {
        $this->owner = $user;

        return $this;
    }
}
