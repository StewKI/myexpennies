<?php

declare(strict_types=1);

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\GeneratedValue;
use Doctrine\ORM\Mapping\Id;
use Doctrine\ORM\Mapping\ManyToOne;
use Doctrine\ORM\Mapping\OneToMany;
use Doctrine\ORM\Mapping\Table;

#[Entity, Table(name: "transactions")]
class Transaction
{
    #[Id, Column(options: ['unsigned' => true]), GeneratedValue]
    private int $id;

    #[Column]
    private string $description;

    #[Column]
    private \DateTime $date;

    #[Column(name: "amount", type: Types::DECIMAL, precision: 13, scale: 3)]
    private float $amount;

    #[Column(name: "created_at")]
    private \DateTime $createdAt;

    #[Column(name: "updated_at")]
    private \DateTime $updatedAt;

    #[ManyToOne(inversedBy: 'transactions')]
    private User $user;

    #[ManyToOne(inversedBy: 'transactions')]
    private Category $category;

    #[OneToMany(targetEntity: Receipt::class, mappedBy: 'transaction')]
    private Collection $receipts;

    public function __construct()
    {
        $this->receipts = new ArrayCollection();
    }

    public function get_id(): int
    {
        return $this->id;
    }

    public function get_description(): string
    {
        return $this->description;
    }

    public function set_description(string $description): Transaction
    {
        $this->description = $description;

        return $this;
    }

    public function get_date(): \DateTime
    {
        return $this->date;
    }

    public function set_date(\DateTime $date): Transaction
    {
        $this->date = $date;

        return $this;
    }

    public function get_amount(): float
    {
        return $this->amount;
    }

    public function set_amount(float $amount): Transaction
    {
        $this->amount = $amount;

        return $this;
    }

    public function get_created_at(): \DateTime
    {
        return $this->createdAt;
    }

    public function set_created_at(\DateTime $createdAt): Transaction
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function get_updated_at(): \DateTime
    {
        return $this->updatedAt;
    }

    public function set_updated_at(\DateTime $updatedAt): Transaction
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    public function get_user(): User
    {
        return $this->user;
    }

    public function set_user(User $user): Transaction
    {
        $user->add_transaction($this);

        $this->user = $user;

        return $this;
    }

    public function get_category(): Category
    {
        return $this->category;
    }

    public function set_category(Category $category): Transaction
    {
        $category->add_transaction($this);

        $this->category = $category;

        return $this;
    }

    public function get_receipts(): Collection
    {
        return $this->receipts;
    }

    public function add_receipt(Receipt $receipt): Transaction
    {
        $this->receipts->add($receipt);

        return $this;
    }


}