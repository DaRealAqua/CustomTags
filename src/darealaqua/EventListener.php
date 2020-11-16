<?php

namespace darealaqua;

use darealaqua\utils\Utils;
use pocketmine\event\Listener;
use pocketmine\event\player\PlayerChatEvent;
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
     * @param PlayerChatEvent $event
     */
    public function onChat(PlayerChatEvent $event) : void{
        $event->getPlayer()->setDisplayName(str_replace(["{tag}", "{player}"], [Utils::getPlayerTag($event->getPlayer()), $event->getPlayer()->getName()], $this->main->config["chat-format"]));
    }

    /**
     * @param PlayerLoginEvent $event
     */
    public function onLogin(PlayerLoginEvent $event) : void{
        if(!$this->main->tag->exists(strtolower($event->getPlayer()->getName()))){
            $this->main->tag->setNested(strtolower($event->getPlayer()->getName()) . ".tag", "#");
            $this->main->tag->save();
        }
    }
}
