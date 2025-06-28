<?php

namespace App\DTO;

abstract class BaseDTO
{
    public function FromArray(array $data): static
    {
        $instance = new static;
        foreach ($data as $key => $value) {
            if (property_exists($instance, $key)) {
                $instance->{$key} = $value;
            }
        }

        return $instance;
    }

    public function toArray(): array
    {
        $data = [];
        foreach ($this as $key => $value) {
            if (property_exists($this, $key)) {
                $data[$key] = $value;
            }
        }

        return $data;
    }

    public function toJson(): string
    {
        return json_encode($this->toArray());
    }

    public function __toString(): string
    {
        return $this->toJson();
    }

    public function __get($name)
    {
        if (property_exists($this, $name)) {
            return $this->{$name};
        }
        throw new \Exception("Property {$name} does not exist on ".static::class);
    }

    public function __set($name, $value)
    {
        if (property_exists($this, $name)) {
            $this->{$name} = $value;
        } else {
            throw new \Exception("Property {$name} does not exist on ".static::class);
        }
    }

    public function __isset($name)
    {
        return property_exists($this, $name);
    }

    public function __unset($name)
    {
        if (property_exists($this, $name)) {
            unset($this->{$name});
        } else {
            throw new \Exception("Property {$name} does not exist on ".static::class);
        }
    }

    public function fromModel($model): static
    {
        $instance = new static;
        foreach ($model->getAttributes() as $key => $value) {
            if (property_exists($instance, $key)) {
                $instance->{$key} = $value;
            }
        }

        return $instance;
    }
}
