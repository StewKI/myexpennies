<?php

declare(strict_types=1);

namespace App\Entity;

use App\Entity\Traits\HasTimestamps;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\GeneratedValue;
use Doctrine\ORM\Mapping\HasLifecycleCallbacks;
use Doctrine\ORM\Mapping\Id;
use Doctrine\ORM\Mapping\ManyToOne;
use Doctrine\ORM\Mapping\OneToMany;
use Doctrine\ORM\Mapping\OneToOne;
use Doctrine\ORM\Mapping\PrePersist;
use Doctrine\ORM\Mapping\PreUpdate;
use Doctrine\ORM\Mapping\Table;
use Doctrine\Persistence\Event\LifecycleEventArgs;

#[Entity, Table(name: "categories")]
#[HasLifecycleCallbacks]
class Category
{
    use HasTimestamps;

    #[Id, Column(options: ["unsigned" => true]), GeneratedValue]
    private int $id;

    #[Column]
    private string $name;

    #[OneToMany(targetEntity: Transaction::class, mappedBy: "category")]
    private Collection $transactions;

    #[ManyToOne(inversedBy: "categories")]
    private User $user;

    public function __construct()
    {
        $this->transactions = new ArrayCollection();
    }

    public function get_id(): int
    {
        return $this->id;
    }

    public function get_name(): string
    {
        return $this->name;
    }

    public function set_name(string $name): Category
    {
        $this->name = $name;

        return $this;
    }

    public function get_created_at(): \DateTime
    {
        return $this->createdAt;
    }

    public function set_created_at(\DateTime $createdAt): Category
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function get_updated_at(): \DateTime
    {
        return $this->updatedAt;
    }

    public function set_updated_at(\DateTime $updatedAt): Category
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    public function get_transactions(): Collection
    {
        return $this->transactions;
    }

    public function add_transaction(Transaction $transaction): Category
    {
        $this->transactions->add($transaction);

        return $this;
    }

    public function get_user(): User
    {
        return $this->user;
    }

    public function set_user(User $user): Category
    {
        $user->add_category($this);

        $this->user = $user;

        return $this;
    }


}