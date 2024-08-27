<?php

namespace App\Adventure;

use App\Adventure\Player;
use App\Adventure\Room;
use App\Adventure\Item;

use PHPUnit\Framework\TestCase;

class PlayerTest extends TestCase
{
    /**
     * Test that the player class gets created correctly
     */
    public function testCreate(): void
    {
        $player = new Player();

        $this->assertInstanceOf('App\Adventure\Player', $player);
    }

    /**
     * Test that the player inventory functions
     */
    public function testInventory(): void
    {
        $lamp = new Item("Lamp", "No Image", "An ordinary lamp", 1);
        $player = new Player([$lamp]);
        $inventory = $player->getInventory();
        $item = $inventory[0];

        $this->assertEquals($item, $lamp);
    }

    /**
     * Test that the player location set and get
     */
    public function testLocation(): void
    {
        $player = new Player();
        $room = new Room("Test Room", "No Image", "A test room");

        $location = $player->getLocation();
        $this->assertNull($location);

        $player->setLocation($room);
        $location = $player->getLocation();
        $this->assertEquals($location, $room);
    }
}
