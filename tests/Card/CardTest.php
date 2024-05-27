<?php

namespace App\Card;

use PHPUnit\Framework\TestCase;

/**
 * Test cases for class Card.
 */
class CardTest extends TestCase
{
    /**
     * Construct object and verify that the object has the expected
     * properties.
     */
    public function testCreateCard(): void
    {
        $card = new Card("hearts", 1);
        $this->assertInstanceOf("\App\Card\Card", $card);
    }

    /**
     * Create a Card object and test that it returns correct value.
     */
    public function testGetCardValue(): void
    {
        $card1 = new Card("hearts", 1);
        $card2 = new Card("spades", 12);

        $card1Res = $card1->getValue();
        $card2Res = $card2->getValue();

        $this->assertEquals($card1Res, 1);
        $this->assertEquals($card2Res, 12);
    }

    /**
     * Create a Card object and test that it returns correct suit as string.
     */
    public function testGetCardSuit(): void
    {
        $card1 = new Card("hearts", 1);
        $card2 = new Card("spades", 12);

        $card1Res = $card1->getSuit();
        $card2Res = $card2->getSuit();

        $this->assertEquals($card1Res, "hearts");
        $this->assertEquals($card2Res, "spades");
    }

    /**
     * Create a Card object and test that it returns a string.
     */
    public function testGetAsString(): void
    {
        $card = new Card("clubs", 13);
        $res = $card->getAsString();

        $this->assertIsString($res);
    }
}
