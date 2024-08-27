<?php

namespace App\Adventure;

use App\Adventure\Item;
use PHPUnit\Framework\TestCase;

class ItemTest extends TestCase
{
    /**
     * Test that the Item class gets created correctly
     */
    public function testCreate(): void
    {
        $item = new Item("Donut", "No img", "A tasty donut", 1);

        $this->assertInstanceOf('App\Adventure\Item', $item);
    }

    /**
     * Test that the Item class returns correct image string
     */
    public function testGetImage(): void
    {
        $item = new Item("Donut", "No img", "A tasty donut", 1);
        $image = $item->getImage();

        $this->assertEquals($image, "No img");
    }

     /**
     * Test that the Item class returns correct id
     */
    public function testGetId(): void
    {
        $item = new Item("Donut", "No img", "A tasty donut", 1);
        $itemId = $item->getId();

        $this->assertEquals($itemId, 1);
    }
}
