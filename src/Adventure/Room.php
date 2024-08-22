<?php

namespace App\Adventure;

class Room
{
    protected string $name;
    protected string $image;
    protected string $description;
    protected bool $explored = false;
    protected bool $visited = false;
    protected bool $locked = false;

    /** @var array<int, Item> $items */
    protected array $items;

    protected bool $requiresItem = false;
    protected bool $isLastRoom = false;

    /** @var Item|null $requiredItem; */
    protected mixed $requiredItem = null;
    /** @var array<string, null|Room> */
    protected array $exits = ["North" => null, "East" => null, "South" => null, "West" => null, "Back" => null];

    /**
     * @param array<int, Item> $items
     */
    public function __construct(string $name, string $image, string $description, array|null $items = null, true|null $requiresItem = null, Item|null $requiredItem = null)
    {
        $this->name = $name;
        $this->image = $image;
        $this->description = $description;
        $this->items = [];

        if ($items != null) {
            $this->items = $items;
        }

        if ($requiresItem != null) {
            $this->requiresItem = $requiresItem;
            $this->requiredItem = $requiredItem;
            $this->locked = true;
        }
    }

    /**
     * Returns weather or not this is the last room.
     *  @return bool */
    public function isLast(): bool
    {
        return $this->isLastRoom;
    }

    /**
     * Sets the room state to reflect it being the last room in the game
     */
    public function setLastRoom(): void
    {
        $this->isLastRoom = true;
    }

    public function setItemLocations(): void
    {
        $items = $this->items;
        $location = $this->name;
        foreach ($items as $item) {
            $item->setLocation($location);
        }
    }

    public function addConnectingRoom(Room $room, string $direction): void
    {
        $this->exits[$direction] = $room;
    }

    /** @return array<int, Item> $items */
    public function getItem(string $name): array
    {
        $specificItem = [];
        $num = 0;
        //$num = count($this->items);

        foreach ($this->items as $item) {
            if ($item->getName() === $name) {
                $removedItem = array_splice($this->items, $num, 1);
                array_push($specificItem, $removedItem[0]);
            }
            $num += 1;
        }

        //for ($i = $num; $i <= $num; $i++) {
        //    if ($this->items[$i - 1]->getName() === $name) {
        //        $item = array_splice($this->items, $i, 1);
        //        array_push($items, $item[0]);
        //    }
        //}

        return $specificItem;
    }

    /** @return array<int, string|null> */
    public function getDescription(): ?array
    {
        return [$this->description];
    }

    /** @return array<int, string|null> */
    public function expandedDescription(): ?array
    {
        if (count($this->items) > 0) {
            $desc = $this->description;
            $item = $this->items[0]->getName();
            $expandedText = "$desc <br>You notice a: $item";

            return [$expandedText];
        }
        return null;
    }

    /** @return array<int, string> */
    public function exploreDescription(): array
    {
        $exits = $this->getAvailableConnectionsAsString();
        $expText = "You search the surrounding area with fervour and with great care, you come up with the following: ";

        if (count($this->items) > 0) {
            $item = $this->items[0]->getName();
            $expandedText = "You notice a: $item";

            return [$expText, $expandedText, $exits];
        }
        return [$expText, $exits];
    }

    /** @return array<int, string> */
    public function visitedDescription(): array
    {
        $exits = $this->getAvailableConnectionsAsString();
        $expText = "You have been here before, you have explored the area and there is nothing new to find...";

        if (count($this->items) > 0) {
            $item = $this->items[0]->getName();
            $expandedText = "The following still remains: $item";

            return [$expText, $expandedText, $exits];
        }
        return [$expText, $exits];
    }

    public function setExplored(): void
    {
        $this->explored = true;
    }

    /** @return bool */
    public function isExplored(): bool
    {
        return $this->explored;
    }

    /** @return string */
    public function getImage(): string
    {
        return $this->image;
    }

    /** @return string */
    public function getName(): string
    {
        return $this->name;
    }

    /** @return bool */
    public function isItemRequired(): bool
    {
        return $this->requiresItem;
    }

    /** @return Item|null */
    public function getRequiredItem(): ?Item
    {
        return $this->requiredItem;
    }

    /** @return array<int, Item|null> */
    public function getItems(): array
    {
        return $this->items;
    }

    /** @param array<int, Item> $items */
    public function setItems(array $items): void
    {
        $this->items = $items;
    }

    /** @return array<string, null|Room> */
    public function getExits(): array
    {
        return $this->exits;
    }

    public function isVisited(): bool
    {
        return $this->visited;
    }

    public function setVisited(): void
    {
        $this->visited = true;
    }

    public function unlockRoom(): void
    {
        $this->locked = false;
    }

    public function isLocked(): bool
    {
        return $this->locked;
    }

    /**
     * Method that returns a descriptive string of what exits can be spotted from the room
     *
     * @return string */
    public function getAvailableConnectionsAsString(): string
    {
        $connections = "You spot the following exits:";
        foreach ($this->exits as $key => $value) {
            if ($value != null) {
                $connections .= " $key.";
            }
        }
        return $connections;
    }

    /**
     * Method that returns an array of strings corresponding to what directions has a room connected to it.
     *
     * @return array<int, string|null>
     */
    public function getConnectionsAsStringArray(): array
    {
        $connections = [];
        foreach ($this->exits as $key => $value) {
            if ($value != null) {
                array_push($connections, $key);
            }
        }
        return $connections;
    }

    /** @return null|string */
    public function getItemsAsString(): ?string
    {
        if (!empty($this->items)) {
            $itemDescript = "You notice the following items:";

            foreach ($this->items as $item) {
                $name = $item->getName();
                $itemDescript .= " $name";
            }
            return $itemDescript;
        }
        return null;
    }
}
