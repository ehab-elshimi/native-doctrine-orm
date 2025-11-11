<?php
namespace App\Entity;

# Call Mapper To Help Doctrine Annotate Table Annotations are MetaDATA
use Doctrine\ORM\Mapping as DataMapper;
# Making Use Of Doctrine Ready Tools
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

use App\Repository\CategoryRepository;

#[DataMapper\Entity(repositoryClass: CategoryRepository::class)]
#[DataMapper\Table(name: "categories")]
# ------------------------------------------- Table => OOP[Class] -----------------------------------------------------
class Category
{
    # --------------------------------------- My Class[Attributes] -----------------------------------------------------
    #[DataMapper\Id]
    #[DataMapper\GeneratedValue]
    #[DataMapper\Column(type: "integer")]
    private int $id;

    #[DataMapper\Column(type: "string", length: 100)]
    private string $name;

    #[DataMapper\Column(type: "datetime")]
    private \DateTime $created_at;

    /*
        [-] Make Entity Relationship with Product Table as 1 to m
        [-] Give It Entity Table and relation-table "category"
        [-] make it relation cascade updates in Addition and Delete And This Is Doctrine "Unit Of Work"
        [##] Make $products ArrayCollection to charge it with Category[Products]
    */
    #[DataMapper\OneToMany(mappedBy: "category", targetEntity: Product::class, cascade: ["persist", "remove"])]
    private Collection $products;

    public function __construct()
    {
        # Assign It During Instantiation
        $this->created_at = new \DateTime();
        # Create Products ArrayCollection 
        $this->products = new ArrayCollection();
    }

    # --------------------------------------- My Class[Methods] -----------------------------------------------------
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

    public function getProducts(): Collection
    {
        return $this->products;
    }
    # Fill Relationship-ArrayCollection-Products With New Ones
    public function addProduct(Product $product): self
    {
        if (!$this->products->contains($product)) {
            $this->products[] = $product;
            $product->setCategory($this);
        }
        return $this;
    }
}
