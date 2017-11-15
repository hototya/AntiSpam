<?php
namespace hototya\chat;

use pocketmine\plugin\PluginBase;
use pocketmine\event\Listener;

use pocketmine\event\player\PlayerChatEvent;

class AntiSpam extends PluginBase implements Listener
{
    private $spam = [];

    public function onEnable()
    {
        $this->getServer()->getPluginManager()->registerEvents($this, $this);
    }

    public function onChat(PlayerChatEvent $event)
    {
        $player = $event->getPlayer();
        $name = $player->getName();
        $msg = $event->getMessage();
        $time = microtime(true);
        if (isset($this->spam[$name])) {
            if ($this->spam[$name]["msg"] === $msg) {
                $event->setCancelled();
            } elseif (floor($time - $this->spam[$name]["time"]) < 3) {
                $event->setCancelled();
            }
        }
        $this->spam[$name]["msg"] = $msg;
        $this->spam[$name]["time"] = $time;
    }
}
