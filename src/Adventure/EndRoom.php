<?php

namespace App\Adventure;

class EndRoom extends Room
{
    protected string $name = "Game Completed!";
    protected string $image = "https://art.pixilart.com/0a3234005cc939d.gif";
    protected string $description = "You managed to find and return all the missing items and with the help of that kind wizard you made it back safely through the beam with all limbs intact. You gained valueable 
    experience in regards to stepping into random portals and will be better prepared next time you happen upon one!";

    /**
     * @param array<int, Item> $items
     */
    public function __construct(array|null $items = null, bool|null $requiresItem = null, Item|null $requiredItem = null)
    {
        $this->items = [];

        if ($items != null) {
            $this->items = $items;
        }

        if ($requiresItem != false) {
            $this->requiresItem = $requiresItem;
            $this->requiredItem = $requiredItem;
        }
    }
}
