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
        $lamp = new Item("Lamp", "None", "An ordinary lamp", 1);
        $itemKey = new Item("Key", "None", "A key", 2);
        $room = new Room("Test Room", "No Image", "A test room");
        $room2 = new MountainHouse([$lamp], true, $itemKey);
        $room3 = new EndRoom([$lamp], true, $itemKey);

        $this->assertInstanceOf('App\Adventure\Room', $room);
        $this->assertInstanceOf('App\Adventure\Room', $room2);
        $this->assertInstanceOf('App\Adventure\Room', $room3);
    }

    /**
     * Test that the room returns correct item
     */
    public function testGetItem(): void
    {
        $lamp = new Item("Lamp", "None", "An ordinary lamp", 1);
        $room = new MountainVillage([$lamp], true, $lamp);

        $item = $room->getItem("Lamp");
        $this->assertEquals($lamp, $item[0]);
    }

    /**
     * Test that the room returns expanded description
     */
    public function testExpandedDescription(): void
    {
        $lamp = new Item("Lamp", "None", "An ordinary lamp", 1);
        $room = new AbandonedTrainStation([$lamp], true, $lamp);
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
        $itemKey = new Item("Key", "None", "A key, what door does it unlock?",1);
        $lamp = new Item("Lamp", "None", "An ordinary lamp", 1);
        $room2 = new TrainTracks([$itemKey], null, null);
        $room3 = new TrainTracks();
        $room = new Room("Test Room", "No Image", "A test room", [$lamp], true, $lamp);

        $desc = $room->exploreDescription();
        $this->assertEquals($desc[1], "You notice: An ordinary lamp");

        $desc = $room2->exploreDescription();
        $this->assertEquals($desc[1], "You notice: A key, what door does it unlock?");

        $desc = $room3->exploreDescription();
        $this->assertEquals($desc[0], "You fumble around in the darkness and feel something small and cool to the touch. You can spot the silhouette of a locomotive but you require more light in order 
        to operate it.");
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