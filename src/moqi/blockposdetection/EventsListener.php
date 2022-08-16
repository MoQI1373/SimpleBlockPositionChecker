<?php

namespace moqi\blockposdetection;

use pocketmine\event\block\BlockBreakEvent;
use pocketmine\event\Listener;

class EventsListener implements Listener{

    /** @var Main */
    public Main $main;

    public function __construct(Main $main){
        $this->main = $main;
    }

    public function onBreakBlock(BlockBreakEvent $event){
        $player = $event->getPlayer();
        $block = $event->getBlock();

        if (isset($this->main->checkingBlockPos[$player->getId()])){
            $player->sendMessage("§b{$block->getName()}'s Pos:  X: §6{$block->getPosition()->getX()}  §bY: §6{$block->getPosition()->getY()}  §bZ: §6{$block->getPosition()->getZ()}");

            $event->cancel();
            unset($this->main->checkingBlockPos[$player->getId()]);
        }
    }
}
