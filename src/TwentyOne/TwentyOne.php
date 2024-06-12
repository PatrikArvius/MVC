<?php

namespace App\TwentyOne;

use App\Card\DeckOfCards;
use App\TwentyOne\Player;
use App\TwentyOne\Dealer;

/**
 * This class handles most of the logic in order to play the game 21
 */
class TwentyOne
{
    /**
     * @var Player $player
     */
    private Player $player;
    /**
     * @var DeckofCards $deck
    */
    private DeckOfCards $deck;
    /**
     * @var Dealer $dealer
     */
    private Dealer $dealer;
    /**
     * @var bool $playerIsActive
     */
    private $playerIsActive = true;
    /**
     * @var bool $allStand
     */
    private $allStand = false;

    private string $winner = "";
    private int $playerHandValue = 0;
    private int $altPlayerHandValue = 0;
    private int $dealerHandValue = 0;
    private int $altDealerHandValue = 0;

    /**
     * Constructs the game with a player object, dealer object and deckofcards object and shuffles the deck.
     */
    public function __construct(Player $player, Dealer $dealer, DeckOfCards $deck)
    {
        $this->player = $player;
        $this->dealer = $dealer;
        $this->deck = $deck;
        $this->deck->shuffleDeck();
    }

    /**
     * Sets the hand values of the player and dealer, altValue is used for alternate values of Aces
     * Aces can be worth either 1 or 14
     */
    public function setHandValues(): void
    {
        if ($this->playerIsActive) {
            $playerValues = $this->player->getHandValues();
            $this->playerHandValue = $playerValues['value'];
            $this->altPlayerHandValue = $playerValues['altValue'];
        }

        $dealerValues = $this->dealer->getHandValues();
        $this->dealerHandValue = $dealerValues['value'];
        $this->altDealerHandValue = $dealerValues['altValue'];
    }

    /**
     * Gets hand value from player or dealer or the alternative handvalue from player or dealer
     * depending on string provided
     *
     * @return int|null
     */
    public function getSpecificHandValue(string $hand): ?int
    {
        $val = null;
        switch ($hand) {
            case "player":
                $val = $this->playerHandValue;
                break;
            case "altPlayer":
                $val = $this->altPlayerHandValue;
                break;
            case "dealer":
                $val = $this->dealerHandValue;
                break;
            case "altDealer":
                $val = $this->altDealerHandValue;
                break;
        }
        return $val;
    }

    /**
     * Method that returns the player object
     *
     * @return Player
     */
    public function getPlayer(): ?object
    {
        return $this->player;
    }

    /**
     * Method that returns the dealer object
     *
     * @return Dealer
     */
    public function getDealer(): ?object
    {
        return $this->dealer;
    }

    /**
     * Compares handvalue and alternate hand value for the player and dealer, sets highest valid player hand, sets highest valid dealer hand
     * then compares respektive highest hands and sets a winner based on the comparison. If dealers best hand is equal to or greater than
     * players best hand then the dealer wins
     */
    public function compareHands(): void
    {
        // Gets the best player hand value
        $playerVal = $this->getBestPlayerValue();

        // Gets the best dealer hand value
        $dealerVal = $this->getBestDealerValue();

        // Checks if the dealer value is valid and if its greater than or equal to the player value, if so sets dealer as winner
        if ($dealerVal <= 21 && ($dealerVal >= $playerVal)) {
            $this->winner = "dealer";
            return;
        }

        $this->winner = "player";
    }

    /**
     * Checks weather the standard player hand value or the alternate value is the best value and returns it
     *
     * @return int $playerVal
     */
    public function getBestPlayerValue(): int
    {
        // Sets playerVal to current player value, that value counts aces as 1's
        $playerVal = $this->playerHandValue;

        //Sets the best valid player hand value
        if ($this->altPlayerHandValue < 21 && ($this->altPlayerHandValue > $this->playerHandValue)) {
            $playerVal = $this->altPlayerHandValue;
        }

        return $playerVal;
    }

    /**
     * Checks weather the standard dealer hand value or the alternate value is the best value and returns it
     *
     * @return int $dealerVal
     */
    public function getBestDealerValue(): int
    {
        // Sets dealerVal to current player value, that value counts aces as 1's
        $dealerVal = $this->dealerHandValue;

        //Sets the best valid dealer hand value
        if ($this->altDealerHandValue <= 21 && ($this->altDealerHandValue > $this->dealerHandValue)) {
            $dealerVal = $this->altDealerHandValue;
        }

        return $dealerVal;
    }

    /**
     * Sets the winner to provided string parameter
     *
     * @param string $winner
     */
    public function setWinner(string $winner): void
    {
        $this->winner = $winner;
    }

    /**
     * Method that returns the value of the private variable $winner
     */
    public function getWinner(): ?string
    {
        return $this->winner;
    }

    /**
     * Method that returns weather or not the player is active as a boolean
     *
     * @return bool
     * */
    public function isPlayerActive(): bool
    {
        return $this->playerIsActive;
    }

    /**
     * Method that draws a card from the deck
     * based on if the player is active or not, said card is then added to either the player hand or dealer hand
     */
    public function drawCard(): void
    {
        if ($this->playerIsActive) {
            $card = $this->deck->drawCard();
            $this->player->add($card[0]);
            return;
        }

        $card = $this->deck->drawCard();
        $this->dealer->add($card[0]);
    }

    /**
     * Method performing the stand action which sets the playerIsActive variable from true to false if the player was active
     * sets the variable for all players standing to true if it was called when the player was not active and then compares
     * dealer and player hands
     */
    public function stand(): void
    {
        if ($this->playerIsActive) {
            $this->playerIsActive = false;
            return;
        }

        $this->allStand = true;
        $this->compareHands();
    }

    /**
     * Method returning boolean representing weather or not player and dealer are both standing
     */
    public function getAllStanding(): bool
    {
        return $this->allStand;
    }

    /**
     * Method that checks if the player hand has hit a value of 21 thus winning the game, or a hand value above 21 thus losing the game
     */
    public function checkPlayer(): void
    {
        if ($this->playerHandValue === 21 || $this->altPlayerHandValue === 21) {
            $this->setWinner("player");
        }
        if ($this->playerHandValue > 21 && $this->altPlayerHandValue > 21) {
            $this->setWinner("dealer");
        }
    }

    /**
     * Method that checks if the dealer hand has hit a value of 21 thus winning the game, or a hand value above 21 thus losing the game
     */
    public function checkDealer(): void
    {
        if ($this->dealerHandValue === 21 || $this->altDealerHandValue === 21) {
            $this->setWinner("dealer");
        }
        if ($this->dealerHandValue > 21 && $this->altDealerHandValue > 21) {
            $this->setWinner("player");
        }
    }

    /**
     * Method that calls method to check player or dealer depending on if the player is active or not
     */
    public function checkGameEnd(): void
    {
        if ($this->playerIsActive) {
            $this->checkPlayer();
            return;
        }
        $this->checkDealer();
    }

    /**
     * Method that simmulates the dealer playing the game, dealer plays another round at handvalues of 16 or less and stands at above 16
     */
    public function simmulateOpponent(): void
    {
        if ($this->dealerHandValue <= 16 || $this->altDealerHandValue <= 16) {
            $this->playRound();
        }
        $this->stand();
    }

    /**
     * Method for playing a game round by drawing a card, setting the hand values for the players and then checking if either hit 21 or above thus ending the game.
     * It also checks weather or not the player is active and calls the method to simmulate the opponent if the player is not active
     */
    public function playRound(): void
    {
        $this->drawCard();
        $this->setHandValues();
        $this->checkGameEnd();

        if (!$this->playerIsActive && $this->winner === "") {
            $this->simmulateOpponent();
        }
    }
}
