<?php

namespace App\Dice;

use PHPUnit\Framework\TestCase;

/**
 * Test cases for class DiceGraphic.
 */
class DiceGraphicTest extends TestCase
{
    /**
     * Construct object and verify that the object has the expected
     * properties, use no arguments.
     */
    public function testCreateDice(): void
    {
        $die = new DiceGraphic();
        $this->assertInstanceOf("\App\Dice\DiceGraphic", $die);

        $die->roll();
        $res = $die->getValue();
        $this->assertNotNull($res);
    }

    /**
     * Create a DiceGraphic object and test that it returns a string.
     */
    public function testGetAsString(): void
    {
        $die = new Dicegraphic();
        $die->roll();

        $res = $die->getAsString();
        $this->assertIsString($res);
    }
}
