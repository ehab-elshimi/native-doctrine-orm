<?php
// File: src/App/Core/Container.php

namespace App\Core;

/**
 * A simple Dependency Injection Container built manually.
 *
 * - Services are registered using closures
 * - Instances are created only when needed (lazy loading)
 * - Once created, instances are reused (per-request singleton)
 */
class Container
{
    # Stores service factories (closures)
    private array $services = [];

    # Stores already created service instances
    private array $instances = [];

    /**
     * Register a service inside the container.
     *
     * Example:
     * $container->set('logger', fn() => new Logger());
     */
    public function set(string $name, callable $factory): void
    {
        $this->services[$name] = $factory;
    }

    /**
     * Get a service instance.
     *
     * This method is intentionally simple and handwritten:
     *  - If the service was already created → return it.
     *  - If not → call the factory and store the result.
     *  - If it doesn't exist → throw an error.
     */
    public function get(string $name)
    {
        # If instance already exists → return it
        if (isset($this->instances[$name])) {
            return $this->instances[$name];
        }

        # If service is not registered → error
        if (!isset($this->services[$name])) {
            throw new \Exception("Service '{$name}' not found in the container.");
        }

        # Create the service using its factory (lazy loading)
        $this->instances[$name] = $this->services[$name]($this);

        return $this->instances[$name];
    }
}
