<?php

namespace App\Dice;

use PHPUnit\Framework\TestCase;

/**
 * Test cases for class Dice.
 */
class DiceTest extends TestCase
{
    /**
     * Construct object and verify that the object has the expected
     * properties, use no arguments.
     */
    public function testCreateDice(): void
    {
        $die = new Dice();
        $this->assertInstanceOf("\App\Dice\Dice", $die);

        $die->roll();
        $res = $die->getValue();
        $this->assertNotNull($res);
    }

    /**
     * Create a Dice object and test that it returns a string.
     */
    public function testGetAsString(): void
    {
        $die = new Dice();
        $die->roll();

        $res = $die->getAsString();
        $this->assertIsString($res);
    }
}
