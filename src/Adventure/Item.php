<?php

namespace App\Adventure;

class Item
{
    protected string $image;
    protected string $name;
    protected int $itemId;
    protected string $location = "";

    public function __construct(string $name, string $image, int $itemId)
    {
        $this->image = $image;
        $this->name = $name;
        $this->itemId = $itemId;
    }

    /** @return string|null */
    public function getName(): ?string
    {
        return $this->name;
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

    public function action(): mixed
    {
        return null;
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
