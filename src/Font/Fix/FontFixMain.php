<?php

namespace Font\Fix;

use pocketmine\plugin\PluginBase;
use pocketmine\event\Listener;
use pocketmine\event\player\PlayerJoinEvent;
use pocketmine\network\mcpe\protocol\ResourcePackStackPacket;
use pocketmine\network\mcpe\protocol\types\resourcepacks\ResourcePackStackEntry;
use pocketmine\network\mcpe\protocol\types\Experiments;

class FontFixMain extends PluginBase implements Listener {

    public function onEnable(): void {
        $this->getLogger()->info("> Фикс на шрифты запущен! автор: KBAPTETu.");
        $this->getServer()->getPluginManager()->registerEvents($this, $this);
    }
    public function refreshFonts($player): void {
        $manager = $this->getServer()->getResourcePackManager();
        $stack = [];

        foreach ($manager->getResourceStack() as $pack) {
            $stack[] = new \pocketmine\network\mcpe\protocol\types\resourcepacks\ResourcePackStackEntry(
                $pack->getPackId(), 
                $pack->getPackVersion(), 
                ""
            );
        }
        
        $pk = ResourcePackStackPacket::create(
            $stack,
            [],
            true,
            "*",
            new Experiments([], false),
            false
        );

        $player->getNetworkSession()->sendDataPacket($pk);
    }
    public function onJoin(PlayerJoinEvent $event): void {
        $player = $event->getPlayer();
        $this->refreshFonts($player);
    }
}