<?php

namespace App\TwentyOne;

use App\Card\Card;
use App\Card\CardHand;
use App\Card\DeckOfCards;
use PHPUnit\Framework\TestCase;

class TwentyOneTest extends TestCase
{
    /**
     * Test that the twenty one class gets created correctly
     */
    public function testCreate(): void
    {
        $player = new Player(new CardHand());
        $dealer = new Dealer(new CardHand());
        $deck = new DeckOfCards("graphic");
        $twentyOne = new TwentyOne($player, $dealer, $deck);

        $this->assertInstanceOf('App\TwentyOne\TwentyOne', $twentyOne);
    }

    /**
     * Creates a new game, adds an ace to the player and makes sure the game sets correct hand values for the player and dealer
     */
    public function testSetHandValues(): void
    {
        $card = new Card("hearts", 1);
        $player = new Player(new CardHand());
        $dealer = new Dealer(new CardHand());
        $deck = new DeckOfCards("graphic");
        $twentyOne = new TwentyOne($player, $dealer, $deck);
        $twentyOne->setHandValues();
        $res = $twentyOne->getSpecificHandValue("player");

        $player->add($card);
        $twentyOne->setHandValues();
        $res2 = $twentyOne->getSpecificHandValue("player");
        $res3 = $twentyOne->getSpecificHandValue("altPlayer");
        $res4 = $twentyOne->getSpecificHandValue("dealer");
        $res5 = $twentyOne->getSpecificHandValue("altDealer");

        $this->assertEquals(0, $res);
        $this->assertEquals(1, $res2);
        $this->assertEquals(14, $res3);
        $this->assertEquals(0, $res4);
        $this->assertEquals(0, $res5);
    }

    /**
     * Tests that getting a specific hand value returns the correct value based on cards added to specific hand
     */
    public function testGetSpecificHandValue(): void
    {
        $card = new Card("clubs", 2);
        $card2 = new Card("hearts", 1);
        $player = new Player(new CardHand());
        $dealer = new Dealer(new CardHand());
        $deck = new DeckOfCards("graphic");
        $twentyOne = new TwentyOne($player, $dealer, $deck);
        $dealer->add($card);
        $dealer->add($card2);

        $twentyOne->setHandValues();
        $res = $twentyOne->getSpecificHandValue("dealer");
        $res2 = $twentyOne->getSpecificHandValue("altDealer");

        $this->assertEquals(3, $res);
        $this->assertEquals(16, $res2);
    }

    /**
     * Tests that the Twenty One game returns Player and Dealer objects correctly
     */
    public function testGetPlayerAndDealerObjects(): void
    {
        $player = new Player(new CardHand());
        $dealer = new Dealer(new CardHand());
        $deck = new DeckOfCards("graphic");
        $twentyOne = new TwentyOne($player, $dealer, $deck);
        $res = $twentyOne->getPlayer();
        $res2 = $twentyOne->getDealer();

        $this->assertInstanceOf('App\TwentyOne\Player', $res);
        $this->assertInstanceOf('App\TwentyOne\Dealer', $res2);
    }

    /**
     * Tests that TwentyOne compare hands function correctly compares player and dealer hands and sets correct winner
     */
    public function testCompareHands(): void
    {
        $card = new Card("clubs", 2);
        $card2 = new Card("hearts", 1);
        $player = new Player(new CardHand());
        $dealer = new Dealer(new CardHand());
        $deck = new DeckOfCards("graphic");
        $twentyOne = new TwentyOne($player, $dealer, $deck);
        $twentyOne->compareHands();
        $res = $twentyOne->getWinner();

        //Adds card to player hand such that player should be the winner, sets hand values, compares hands and gets winner
        $player->add($card);
        $twentyOne->setHandValues();
        $twentyOne->compareHands();
        $res2 = $twentyOne->getWinner();

        //Adds cards such that dealer should be the winner, sets hand values, compares hands and gets winner
        $player->add($card2);
        $dealer->add($card);
        $dealer->add($card2);
        $twentyOne->setHandValues();
        $twentyOne->compareHands();
        $res3 = $twentyOne->getWinner();


        $this->assertEquals("dealer", $res);
        $this->assertEquals("player", $res2);
        $this->assertEquals("dealer", $res3);
    }

    /**
     * Tests that setting the winner correctly sets the winner to string parameter provided
     */
    public function testSetWinner(): void
    {
        $player = new Player(new CardHand());
        $dealer = new Dealer(new CardHand());
        $deck = new DeckOfCards("graphic");
        $twentyOne = new TwentyOne($player, $dealer, $deck);

        $res = $twentyOne->getWinner();
        $twentyOne->setWinner("Mos");
        $res2 = $twentyOne->getWinner();

        $this->assertEquals("", $res);
        $this->assertEquals("Mos", $res2);
    }

    /**
     * Tests that card gets drawn from deck correctly and ends up in correct hand based on active player
     */
    public function testDrawCard(): void
    {
        $player = new Player(new CardHand());
        $dealer = new Dealer(new CardHand());
        $deck = new DeckOfCards("graphic");
        $twentyOne = new TwentyOne($player, $dealer, $deck);

        //Player is active entity by default so drawn cards end up in player hand
        $twentyOne->drawCard();
        $twentyOne->setHandValues();
        $res = $deck->getNumberCards();
        $res2 = $twentyOne->getSpecificHandValue("player");

        //Makes dealer the active entity, so that drawn cards end up in dealer hand
        $twentyOne->stand();
        $twentyOne->drawCard();
        $twentyOne->setHandValues();
        $res3 = $deck->getNumberCards();
        $res4 = $twentyOne->getSpecificHandValue("dealer");

        $this->assertEquals(51, $res);
        $this->assertGreaterThan(0, $res2);
        $this->assertEquals(50, $res3);
        $this->assertGreaterThan(0, $res4);
    }

    /**
     * Tests that calling the stand function correctly switches active player and sets a winner if both dealer and player stands
     */
    public function testStand(): void
    {
        $player = new Player(new CardHand());
        $dealer = new Dealer(new CardHand());
        $deck = new DeckOfCards("graphic");
        $twentyOne = new TwentyOne($player, $dealer, $deck);

        $res = $twentyOne->isPlayerActive();
        $res2 = $twentyOne->getAllStanding();

        $twentyOne->stand();
        $res3 = $twentyOne->isPlayerActive();

        $twentyOne->stand();
        $res4 = $twentyOne->getAllStanding();
        $res5 = $twentyOne->getWinner();

        $this->assertEquals(true, $res);
        $this->assertEquals(false, $res2);
        $this->assertEquals(false, $res3);
        $this->assertEquals(true, $res4);
        $this->assertNotEquals("", $res5);
    }

    /**
     * Test that checks if checkPlayer function correctly checks for win or loss condition for the player
     */
    public function testCheckPlayer(): void
    {
        $card = new Card("clubs", 21);
        $player = new Player(new CardHand());
        $dealer = new Dealer(new CardHand());
        $deck = new DeckOfCards("graphic");
        $twentyOne = new TwentyOne($player, $dealer, $deck);

        $player->add($card);
        $twentyOne->setHandValues();
        $twentyOne->checkPlayer();
        $res = $twentyOne->getWinner();
        $this->assertEquals("player", $res);

        //Adds another card making the player go higher than 21, thus the dealer should win
        $player->add($card);
        $twentyOne->setHandValues();
        $twentyOne->checkPlayer();
        $res2 = $twentyOne->getWinner();
        $this->assertEquals("dealer", $res2);
    }

    /**
     * Test that checks if checkDealer function correctly checks for win or loss condition for the dealer
     */
    public function testCheckDealer(): void
    {
        $card = new Card("clubs", 21);
        $player = new Player(new CardHand());
        $dealer = new Dealer(new CardHand());
        $deck = new DeckOfCards("graphic");
        $twentyOne = new TwentyOne($player, $dealer, $deck);

        $dealer->add($card);
        $twentyOne->setHandValues();
        $twentyOne->checkDealer();
        $res = $twentyOne->getWinner();
        $this->assertEquals("dealer", $res);

        //Adds another card making the dealer go higher than 21, thus the player should win
        $dealer->add($card);
        $twentyOne->setHandValues();
        $twentyOne->checkDealer();
        $res2 = $twentyOne->getWinner();
        $this->assertEquals("player", $res2);
    }

    /**
     * Tests that checkGameEnd function correctly calls methods for checking active player for win condition
     */
    public function testCheckGameEnd(): void
    {
        $card = new Card("clubs", 21);
        $player = new Player(new CardHand());
        $dealer = new Dealer(new CardHand());
        $deck = new DeckOfCards("graphic");
        $twentyOne = new TwentyOne($player, $dealer, $deck);

        $player->add($card);
        $twentyOne->setHandValues();
        $twentyOne->checkGameEnd();
        $res = $twentyOne->getWinner();
        $this->assertEquals("player", $res);

        $dealer->add($card);
        $twentyOne->setHandValues();
        $twentyOne->stand();
        $twentyOne->checkGameEnd();
        $res2 = $twentyOne->getWinner();
        $this->assertEquals("dealer", $res2);
    }

    /**
     * Tests that dealer simmulation happens correctly, that dealer draws card on 16 and stands above 16
     */
    public function testOpponentSimmulation(): void
    {
        $card = new Card("clubs", 16);
        $player = new Player(new CardHand());
        $dealer = new Dealer(new CardHand());
        $deck = new DeckOfCards("graphic");
        $twentyOne = new TwentyOne($player, $dealer, $deck);
        $dealer->add($card);
        $twentyOne->setHandValues();
        $twentyOne->stand();
        $twentyOne->simmulateOpponent();

        $res = $twentyOne->getSpecificHandValue("dealer");
        $res2 = $twentyOne->getWinner();
        $res3 = $twentyOne->getAllStanding();

        $this->assertGreaterThan(16, $res);
        $this->assertNotEquals("", $res2);
        $this->assertEquals(true, $res3);
    }

    /**
     * Tests that playRound method correctly calls methods and simmulates opponent if player is standing without a winner being set
     */
    public function testPlayRound(): void
    {
        $card = new Card("clubs", 16);
        $player = new Player(new CardHand());
        $dealer = new Dealer(new CardHand());
        $deck = new DeckOfCards("graphic");
        $twentyOne = new TwentyOne($player, $dealer, $deck);
        $player->add($card);
        $twentyOne->setHandValues();
        $twentyOne->stand();
        $twentyOne->playRound();

        $res = $twentyOne->getSpecificHandValue("dealer");
        $res2 = $twentyOne->getWinner();
        $res3 = $twentyOne->getAllStanding();
        $res4 = $twentyOne->getSpecificHandValue("player");

        $this->assertGreaterThan(16, $res);
        $this->assertNotEquals("", $res2);
        $this->assertEquals(true, $res3);
        $this->assertEquals(16, $res4);
    }
}
