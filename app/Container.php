<?php

declare(strict_types=1);

namespace App;

use App\Exceptions\Container\NotFoundException;
use App\Exceptions\Model\ContainerException;
use App\Exceptions\RouteNotFoundException;
use Psr\Container\ContainerInterface;
use ReflectionClass;
use ReflectionException;
use ReflectionNamedType;
use ReflectionParameter;
use ReflectionUnionType;
use TypeError;

class Container implements ContainerInterface
{
    private array $entries = [];

    public function get(string $id)
    {
        if ($this->has($id)) {

            $entry = $this->entries[$id];

            if (is_callable($this->entries[$id])) {

                return $this->entries[$id]($this);
            }
            $id = $entry;
        }
        return $this->resolve($id);
    }


    public function has(string $id): bool
    {
        return isset($this->entries[$id]);
    }

    public function set(string $id, callable|string $value)
    {
        return $this->entries[$id] = $value;
    }

    /**
     * @throws ReflectionException
     * @throws ContainerException
     */
    public function resolve(string $id)
    {
        $reflection = new ReflectionClass($id);

        if (!$reflection->isInstantiable()) {
            throw new ContainerException("Cannot instantiate class: $id");
        }

        $constructor = $reflection->getConstructor();

        if (!$constructor) {
            return new $id();
        }

        $params = $constructor->getParameters();

        if (!$params) {
            return new $id();
        }

        $dependencies = array_map(function (ReflectionParameter $params){
            $name = $params->getName();
            $type = $params->getType();

            if (!$type) {
                throw new ContainerException("Cannot resolve parameter type: $name");
            }

            if ($type instanceof ReflectionUnionType) {
                throw new ContainerException("Cannot resolve parameter type: $name");
            }

            if ($type instanceof ReflectionNamedType && ! $type->isBuiltin()) {
                return $this->get($type->getName());
            }

            throw new TypeError("Cannot resolve parameter type: $name");
        }
        , $params);
        return $reflection->newInstanceArgs($dependencies);
    }
}