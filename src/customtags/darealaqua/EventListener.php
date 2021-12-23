<?php

declare(strict_types=1);

namespace customtags\darealaqua;

use customtags\darealaqua\utils\API;
use pocketmine\event\Listener;
use pocketmine\event\player\PlayerChatEvent;
use pocketmine\event\player\PlayerJoinEvent;
use pocketmine\event\player\PlayerLoginEvent;

class EventListener implements Listener{

    /*** @var Main */
    private $main;

    /**
     * EventListener constructor.
     * @param Main $main
     */
    public function __construct(Main $main)
    {
        $this->main = $main;
    }

    /**
     * @priority MONITOR
     * @param PlayerChatEvent $event
     * @return void
     */
    public function onPlayerChat(PlayerChatEvent $event): void
    {
        $player = $event->getPlayer();
        $api = $this->main->getAPI();
        $format = str_replace(["{tag}", "{format}"], [
            $api->getPlayerTag($player, API::CHAT_FORMAT), $event->getFormat()
        ],
            $api->getCfg()->getNested("chat-format")
        );
        $event->setFormat($format);
    }

    /**
     * @param PlayerLoginEvent $event
     */
    public function onPlayerLogin(PlayerLoginEvent $event): void
    {
        $player = $event->getPlayer();
        $api = $this->main->getAPI();
        if(!$api->existPlayer($player)){
            $api->createPlayer($player);
        }
    }
}
