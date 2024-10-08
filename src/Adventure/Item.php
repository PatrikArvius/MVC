<?php

namespace App\Adventure;

class Item
{
    protected string $image;
    protected string $name;
    protected string $description;
    protected int $itemId;
    protected string $location = "";

    public function __construct(string $name, string $image, string $description, int $itemId)
    {
        $this->image = $image;
        $this->name = $name;
        $this->itemId = $itemId;
        $this->description = $description;
    }

    /** @return string|null */
    public function getName(): ?string
    {
        return $this->name;
    }

    /** @return string|null */
    public function getDescription(): ?string
    {
        return $this->description;
    }

    /** @return string|null */
    public function getImage(): ?string
    {
        return $this->image;
    }

    /** @return int */
    public function getId(): int
    {
        return $this->itemId;
    }

    public function setLocation(string $location): void
    {
        $this->location = $location;
    }

    /** @return string */
    public function getLocation(): string
    {
        return $this->location;
    }
}
