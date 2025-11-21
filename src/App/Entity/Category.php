<?php

namespace App\Entity;

# Bring Doctrine Mapping Tools
use Doctrine\ORM\Mapping as ORM;
# Doctrine Collection Tools
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

use App\Repository\CategoryRepository;

#[ORM\Entity(repositoryClass: CategoryRepository::class)]
#[ORM\Table(name: "categories")]
class Category
{
    # ----------------- Class Attributes -----------------
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: "integer")]
    private int $id;

    #[ORM\Column(type: "string", length: 100)]
    private string $name;

    #[ORM\Column(type: "datetime")]
    private \DateTime $created_at;

    /**
     * Relationship: One Category has Many Products (1 -> Many)
     *
     * - mappedBy: the property name on the Product entity that owns the relation
     * - targetEntity: the related entity class (Product)
     * - cascade: persist/remove so Doctrine automatically saves & deletes children
     *
     * Doctrine Unit of Work will:
     * - Insert related products automatically when category is persisted
     * - Remove related products if the category is deleted
     *
     * $products is initialized as an ArrayCollection to hold the related Product objects.
     */
    #[ORM\OneToMany(mappedBy: "category", targetEntity: Product::class, cascade: ["persist", "remove"])]
    private Collection $products;

    public function __construct()
    {
        # Automatically set created_at on creation
        $this->created_at = new \DateTime();

        # Initialize the Products collection
        $this->products = new ArrayCollection();
    }

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

    public function getCreatedAt(): \DateTime
    {
        return $this->created_at;
    }

    /**
     * @return Collection<int, Product>
     */
    public function getProducts(): Collection
    {
        return $this->products;
    }

    /**
     * Add a product to the category.
     * If the product is not already related, we add it and
     * set the category on the Product side to keep relation synced.
     */
    public function addProduct(Product $product): self
    {
        if (!$this->products->contains($product)) {
            $this->products[] = $product;
            $product->setCategory($this);
        }

        return $this;
    }
}
