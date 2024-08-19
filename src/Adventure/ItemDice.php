<?php

namespace App\Adventure;

class ItemDice extends Item
{
    /** @return int */
    public function action(): int
    {
        $roll = random_int(1, 6);
        return $roll;
    }
}
