<?php
namespace App\Entity;

# Call Mapper To Help Doctrine Annotate Table 
use Doctrine\ORM\Mapping as DataMapper;
use App\Repository\ProductRepository;
use App\Entity\Category;

# Assign Entity Class As Repository Class To Make Use Of Built In (Doctrine\ORM\EntityRepository) => Methods and Features
#[DataMapper\Entity(repositoryClass: ProductRepository::class)]
# Convert Table-in-Database To OOP Class (Attributes, Methods) 
# Then Doctrine Will Get It Through Annotations of "DataMapper" and Capsulate it to Give You "Treasure-Of-Repository-Features" 
#[DataMapper\Table(name: "products")]

# ------------------------------------------- Table => OOP[Class] -----------------------------------------------------
class Product
{
    # --------------------------------------- My Class[Attributes] -----------------------------------------------------
    #[DataMapper\Id]
    #[DataMapper\GeneratedValue]
    #[DataMapper\Column(type: "integer")]
    private int $id;

    #[DataMapper\Column(type: "string", length: 100)]
    private string $name;

    #[DataMapper\Column(type: "decimal", precision: 10, scale: 2)]
    private float $price;

    /*
        [-] Make Entity Relationship with Category Table as m to 1
        [-] Give It Entity Table and relation-table "products"
        [-] make it relation restricted to null
    */
    #[DataMapper\ManyToOne(targetEntity: Category::class, inversedBy: "products")]
    #[DataMapper\JoinColumn(nullable: false)]
    # After i annotated my relation i will get instance of Entity I make relation with
    private Category $category;

    # --------------------------------------- My Class[Methods] -----------------------------------------------------
    public function getId(): int { return $this->id; }
    public function getName(): string { return $this->name; }
    public function setName(string $name): self { $this->name = $name; return $this; }
    public function getPrice(): float { return $this->price; }
    public function setPrice(float $price): self { $this->price = $price; return $this; }
    public function getCategory(): Category { return $this->category; }
    public function setCategory(Category $category): self { $this->category = $category; return $this; }
}
