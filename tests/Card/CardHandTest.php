<?php

namespace App\Card;

use PHPUnit\Framework\TestCase;

/**
 * Test cases for class CardHand
 */
class CardHandTest extends TestCase
{
    /**
     * Construct object and verify that the object has the expected
     * properties.
     */
    public function testCreateHand(): void
    {
        $hand = new CardHand();
        $this->assertInstanceOf("\App\Card\CardHand", $hand);
    }

    /**
     * Construct a CardHand and test that adding a card puts a card in the CardHand array
     */
    public function testAddCardToHand(): void
    {
        $hand = new CardHand();
        $card = new Card("hearts", 6);
        $res = $hand->getNumberCards();
        $this->assertEquals($res, 0);

        $hand->add($card);
        $res = $hand->getNumberCards();
        $this->assertEquals($res, 1);
    }

    /**
     * Construct a CardHand and add two different card types, ensure it returns correct number of cards in the hand
     */
    public function testGetNumberOfCards(): void
    {
        $hand = new CardHand();
        $card = new Card("hearts", 6);
        $card2 = new CardGraphic("spades", 12);
        $hand->add($card);
        $hand->add($card2);

        $res = $hand->getNumberCards();
        $this->assertEquals($res, 2);
    }

    /**
     * Construct CardHand, add two different card types, ensure the cardhand returns the correct values of each individual card
     */
    public function testGetCardValues(): void
    {
        $hand = new CardHand();
        $card = new Card("hearts", 6);
        $card2 = new CardGraphic("spades", 12);
        $hand->add($card);
        $hand->add($card2);

        $res = $hand->getValues();
        $this->assertEquals($res, [6, 12]);
    }

    /**
     * Construct two CardHands, add two different cards to the first one and none to the second
     * Make sure each hand returns correct string representation of the different cards, the second cardhand should return an empty array
     */
    public function testGetHandAsString(): void
    {
        $hand = new CardHand();
        $card = new Card("hearts", 6);
        $card2 = new CardGraphic("spades", 12);
        $hand->add($card);
        $hand->add($card2);

        $res = $hand->getString();
        $this->assertEquals($res, ["[hearts6]", '🂭']);

        $hand2 = new CardHand();
        $res2 = $hand2->getString();
        $this->assertEquals($res2, []);
    }
}
