<?php

namespace App\TwentyOne;

use App\Card\Card;
use App\Card\CardGraphic;
use App\Card\CardHand;
use PHPUnit\Framework\TestCase;

/**
 * Test cases for the Player class
 */
class PlayerTest extends TestCase
{
    /**
     * Creates a Player with a Cardhand and makes sure its created
     */
    public function testCreatePlayer(): void
    {
        $hand = new CardHand();
        $player = new Player($hand);

        $this->assertInstanceOf('App\TwentyOne\Player', $player);
    }

    /**
     * Creates an ace Card and a Player with an empty CardHand, checks that player correctly returns values of empty hand, values of hand with one ace
     * and values of hand with two aces (altValue counts first ace as 14)
     */
    public function testGetValuesOfPlayerHand(): void
    {
        $card = new Card("hearts", 1);
        $hand = new CardHand();
        $player = new Player($hand);
        $res = $player->getHandValues();
        $player->add($card);
        $res2 = $player->getHandValues();
        $player->add($card);
        $res3 = $player->getHandValues();

        $this->assertEquals(['value' => 0, 'altValue' => 0], $res);
        $this->assertEquals(['value' => 1, 'altValue' => 14], $res2);
        $this->assertEquals(['value' => 2, 'altValue' => 15], $res3);
    }

    /**
     * Creates Ace Card, PlayerHand, Player and checks that Player correctly returns false on holding an ace when the hand is empty and true when the hand contains atleast one ace
     */
    public function testIfPlayerHoldsAce(): void
    {
        $card = new Card("hearts", 1);
        $hand = new CardHand();
        $player = new Player($hand);
        $res = $player->holdsAce();
        $player->add($card);
        $res2 = $player->holdsAce();

        $this->assertEquals(false, $res);
        $this->assertEquals(true, $res2);
    }

    /**
     * Creates a card, cardgraphic, cardhand and player. Tests that Player correctly returns an array of stringrepresentations of the cards in the playerhand
     * depending on if the hand is empty, contains a standard card or contains a standard card and a graphic card
     */
    public function testGetPlayerHandAsString(): void
    {
        $card = new Card("hearts", 1);
        $cardGraph = new CardGraphic("clubs", 12);
        $hand = new CardHand();
        $player = new Player($hand);
        $res = $player->getString();
        $player->add($card);
        $res2 = $player->getString();
        $player->add($cardGraph);
        $res3 = $player->getString();

        $this->assertEquals([], $res);
        $this->assertEquals(['[hearts1]'], $res2);
        $this->assertEquals(['[hearts1]', '🃝'], $res3);
    }
}
