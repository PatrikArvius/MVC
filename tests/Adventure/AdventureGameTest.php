<?php

namespace App\Adventure;

use App\Adventure\AbandonedTrainStation;
use App\Adventure\AdventureGame;
use App\Adventure\EndRoom;
use App\Adventure\Player;
use App\Adventure\Room;
use App\Adventure\Item;
use App\Adventure\MountainVillage;
use App\Adventure\MountainHouse;
use App\Adventure\TrainTracks;
use PHPUnit\Framework\TestCase;

class AdventureGameTest extends TestCase
{
    /**
     * Test that the adventuregame class gets created correctly
     */
    public function testCreate(): void
    {
        $cheats = "yes";
        $itemKey = new Item("Key", "None", "A key, what door does it unlock?",1);
        $itemLamp = new Item("Lamp", "None", "An old lamp, but it still works", 2);
        $trainStation = new AbandonedTrainStation([$itemLamp]);
        $tracks = new TrainTracks([$itemKey], true, $itemLamp);
        $mountainVillage = new MountainVillage();
        $topHouse = new MountainHouse(null, true, $itemKey);
        $endRoom = new EndRoom();
        $player = new Player();
        $adventureGame = new AdventureGame([$trainStation, $tracks, $mountainVillage, $topHouse], $endRoom, $player, $cheats);

        $this->assertInstanceOf('App\Adventure\AdventureGame', $adventureGame);
    }

    /**
     * Test that the adventuregame class gets created correctly
     */
    public function testCheckGameEnd(): void
    {
        $room = new Room("Test Room", "No image", "A room created for a test");
        $room->setLastRoom();
        $endRoom = new EndRoom();
        $player = new Player();
        $adventureGame = new AdventureGame([$room], $endRoom, $player);
        $isOver = $adventureGame->isGameOver();
        $this->assertEquals(False, $isOver);

        $adventureGame->checkGameEnd();
        $isOver = $adventureGame->isGameOver();
        $this->assertEquals(True, $isOver);
    }

    /**
     * Test that the adventuregame sets cheating and cheat description correctly
     */
    public function testCheating(): void
    {
        $itemKey = new Item("Key", "None", "A key, what door does it unlock?",1);
        $room = new Room("Test Room", "No image", "A room created for a test", [$itemKey], true, $itemKey);
        $endRoom = new EndRoom();
        $player = new Player();
        $adventureGame = new AdventureGame([$room], $endRoom, $player, "cheating");
        $cheating = $adventureGame->isCheating();
        $cheatDescription = $adventureGame->getCheatDescription();
        $expectedDescription = "CHEAT: Go to Test Room, pick up Key, use at Test Room. ";
        $this->assertEquals(True, $cheating);
        $this->assertEquals($expectedDescription, $cheatDescription);
    }

    /**
     * Test that the adventuregame sets and gets current room correctly
     */
    public function testCurrentRoom(): void
    {
        $room = new Room("Test Room", "No image", "A room created for a test");
        $room2 = new Room("Test Room Two", "No image", "A room created for a test");
        $endRoom = new EndRoom();
        $player = new Player();
        $adventureGame = new AdventureGame([$room, $room2], $endRoom, $player, "cheating");
        $currentRoom = $adventureGame->getCurrentRoom();
        $this->assertEquals($currentRoom, $room);
        $adventureGame->setCurrentRoom($room2);
        $currentRoom = $adventureGame->getCurrentRoom();
        $this->assertEquals($currentRoom, $room2);
    }

    /**
     * Test that the adventuregame returns correct descriptions based on room explored status
     */
    public function testGetDescription(): void
    {
        $room = new Room("Test Room", "No image", "A room created for a test");
        $endRoom = new EndRoom();
        $player = new Player();
        $adventureGame = new AdventureGame([$room], $endRoom, $player);

        $description = $adventureGame->getDescription();
        $this->assertEquals($description[0], "A room created for a test");

        $adventureGame->explore();
        $description = $adventureGame->getDescription();
        $this->assertEquals($description[0], "You search the surrounding area with fervour and with great care.");

        $room->setVisited();
        $description = $adventureGame->getDescription();
        $this->assertEquals($description[0], "You have been here before, you have explored the area and there is nothing new to find...");
    }

    /**
     * Test that the adventuregame returns correct available actions
     */
    public function testGetActions(): void
    {
        $itemKey = new Item("Key", "None", "A key, what door does it unlock?",1);
        $room = new Room("Test Room", "No image", "A room created for a test");
        $room2 = new Room("Test Room Two", "No image", "A room created for a test", [$itemKey], true, $itemKey);
        $room3 = new Room("Test Room Two", "No image", "A room created for a test");
        $endRoom = new EndRoom();
        $player = new Player();
        $adventureGame = new AdventureGame([$room, $room2, $room3], $endRoom, $player);

        $actions = $adventureGame->getActions();
        $this->assertEquals($actions, ["Explore"]);

        $adventureGame->explore();
        $actions = $adventureGame->getActions();
        $this->assertNotEmpty($actions);

        $adventureGame->setCurrentRoom($room2);
        $adventureGame->explore();
        $actions = $adventureGame->getActions();
        $this->assertEquals($actions, ["Pick Up Key", "Back"]);
    }

    /**
     * Test that the adventuregame uses actions correctly
     */
    public function testUseAction(): void
    {
        $itemKey = new Item("Key", "None", "A key, what door does it unlock?",1);
        $room = new Room("Test Room", "No image", "A room created for a test");
        $room2 = new Room("Test Room Two", "No image", "A room created for a test", [$itemKey], true, $itemKey);
        $room3 = new Room("Test Room Two", "No image", "A room created for a test");
        $room4 = new Room("Test Room Two", "No image", "A room created for a test");
        $room3->addConnectingRoom($room4, "North");
        $room3->addConnectingRoom($room4, "South");
        $room3->addConnectingRoom($room4, "West");
        $room3->addConnectingRoom($room4, "East");

        $endRoom = new EndRoom();
        $player = new Player();
        $adventureGame = new AdventureGame([$room, $room2, $room3], $endRoom, $player);

        $adventureGame->useAction("Explore");
        $isExplored = $adventureGame->getCurrentRoom()->isExplored();
        $this->assertEquals($isExplored, true);

        $adventureGame->setCurrentRoom($room2);
        $adventureGame->useAction("Back");
        $currentRoom = $adventureGame->getCurrentRoom();
        $isVisited = $room2->isVisited();
        $this->assertEquals($currentRoom, $room);
        $this->assertEquals($isVisited, true);

        $adventureGame->setCurrentRoom($room3);
        $adventureGame->useAction("North");
        $currentRoom = $adventureGame->getCurrentRoom();
        $this->assertEquals($currentRoom, $room4);

        $adventureGame->setCurrentRoom($room3);
        $adventureGame->useAction("South");
        $currentRoom = $adventureGame->getCurrentRoom();
        $this->assertEquals($currentRoom, $room4);

        $adventureGame->setCurrentRoom($room3);
        $adventureGame->useAction("West");
        $currentRoom = $adventureGame->getCurrentRoom();
        $this->assertEquals($currentRoom, $room4);

        $adventureGame->setCurrentRoom($room3);
        $adventureGame->useAction("East");
        $currentRoom = $adventureGame->getCurrentRoom();
        $this->assertEquals($currentRoom, $room4);

        $adventureGame->setCurrentRoom($room2);
        $adventureGame->useAction("Pick Up Key");
        $inventory = $adventureGame->getPlayerInventory();
        $this->assertEquals($inventory[0], $itemKey);
    }

    /**
     * Test that the adventuregame uses an item correctly
     */
    public function testUseItem(): void
    {
        $itemKey = new Item("Key", "None", "A key, what door does it unlock?",1);
        $room = new Room("Test Room", "No image", "A room created for a test");
        $room2 = new Room("Test Room Two", "No image", "A room created for a test", [$itemKey], true, $itemKey);
        $endRoom = new EndRoom();
        $player = new Player();
        $adventureGame = new AdventureGame([$room, $room2], $endRoom, $player);

        $adventureGame->setCurrentRoom($room2);
        $adventureGame->useAction("Pick Up Key");
        $inventory = $adventureGame->getPlayerInventory();
        $this->assertNotEmpty($inventory);

        $adventureGame->useItem("Key");
        $inventory = $adventureGame->getPlayerInventory();
        $isLocked = $adventureGame->getCurrentRoom()->isLocked();
        $this->assertEmpty($inventory);
        $this->assertEquals($isLocked, false);
    }
}
