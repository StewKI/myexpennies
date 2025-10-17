<?php

declare(strict_types=1);

namespace App\Entity;

use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\GeneratedValue;
use Doctrine\ORM\Mapping\Id;
use Doctrine\ORM\Mapping\ManyToOne;
use Doctrine\ORM\Mapping\Table;

#[Entity, Table(name: "receipts")]
class Receipt
{
    #[Id, Column(options: ["unsigned" => true]), GeneratedValue]
    private int $id;

    #[Column(name: "file_name")]
    private string $fileName;

    #[Column(name: "created_at")]
    private \DateTime $createdAt;

    #[ManyToOne(inversedBy: 'receipts')]
    private Transaction $transaction;

    public function get_id(): int
    {
        return $this->id;
    }

    public function set_id(int $id): Receipt
    {
        $this->id = $id;

        return $this;
    }

    public function get_file_name(): string
    {
        return $this->fileName;
    }

    public function set_file_name(string $fileName): Receipt
    {
        $this->fileName = $fileName;

        return $this;
    }

    public function get_created_at(): \DateTime
    {
        return $this->createdAt;
    }

    public function set_created_at(\DateTime $createdAt): Receipt
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function get_transaction(): Transaction
    {
        return $this->transaction;
    }

    public function set_transaction(Transaction $transaction): Receipt
    {
        $transaction->add_receipt($this);

        $this->transaction = $transaction;

        return $this;
    }


}