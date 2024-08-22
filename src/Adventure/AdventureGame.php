<?php

namespace App\Adventure;

class AdventureGame
{
    /** @var array<int, Room> $rooms */
    protected array $rooms;

    /** @var Room $currentRoom */
    protected object $currentRoom;

    /** @var Room $endRoom */
    protected object $endRoom;

    //** @var Room $previousRoom */
    //protected object $previousRoom;

    //** @var Room $nextRoom */
    //protected object $nextRoom;

    /** @var Player $player */
    protected object $player;

    /** @var bool $cheatMode */
    protected bool $cheatMode = false;

    /** @var bool $gameOver */
    protected bool $gameOver = false;

    /** @var string|null $cheatDesc */
    protected ?string $cheatDesc = null;

    /** @param array<int, Room> $rooms */
    public function __construct(array $rooms, Room $endRoom, Player $player, string|null $cheating = null)
    {
        $this->rooms = $rooms;
        $this->player = $player;
        $this->currentRoom = $rooms[0];
        $this->endRoom = $endRoom;

        if ($cheating !== null) {
            $this->setCheatMode();
            $this->setCheatDescription();
        }

        if (count($rooms) > 1) {
            $this->generateRoomConnections($rooms);
        }
        //$this->nextRoom = $rooms[1];
    }

    /**
     * Checks if the game end conditions are met and sets the current room to end screen room
     *
     */
    public function checkGameEnd(): void
    {
        $rooms = $this->rooms;
        $numRooms = count($rooms);
        $unlockedRooms = 0;

        foreach ($rooms as $room) {
            if (!$room->isLocked()) {
                $unlockedRooms += 1;
            }
        }

        if ($numRooms == $unlockedRooms) {
            $this->currentRoom = $this->endRoom;
            $this->gameOver = true;
        }
    }

    /**
     * Generates connections between all rooms in an array, randomized
     *
     * @param array <int, Room> $rooms */
    public function generateRoomConnections(array $rooms): void
    {
        $directions = ["North", "South", "East", "West"];
        $counterDirections = ["South", "North", "West", "East"];
        $counterDirection = "";
        $num = 0;
        $numRooms = count($rooms);

        foreach ($rooms as $room) {
            $rand = random_int(0, 3);
            while ($counterDirection === $directions[$rand]) {
                $rand = random_int(0, 3);
            }

            if ($num === 0) {
                $room->addConnectingRoom($rooms[$num + 1], $directions[$rand]);
                $counterDirection = $counterDirections[$rand];
                $num += 1;
                continue;
            }

            if ($num === $numRooms - 1) {
                //$room->addConnectingRoom($rooms[$num - 1], $counterDirection);
                $room->addConnectingRoom($rooms[$num - 1], "Back");
                break;
            }

            //$room->addConnectingRoom($rooms[$num - 1], $counterDirection);
            $room->addConnectingRoom($rooms[$num - 1], "Back");
            $room->addConnectingRoom($rooms[$num + 1], $directions[$rand]);
            $counterDirection = $counterDirections[$rand];
            $num += 1;
        }
    }

    /** @return bool */
    public function isCheating(): bool
    {
        return $this->cheatMode;
    }

    public function setCheatMode(): void
    {
        $this->cheatMode = true;
    }

    /** @return bool */
    public function isGameOver(): bool
    {
        return $this->gameOver;
    }

    /** @return Room */
    public function getCurrentRoom(): Room
    {
        return $this->currentRoom;
    }

    /** @param Room $room */
    public function setCurrentRoom(Room $room): void
    {
        $this->currentRoom = $room;
    }

    /** @return bool */
    public function isAtLastRoom(): bool
    {
        return $this->currentRoom->isLast();
    }

    public function explore(): void
    {
        $this->currentRoom->setExplored();
    }

    /** @return array<int, string|null> */
    public function getDescription(): ?array
    {
        if ($this->currentRoom->isExplored() && $this->currentRoom->isVisited()) {
            return $this->currentRoom->visitedDescription();
        }
        if ($this->currentRoom->isExplored()) {
            return $this->currentRoom->exploreDescription();
        }
        return $this->currentRoom->getDescription();
    }

    public function setCheatDescription(): void
    {
        $requiredItemName = null;
        $requiredItemLocation = null;
        $requireingRoom = null;

        foreach ($this->rooms as $room) {
            if ($room->getRequiredItem() != null) {
                $requiredItemName = $room->getRequiredItem()->getName();
                $requireingRoom = $room->getName();
            }
        }

        foreach ($this->rooms as $room) {
            $items = $room->getItems();
            if (!empty($items) && $items[0]) {
                $itemName = $items[0]->getName();

                if ($itemName == $requiredItemName) {
                    $requiredItemLocation = $room->getName();
                }
            }
        }
        $this->cheatDesc = "CHEAT: Go to $requiredItemLocation, pick up $requiredItemName, use it at $requireingRoom";
    }

    /** @return string|null */
    public function getCheatDescription(): ?string
    {
        return $this->cheatDesc;
    }

    /** @return array<int, string|null> */
    public function getActions(): array
    {
        $availableActions = [];

        if (!$this->currentRoom->isExplored()) {
            array_push($availableActions, "Explore");
            return $availableActions;
        }

        if (count($this->currentRoom->getItems()) > 0) {
            array_push($availableActions, "Pick Up");
        }

        $connections = $this->currentRoom->getConnectionsAsStringArray();
        foreach ($connections as $connection) {
            array_push($availableActions, $connection);
        }

        return $availableActions;
    }

    public function useAction(string $action): void
    {
        $exits = $this->currentRoom->getExits();

        switch ($action) {
            case "Explore":
                $this->currentRoom->setExplored();
                break;
            case "Back":
                if ($exits["Back"] != null) {
                    $this->currentRoom->setVisited();
                    $this->currentRoom = $exits["Back"];
                }
                break;
            case "North":
                if ($exits["North"] != null) {
                    $this->currentRoom->setVisited();
                    $this->currentRoom = $exits["North"];
                }
                break;
            case "South":
                if ($exits["South"] != null) {
                    $this->currentRoom->setVisited();
                    $this->currentRoom = $exits["South"];
                }
                break;
            case "West":
                if ($exits["West"] != null) {
                    $this->currentRoom->setVisited();
                    $this->currentRoom = $exits["West"];
                }
                break;
            case "East":
                if ($exits["East"] != null) {
                    $this->currentRoom->setVisited();
                    $this->currentRoom = $exits["East"];
                }
                break;
            case "Pick Up":
                $this->pickUpItem();
                break;
        }
    }

    public function useItem(string $item): void
    {
        $reqItem = $this->currentRoom->getRequiredItem();

        if ($reqItem !== null && $reqItem->getName() == $item) {
            $this->currentRoom->unlockRoom();
            $this->player->deleteItem($item);
        }
    }

    public function pickUpItem(): void
    {
        $items = null;
        $item = null;
        if (count($this->currentRoom->getItems()) > 0) {
            $items = $this->currentRoom->getItems();
            $item = $items[0];
        }

        if ($item) {
            $this->player->addToInventory($item);
            $items = [];
            $this->currentRoom->setItems($items);
        }
    }

    /** @return array<int, Item|null> */
    public function getPlayerInventory(): array
    {
        return $this->player->getInventory();
    }


    //** @return Room|null */
    //public function getNextRoom(): ?Room
    //{
    //    return $this->nextRoom;
    //}

    //** @return Room|null */
    //public function getPreviousRoom(): ?Room
    //{
    //    return $this->previousRoom;
    //}

    //** @return bool */
    //public function isAtLastRoom(): bool
    //{
    //    $numRooms = count($this->rooms);
    //    $current = $this->getCurrentRoom();
    //    $lastRoom = $this->rooms[$numRooms - 1];

    //    if ($current->getId() === $lastRoom->getId()) {
    //        return true;
    //    }
    //    return false;
    //}

    //** @return bool */
    //public function isAtFirstRoom(): bool
    //{
    //    $current = $this->getCurrentRoom();
    //    $firstRoom = $this->rooms[0];

    //    if ($current->getId() === $firstRoom->getId()) {
    //        return true;
    //    }
    //    return false;
    //}

    /* public function moveToNextRoom(): void
    {
        $current = $this->getCurrentRoom();
        $id = $current->getId();

        if (!$this->isAtLastRoom()) {
            $this->setCurrentRoom($this->nextRoom);
            $this->setPreviousRoom($current);
            $this->setNextRoom($this->rooms[$id + 1]);
            }
        $this->gameEnd();
    } */

    /* public function moveToPreviousRoom(): void
    {
        $current = $this->getCurrentRoom();
        $id = $current->getId();

        if (!$this->isAtFirstRoom()) {
            $this->setCurrentRoom($this->previousRoom);
            $this->setNextRoom($current);
            $this->setPreviousRoom($this->rooms[$id - 1]);
        }
    } */

    //** @param Room $room */
    //public function setCurrentRoom(Room $room): void
    //{
    //    $this->currentRoom = $room;
    //}

    //** @param Room $room*/
    //public function setPreviousRoom(Room $room): void
    //{
    //    $this->previousRoom = $room;
    //}

    /* public function setNextRoom(Room $room): void
    {
        if (!$this->isAtLastRoom()) {
            $this->nextRoom = $room;
        }
    } */

}
