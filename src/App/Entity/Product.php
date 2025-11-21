<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\ProductRepository;
use App\Entity\Category;

#[ORM\Entity(repositoryClass: ProductRepository::class)]
#[ORM\Table(name: "products")]
class Product
{
    # ----------------- Class Attributes -----------------
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: "integer")]
    private int $id;

    #[ORM\Column(type: "string", length: 100)]
    private string $name;

    #[ORM\Column(type: "decimal", precision: 10, scale: 2)]
    private float $price;

    /**
     * Relationship: Many Products belong to One Category (Many -> One)
     *
     * - targetEntity: Category::class (the related entity)
     * - inversedBy: the property on Category that holds the product collection
     * - JoinColumn(nullable=false): category is required (cannot be null)
     *
     * Doctrine will automatically link each Product to its Category.
     */
    #[ORM\ManyToOne(targetEntity: Category::class, inversedBy: "products")]
    #[ORM\JoinColumn(nullable: false)]
    private Category $category;

    # ----------------- Getter & Setter Methods -----------------

    public function getId(): int
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;
        return $this;
    }

    public function getPrice(): float
    {
        return $this->price;
    }

    public function setPrice(float $price): self
    {
        $this->price = $price;
        return $this;
    }

    public function getCategory(): Category
    {
        return $this->category;
    }

    public function setCategory(Category $category): self
    {
        $this->category = $category;
        return $this;
    }
}
