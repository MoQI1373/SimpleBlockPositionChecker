<?php

namespace moqi\blockposdetection;

use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\player\Player;
use pocketmine\plugin\PluginBase;

class Main extends PluginBase{

    public array $checkingBlockPos;

    public function onEnable(): void
    {
        $this->getLogger()->info("§aBlock Pos Checker by MoQi enabled");

        $this->getServer()->getPluginManager()->registerEvents(new EventsListener($this), $this);
    }

    public function onDisable(): void
    {
        $this->getLogger()->info("§aPlugin Disable");
    }

    public function onCommand(CommandSender $sender, Command $command, string $label, array $args): bool
    {
        if ($command->getName() == "checkblockpos"){
            if ($sender instanceof Player){
                if ($sender->hasPermission("checkpos.permission")) {
                    if (!isset($this->checkingBlockPos[$sender->getId()])) {
                        $this->checkingBlockPos[$sender->getId()] = $sender;
                        $sender->sendMessage("§6Break a block to check the pos");
                    } else {
                        $sender->sendMessage("§cYou are already in checking state");
                        $sender->sendMessage("§6Break a block to check the pos");
                    }
                } else{
                    $sender->sendMessage("§cYou dont have permission to use this command!");
                }
            }
        }
        return false;
    }
}
