<?php

namespace App\Card;

use PHPUnit\Framework\TestCase;

/**
 * Test cases for class CardGraphic.
 */
class CardGraphicTest extends TestCase
{
    /**
     * Construct object and verify that the object has the expected
     * properties.
     */
    public function testCreateCard(): void
    {
        $card = new CardGraphic("hearts", 1);
        $this->assertInstanceOf("\App\Card\CardGraphic", $card);
    }

    /**
     * Create a CardGraphic object and test that it returns a string.
     */
    public function testGetAsString(): void {
        $card = new CardGraphic("clubs", 13);
        $res = $card->getAsString();

        $this->assertIsString($res);
    }
}
