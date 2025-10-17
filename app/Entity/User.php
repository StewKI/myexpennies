<?php

declare(strict_types=1);

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\GeneratedValue;
use Doctrine\ORM\Mapping\Id;
use Doctrine\ORM\Mapping\OneToMany;
use Doctrine\ORM\Mapping\Table;

#[Entity, Table(name: 'users')]
class User
{
    #[Id, Column(options: ['unsigned' => true]), GeneratedValue]
    private int $id;

    #[Column]
    private string $name;

    #[Column]
    private string $email;

    #[Column]
    private string $password;

    #[Column(name: "created_at")]
    private \DateTime $createdAt;

    #[Column(name: "updated_at")]
    private \DateTime $updatedAt;

    #[OneToMany(targetEntity: Category::class, mappedBy: 'user')]
    private Collection $categories;

    #[OneToMany(targetEntity: Transaction::class, mappedBy: 'user')]
    private Collection $transactions;

    public function __construct()
    {
        $this->categories = new ArrayCollection();
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

    public function set_name(string $name): User
    {
        $this->name = $name;

        return $this;
    }

    public function get_email(): string
    {
        return $this->email;
    }

    public function set_email(string $email): User
    {
        $this->email = $email;

        return $this;
    }

    public function get_password(): string
    {
        return $this->password;
    }

    public function set_password(string $password): User
    {
        $this->password = $password;

        return $this;
    }

    public function get_created_at(): \DateTime
    {
        return $this->createdAt;
    }

    public function set_created_at(\DateTime $createdAt): User
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function get_updated_at(): \DateTime
    {
        return $this->updatedAt;
    }

    public function set_updated_at(\DateTime $updatedAt): User
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    public function get_categories(): Collection
    {
        return $this->categories;
    }

    public function add_category(Category $category): User
    {
        $this->categories->add($category);

        return $this;
    }

    public function get_transactions(): Collection
    {
        return $this->transactions;
    }

    public function add_transaction(Transaction $transaction): User
    {
        $this->transactions->add($transaction);

        return $this;
    }


}