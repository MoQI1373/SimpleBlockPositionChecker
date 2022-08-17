<?php

namespace moqi\blockposdetection;

use SOFe\AwaitStd\AwaitStd;
use SOFe\AwaitStd\DisposeException;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\event\EventPriority;
use pocketmine\event\block\BlockBreakEvent;
use pocketmine\player\Player;
use pocketmine\plugin\PluginBase;

class Main extends PluginBase{

    private AwaitStd $std;
    /**
     * @var true[]
     * @phpstan-var array<int, true>
     */
    private array $checking;

    public function onEnable(): void
    {
        $this->getLogger()->info("§aBlock Pos Checker by MoQi enabled");
        $this->std = AwaitStd::init($this);
    }

    public function onDisable(): void
    {
        $this->getLogger()->info("§aPlugin Disable");
    }

    public function onCommand(CommandSender $sender, Command $command, string $label, array $args): bool
    {
        if (!$sender instanceof Player || $command->getName() !== "checkblockpos") {
            return false;
        }

        Await::f2c(function () use ($sender) : \Generator {
            $id = $sender->getId();
            if (isset($this->checking[$id])) {
                $sender->sendMessage("§cYou are already in checking state");
                return;
            }
            $this->checking[$id] = true;

            $sender->sendMessage("§6Break a block to check the pos");
            yield from $this->std->awaitEvent(
                BlockBreakEvent::class,
                fn(BlockBreakEvent $event) : bool => $event->getPlayer() === $sender,
                false,
                EventPriority::NORMAL, // Event will be cancelled, so is not monitor.
                false,
                $sender
            );
            $event->cancel();
            $block = $event->getBlock();

            $player->sendMessage("§b{$block->getName()}'s Pos:  X: §6{$block->getPosition()->getX()}  §bY: §6{$block->getPosition()->getY()}  §bZ: §6{$block->getPosition()->getZ()}");
            unset($this->checking[$id]);
        }, null, [DisposeException::class => static function () : void {}]);

        return true;
    }
}
