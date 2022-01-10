<?php

/*
 * CustomTags Plugin
 * Copyright (C) 2022 DaRealAqua
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 */

declare(strict_types=1);

namespace customtags\darealaqua;

use customtags\darealaqua\utils\API;
use pocketmine\event\Listener;
use pocketmine\event\player\PlayerChatEvent;
use pocketmine\event\player\PlayerLoginEvent;

class EventListener implements Listener {

    /**
     * @param Main $main
     */
    public function __construct(
        private Main $main
    ) {
    }

    /**
     * @priority NORMAL
     * @param PlayerChatEvent $event
     * @return void
     */
    public function onPlayerChat(PlayerChatEvent $event) : void {
        $player = $event->getPlayer();
        $api = $this->main->getAPI();
        $format = str_replace(["{tag}", "{format}"], [$api->getPlayerTag($player, API::CHAT_FORMAT), $event->getFormat()], $api->getCfg()->get("chat-format"));
        $event->setFormat($format);
    }

    /**
     * @priority NORMAL
     * @param PlayerLoginEvent $event
     * @return void
     */
    public function onPlayerLogin(PlayerLoginEvent $event) : void {
        $player = $event->getPlayer();
        $api = $this->main->getAPI();
        if (!$api->existPlayer($player)) {
            $api->createPlayer($player);
        }
    }

}
