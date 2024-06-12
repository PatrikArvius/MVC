<?php

namespace App\Dice;

use PHPUnit\Framework\TestCase;

/**
 * Test cases for class DiceHand.
 */
class DiceHandTest extends TestCase
{
    /**
     * Stub the dices to assure the value can be asserted.
     */
    public function testAddStubbedDices(): void
    {
        // Create a stub for the Dice class.
        $stub = $this->createMock(Dice::class);

        // Configure the stub.
        $stub->method('getValue')
            ->willReturn(6);

        $dicehand = new DiceHand();
        $dicehand->add(clone $stub);
        $dicehand->add(clone $stub);
        $values = $dicehand->getValues();
        $res = 0;

        foreach ($values as $val) {
            $res += $val;
        }

        $this->assertEquals(12, $res);
    }

    /**
     * Test that correct number of dice gets returned
     */
    public function testGetNumberOfDices(): void
    {
        $dicehand = new DiceHand();
        $dicehand->add(new Dice());
        $dicehand->add(new DiceGraphic());

        $res = $dicehand->getNumberDices();
        $this->assertEquals(2, $res);
    }

    /**
     * Test that dice gets rolled
     */
    public function testRollDices(): void
    {
        $dicehand = new DiceHand();
        $dicehand->add(new Dice());
        $res = $dicehand->getValues();
        $dicehand->roll();
        $res2 = $dicehand->getValues();

        $this->assertEquals(null, $res[0]);
        $this->assertGreaterThan(0, $res2);
        $this->lessThanOrEqual(6, $res2);
    }

    /**
     * Test that dicehand returns correct string values
     */
    public function testGetStringValues(): void
    {
        // Create a stub for the Dice class.
        $stub = $this->createMock(Dice::class);

        // Configure the stub.
        $stub->method('getAsString')
            ->willReturn("6");

        $dicehand = new DiceHand();
        $dicehand->add(clone $stub);
        $res = $dicehand->getString();
        $this->assertEquals("6", $res[0]);
    }
}
