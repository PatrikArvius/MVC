<?php

namespace App\Adventure;

use App\Adventure\Item;
use PHPUnit\Framework\TestCase;

class RoomTest extends TestCase
{
    /**
     * Test that the room class gets created correctly
     */
    public function testCreate(): void
    {
        $room = new Room("Test Room", "No Image", "A test room");

        $this->assertInstanceOf('App\Adventure\Room', $room);
    }

    /**
     * Test that the room returns correct item
     */
    public function testGetItem(): void
    {
        $lamp = new Item("Lamp", "None", "An ordinary lamp", 1);
        $room = new Room("Test Room", "No Image", "A test room", [$lamp], true, $lamp);

        $item = $room->getItem("Lamp");
        $this->assertEquals($lamp, $item[0]);
    }

    /**
     * Test that the room returns expanded description
     */
    public function testExpandedDescription(): void
    {
        $lamp = new Item("Lamp", "None", "An ordinary lamp", 1);
        $room = new Room("Test Room", "No Image", "A test room", [$lamp], true, $lamp);
        $room2 = new Room("Test room 2", "None", "A second test room");

        $desc = $room->expandedDescription();
        $this->assertNotEmpty($desc);

        $desc2 = $room2->expandedDescription();
        $this->assertNull($desc2);
    }

    /**
     * Test that the room returns correct explore description
     */
    public function testExploreDescription(): void
    {
        $lamp = new Item("Lamp", "None", "An ordinary lamp", 1);
        $room = new Room("Test Room", "No Image", "A test room", [$lamp], true, $lamp);

        $desc = $room->exploreDescription();
        $this->assertEquals($desc[1], "You notice: An ordinary lamp");
    }

    /**
     * Test that the room returns correct visited description
     */
    public function testVisistedDescription(): void
    {
        $lamp = new Item("Lamp", "None", "An ordinary lamp", 1);
        $room = new Room("Test Room", "No Image", "A test room", [$lamp], true, $lamp);

        $desc = $room->visitedDescription();
        $this->assertEquals($desc[1], "The following still remains: An ordinary lamp");
    }

    /**
     * Test that the room returns correct image string
     */
    public function testGetImage(): void
    {
        $lamp = new Item("Lamp", "None", "An ordinary lamp", 1);
        $room = new Room("Test Room", "No Image", "A test room", [$lamp], true, $lamp);

        $image = $room->getImage();
        $this->assertEquals($image, "No Image");
    }

    /**
     * Test that the room returns correct bool for item requirement
     */
    public function testIsItemRequired(): void
    {
        $lamp = new Item("Lamp", "None", "An ordinary lamp", 1);
        $room = new Room("Test Room", "No Image", "A test room", [$lamp], true, $lamp);
        $room2 = new Room("Test room 2", "None", "A second test room");

        $itemRequired = $room->isItemRequired();
        $this->assertEquals($itemRequired, true);

        $itemRequired = $room2->isItemRequired();
        $this->assertEquals($itemRequired, false);
    }

    /**
     * Test that the room returns correct available connections represented as a string
     */
    public function testGetAvailableConnectionsAsString(): void
    {
        $lamp = new Item("Lamp", "None", "An ordinary lamp", 1);
        $room = new Room("Test Room", "No Image", "A test room", [$lamp], true, $lamp);
        $room2 = new Room("Test room 2", "None", "A second test room");
        $room->addConnectingRoom($room2, "North");
        $room2->addConnectingRoom($room, "South");

        $connections = $room->getAvailableConnectionsAsString();
        $this->assertEquals($connections, "You spot the following exits: North (Locked).");

        $connections = $room2->getAvailableConnectionsAsString();
        $this->assertEquals($connections, "You spot the following exits: South.");
    }

    /**
     * Test that the room returns correct string representing the item it holds
     */
    public function testGetItemsAsString(): void
    {
        $lamp = new Item("Lamp", "None", "An ordinary lamp", 1);
        $room = new Room("Test Room", "No Image", "A test room", [$lamp], true, $lamp);
        $room2 = new Room("Test room 2", "None", "A second test room");

        $itemsDesc = $room->getItemsAsString();
        $this->assertEquals($itemsDesc, "You notice the following items: Lamp");

        $itemsDesc = $room2->getItemsAsString();
        $this->assertNull($itemsDesc);
    }
}