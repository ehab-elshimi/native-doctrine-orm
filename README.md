# Doctrine Category–Product Mini Framework

A **handcrafted PHP project** demonstrating modern backend architecture using **native PHP 8+, Doctrine ORM, and custom dependency injection**, built **without Laravel or Symfony shortcuts**.

This project is designed to **showcase native backend engineering skills** — exactly what European recruiters and tech leads look for in backend developers.

---

## ✅ Project Highlights

* **Native PHP & Doctrine Mastery**

  * Full control of entity mapping using **Doctrine Attributes**
  * One-to-Many and Many-to-One relationships without relying on a framework
  * Repository pattern and advanced QueryBuilder usage
  * Filtering using Doctrine **Criteria**, **Collection filters**, and **custom queries**

* **Custom Dependency Injection Container**

  * Handmade container showing understanding of DI principles
  * Lazy-loaded services and controllers
  * Clean separation between business logic and routing

* **Services & Controllers Layered Architecture**

  * `CategoryService` and `ProductService` contain business logic
  * Controllers handle only requests and output, keeping code modular

* **Caching with Symfony Cache Adapter**

  * Caching categories and products for performance
  * Debug routes to inspect cached data

* **Multiple Filtering Techniques for Products**

  1. **QueryBuilder** – database-level filtering
  2. **Collection filter** – in-memory PHP filtering
  3. **Doctrine Criteria** – reusable and expressive filters

* **Clean, Minimal, Readable Code**

  * No boilerplate from Laravel/Symfony
  * Focus on **real-world backend architecture and native PHP skills**

---

## 📁 Project Structure

```
src/
 └── App/
      ├── Controllers/
      │     ├── CategoryController.php
      │     └── ProductController.php
      ├── Services/
      │     ├── CategoryService.php
      │     └── ProductService.php
      ├── Entity/
      │     ├── Category.php
      │     └── Product.php
      ├── Repository/
      │     ├── CategoryRepository.php
      │     └── ProductRepository.php
      └── Core/
            └── Container.php   ← handcrafted DI container
config/
 └── bootstrap.php             ← container setup + services registration
public/
 └── index.php                 ← front controller with routing
```

---

## 🛠 Requirements

* **PHP ≥ 8.1** (native features & attributes)
* Composer
* MySQL / MariaDB
* Doctrine ORM (installed via Composer)

---

## 📦 Installation

1. Clone repository:

```bash
git clone https://github.com/your-username/doctrine-mini-framework.git
cd doctrine-mini-framework
```

2. Install dependencies:

```bash
composer install
```

3. Configure your database in `config/bootstrap.php`.

4. Generate database tables:

```bash
vendor/bin/doctrine orm:schema-tool:update --force
```

5. Start local server:

```bash
php -S localhost:8000 -t public
```

Open in browser: `http://localhost:8000`

---

## 🧭 Available Routes

### Categories

| Route                                   | Description                             |
| --------------------------------------- | --------------------------------------- |
| `?uri=categories`                       | List all categories                     |
| `?uri=category-detail&name=Electronics` | Show a single category                  |
| `?uri=category-prices1`                 | Filter products (QueryBuilder)          |
| `?uri=category-prices2`                 | Filter products (PHP Collection filter) |
| `?uri=category-prices3`                 | Filter products (Doctrine Criteria)     |
| `?uri=cache-debug`                      | Inspect cached category cache           |

### Products

| Route                                     | Description                         |
| ----------------------------------------- | ----------------------------------- |
| `?uri=products`                           | List all products                   |
| `?uri=product-detail&product_id=1`        | Show single product                 |
| `?uri=products-by-category&category_id=3` | Products in a category              |
| `?uri=products-by-price&min=5000`         | Products above minimum price        |
| `?uri=products-with-category`             | Join products with their categories |

---

## 🎯 Why This Project Matters for European Recruiters

* **Native PHP & Doctrine skills** – you demonstrate mastery of backend without frameworks
* **Clean architecture** – services, controllers, repositories, and DI container
* **Scalable & maintainable** – shows understanding of real enterprise projects
* **Problem-solving mindset** – multiple filtering solutions, caching, and entity relationships

---

## 🧠 Learning Purpose

* Deep understanding of **Doctrine ORM internals** (Unit of Work, Proxies, Repositories)
* Building **MVC from scratch** with native PHP
* Designing **realistic backend systems** without relying on frameworks
* Preparing for **European backend roles**, especially in FinTech, SaaS, or companies valuing native coding skills

---

## 🤝 Contributions

* Extend entities and relationships
* Add custom router or event system
* Implement autowiring for the container
* Add tests and CI/CD pipelines

---

This project is perfect for showcasing **native PHP, Doctrine ORM, and clean backend architecture skills** to European recruiters and companies.
