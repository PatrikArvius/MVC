<?php

namespace App\Adventure;

class AbandonedTrainStation extends Room
{
    protected string $name = "Abandoned Train Station";
    protected string $image = "abandonedtrainstation2.png";
    protected string $description = "You arrive at an abandoned train station, the air is thick of dread. The station has clearly seen better days, its windows are broken and there 
    has been damage to the structure. A faint light is glowing at what used to be the entrance to the tracks. Way further in the distance you spot a mountaintop submerged in a strange 
    glow that resembles the mysterious glow emitted from the beam you so bravely stepped into which got you here in the first place, could that be the way home?";

    /**
     * @param array<int, Item> $items
     */
    public function __construct(array|null $items = null, bool|null $requiresItem = null, Item|null $requiredItem = null)
    {
        $this->items = [];

        if ($items != null) {
            $this->items = $items;
        }

        if ($requiresItem != null) {
            $this->requiresItem = $requiresItem;
            $this->requiredItem = $requiredItem;
            $this->locked = true;
        }
    }
}
