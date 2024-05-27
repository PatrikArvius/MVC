<?php

namespace App\Card;

use PHPUnit\Framework\TestCase;

/**
* Test cases for DeckOfCards class
*/
class DeckOfCardsTest extends TestCase
{
    /**
    * Construct two deck objects with different card types and ensure they get created
    */
    public function testCreateDeck(): void
    {
        $deck = new DeckOfCards("graphic");
        $deck2 = new DeckOfCards("regular");
        $this->assertInstanceOf('App\Card\DeckOfCards', $deck);
        $this->assertInstanceOf('App\Card\DeckOfCards', $deck2);
    }

    /**
     * Create a deck object and test that it returns the deck as an array of cards
     */
    public function testGetDeck(): void
    {
        $deck = new DeckOfCards("graphic");
        $deckArray = $deck->getDeck();
        $deckCount = count($deckArray);

        $this->assertInstanceOf("App\Card\Card", $deckArray[0]);
        $this->assertIsArray($deckArray);
        $this->assertEquals(52, $deckCount);
    }

    /**
    * Create a deck, test that it correctly returns array of suit and value key/value pair array for each card.
    */
    public function testGetSuitAndValue(): void
    {
        $deck = new DeckOfCards("graphic");
        $res = $deck->getSuitAndValue();

        $this->assertIsArray($res);
        $this->assertEquals(['value' => 1,'suit' => 'spades'], $res[0]);
        $this->assertEquals(['value' => 13,'suit' => 'clubs'], $res[51]);
    }

    /**
    * Create deck, test that it correctly returns base number of cards (52)
    * and that it returns 56 after adding four more aces (one per suit)
    */
    public function testGetNumberOfCardsInDeck(): void
    {
        $deck = new DeckOfCards("graphic");
        $res = $deck->getNumberCards();
        $deck->add("graphic", 1);
        $res2 = $deck->getNumberCards();

        $this->assertEquals(52, $res);
        $this->assertEquals(56, $res2);
    }

    /**
    * Creates a deck, tests that it returns the values of each card in it as an array of integers
    */
    public function testGetCardValuesAsArray(): void
    {
        $deck = new DeckOfCards("graphic");
        $res = $deck->getValues();

        $this->assertIsArray($res);
        $this->assertEquals(1, $res[0]);
        $this->assertEquals(13, $res[51]);
    }

    /**
    * Creates a deck, tests that it returns an array of string representations of each card
    */
    public function testGetCardsInDeckAsArrayOfStrings(): void
    {
        $deck = new DeckOfCards("graphic");
        $res = $deck->getString();

        $this->assertIsArray($res);
        $this->assertEquals('🂡', $res[0]);
        $this->assertEquals('🃞', $res[51]);
    }

    /**
    * Creates a deck, tests that it returns a sorted array of string representations of each card
    */
    public function testGetSortedCardsInDeckAsArrayOfStrings(): void
    {
        $deck = new DeckOfCards("graphic");
        $res = $deck->getStringSorted();

        $this->assertIsArray($res);
        $this->assertEquals('🂡', $res[0]);
        $this->assertEquals('🂢', $res[1]);
        $this->assertEquals('🂣', $res[2]);
    }

    /**
    * Creates a deck, which is not sorted by suit and value by default. Compares a default deck to a shuffled one to make sure they are not the same after it being shuffled
    */
    public function testShuffleOfDeck(): void
    {
        $deck = new DeckOfCards("graphic");
        $res = $deck->getDeck();
        $deck->shuffleDeck();
        $res2 = $deck->getDeck();

        $this->assertNotEquals($res, $res2);
    }

    /**
     * Creates deck, tests that drawing (default 1) cards works as intented and returns correct number of card objects and removes them from the deck
     */
    public function testDrawingCardFromDeck(): void
    {
        $deck = new DeckOfCards("graphic");
        $res = $deck->drawCard();
        $res2 = $deck->drawCard(3);
        $res3 = $deck->getNumberCards();

        $this->assertIsArray($res);
        $this->assertIsArray($res2);
        $this->assertInstanceOf('App\Card\Card', $res[0]);
        $this->assertInstanceOf('App\Card\Card', $res2[1]);
        $this->assertEquals(48, $res3);
    }
}
