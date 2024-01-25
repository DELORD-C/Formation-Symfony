<?php

namespace App\Entity;

use App\Entity\Post\Comment as PostComment;
use App\Entity\Review\Comment as ReviewComment;
use App\Entity\Post\Comment\Like;
use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: UserRepository::class)]
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 180, unique: true)]
    #[Assert\Email()]
    private ?string $email = null;

    #[ORM\Column]
    private array $roles = [];

    /**
     * @var string The hashed password
     */
    #[ORM\Column]
    private ?string $password = null;

//    #[Assert\PasswordStrength(
//        minScore: Assert\PasswordStrength::STRENGTH_MEDIUM,
//        message: "Your password must at least contain an uppercase letter, a lowercase letter, a number and 8 characters"
//    )]
    private $rawPassword;

    #[ORM\OneToMany(mappedBy: 'user', targetEntity: PostComment::class, orphanRemoval: true)]
    private Collection $postComments;

    #[ORM\OneToMany(mappedBy: 'user', targetEntity: ReviewComment::class, orphanRemoval: true)]
    private Collection $reviewComments;

    #[ORM\OneToMany(mappedBy: 'user', targetEntity: Post::class)]
    private Collection $post;

    #[ORM\OneToMany(mappedBy: 'user', targetEntity: Review::class)]
    private Collection $review;

    #[ORM\OneToMany(mappedBy: 'user', targetEntity: Like::class)]
    private Collection $postCommentLikes;

    #[ORM\OneToMany(mappedBy: 'user', targetEntity: ReviewComment\Like::class)]
    private Collection $reviewCommentLikes;

    public function __construct()
    {
        $this->postComments = new ArrayCollection();
        $this->reviewComments = new ArrayCollection();
        $this->post = new ArrayCollection();
        $this->review = new ArrayCollection();
        $this->postCommentLikes = new ArrayCollection();
        $this->reviewCommentLikes = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): static
    {
        $this->email = $email;

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
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    public function setRoles(array $roles): static
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

    public function setPassword(string $password): static
    {
        $this->password = $password;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials(): void
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    public function getRawPassword(): string
    {
        return $this->rawPassword;
    }

    public function setRawPassword(string $rawPassword): static
    {
        $this->rawPassword = $rawPassword;

        return $this;
    }

    /**
     * @return Collection<int, PostComment>
     */
    public function getPostComments(): Collection
    {
        return $this->postComments;
    }

    public function addPostComment(PostComment $postComment): static
    {
        if (!$this->postComments->contains($postComment)) {
            $this->postComments->add($postComment);
            $postComment->setUser($this);
        }

        return $this;
    }

    public function removePostComment(PostComment $postComment): static
    {
        if ($this->postComments->removeElement($postComment)) {
            // set the owning side to null (unless already changed)
            if ($postComment->getUser() === $this) {
                $postComment->setUser(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, ReviewComment>
     */
    public function getReviewComments(): Collection
    {
        return $this->reviewComments;
    }

    public function addReviewComment(ReviewComment $reviewComment): static
    {
        if (!$this->reviewComments->contains($reviewComment)) {
            $this->reviewComments->add($reviewComment);
            $reviewComment->setUser($this);
        }

        return $this;
    }

    public function removeReviewComment(ReviewComment $reviewComment): static
    {
        if ($this->reviewComments->removeElement($reviewComment)) {
            // set the owning side to null (unless already changed)
            if ($reviewComment->getUser() === $this) {
                $reviewComment->setUser(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Post>
     */
    public function getPost(): Collection
    {
        return $this->post;
    }

    public function addPost(Post $post): static
    {
        if (!$this->post->contains($post)) {
            $this->post->add($post);
            $post->setUser($this);
        }

        return $this;
    }

    public function removePost(Post $post): static
    {
        if ($this->post->removeElement($post)) {
            // set the owning side to null (unless already changed)
            if ($post->getUser() === $this) {
                $post->setUser(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Review>
     */
    public function getReview(): Collection
    {
        return $this->review;
    }

    public function addReview(Review $review): static
    {
        if (!$this->review->contains($review)) {
            $this->review->add($review);
            $review->setUser($this);
        }

        return $this;
    }

    public function removeReview(Review $review): static
    {
        if ($this->review->removeElement($review)) {
            // set the owning side to null (unless already changed)
            if ($review->getUser() === $this) {
                $review->setUser(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Like>
     */
    public function getPostCommentLikes(): Collection
    {
        return $this->postCommentLikes;
    }

    public function addPostCommentLike(Like $postCommentLike): static
    {
        if (!$this->postCommentLikes->contains($postCommentLike)) {
            $this->postCommentLikes->add($postCommentLike);
            $postCommentLike->setUser($this);
        }

        return $this;
    }

    public function removePostCommentLike(Like $postCommentLike): static
    {
        if ($this->postCommentLikes->removeElement($postCommentLike)) {
            // set the owning side to null (unless already changed)
            if ($postCommentLike->getUser() === $this) {
                $postCommentLike->setUser(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, ReviewComment\Like>
     */
    public function getReviewCommentLikes(): Collection
    {
        return $this->reviewCommentLikes;
    }

    public function addReviewCommentLike(ReviewComment\Like $reviewCommentLike): static
    {
        if (!$this->reviewCommentLikes->contains($reviewCommentLike)) {
            $this->reviewCommentLikes->add($reviewCommentLike);
            $reviewCommentLike->setUser($this);
        }

        return $this;
    }

    public function removeReviewCommentLike(ReviewComment\Like $reviewCommentLike): static
    {
        if ($this->reviewCommentLikes->removeElement($reviewCommentLike)) {
            // set the owning side to null (unless already changed)
            if ($reviewCommentLike->getUser() === $this) {
                $reviewCommentLike->setUser(null);
            }
        }

        return $this;
    }

    public function hasLiked (PostComment | ReviewComment $comment): bool
    {
        if ($comment instanceof PostComment) {
            foreach ($this->postCommentLikes as $like) {
                if ($like->getComment() === $comment) {
                    return true;
                }
            }
        }
        else {
            foreach ($this->reviewCommentLikes as $like) {
                if ($like->getComment() === $comment) {
                    return true;
                }
            }
        }
        return false;
    }
}
