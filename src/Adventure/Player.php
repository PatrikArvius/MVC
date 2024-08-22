<?php

namespace App\Adventure;

use App\Adventure\Room;

class Player
{
    /** @var array<int, Item> $inventory */
    protected array $inventory;
    /** @var Room $location */
    protected object $location;

    /** @param array<int, Item> $inventory */
    public function __construct(array $inventory = null)
    {
        if ($inventory != null) {
            $this->inventory = $inventory;
        }

        $this->inventory = [];
    }

    /** @return object|null */
    public function getLocation(): ?object
    {
        return $this->location;
    }

    public function setLocation(Room $room): void
    {
        $this->location = $room;
    }

    /** @return array<int, Item|null> */
    public function getInventory(): array
    {
        return $this->inventory;
    }

    public function useItem(Item $item): void
    {
        if (!empty($this->inventory)) {
            foreach ($this->inventory as $invItem) {
                if ($invItem->getName() === $item->getName()) {
                    $item->action();
                }
            }
        }
    }

    public function deleteItem(string $itemName): void
    {
        $num = 0;
        if (!empty($this->inventory)) {
            foreach ($this->inventory as $item) {
                if ($item->getName() === $itemName) {
                    array_splice($this->inventory, $num, 1);
                }
                $num += 1;
            }
        }
    }

    public function addToInventory(Item $item): void
    {
        array_push($this->inventory, $item);
    }
}
