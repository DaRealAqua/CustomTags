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

namespace customtags\darealaqua\utils;

use customtags\darealaqua\Main;
use pocketmine\utils\Config;
use pocketmine\player\Player;
use pocketmine\utils\TextFormat as C;

class API {

    /** @var Config */
    private Config
        $config,
        $tags,
        $messages,
        $players;

    /** @var int */
    public const
        CHAT_FORMAT = 0,
        FORM_FORMAT = 1;

    /**
     * @param Main $main
     */
    public function __construct(
        private Main $main
    ) {
        $this->init();
    }

    /**
     * @return void
     */
    public function init() : void {
        #-------- YAML - RESOURCES --------#
        $this->main->saveResource("config.yml");
        $this->main->saveResource("tags.yml");
        $this->main->saveResource("messages.yml");
        $this->main->saveResource("players.yml");

        #-------- YAML - CONFIGS --------#
        $this->config = new Config($this->main->getDataFolder() . "config.yml", Config::YAML);
        $this->tags = new Config($this->main->getDataFolder() . "tags.yml", Config::YAML);
        $this->messages = new Config($this->main->getDataFolder() . "messages.yml", Config::YAML);
        $this->players = new Config($this->main->getDataFolder() . "players.yml", Config::YAML);
    }

    /**
     * @param Player $player
     * @return bool
     */
    public function existPlayer(Player $player) : bool {
        return $this->players->exists(strtolower($player->getName()));
    }

    /**
     * @param Player $player
     * @return void
     */
    public function createPlayer(Player $player) : void {
        $this->players->setNested(strtolower($player->getName()) . ".tag", null);
        $this->players->save();
    }

    /**
     * @param Player $player
     * @param int $modeId
     * @return string
     */
    public function getPlayerTag(Player $player, int $modeId = 0) : string {
        $playerTag = $this->getPlayers()->getAll()[strtolower($player->getName())]["tag"];
        switch ($modeId) {
            case self::CHAT_FORMAT:
                return $playerTag === null ? "" : $playerTag;
            case self::FORM_FORMAT:
                return $playerTag === null ? C::RED . "No Tag!" : $playerTag;
            default:
                return "Unknown";
        }
    }

    /**
     * @param Player $player
     * @param string $tag
     * @return void
     */
    public function setPlayerTag(Player $player, string $tag) : void {
        $this->getPlayers()->setNested(strtolower($player->getName()) . ".tag", $tag);
        $this->getPlayers()->save();
    }

    /**
     * @param Player $player
     * @return void
     */
    public function resetPlayerTag(Player $player) : void {
        $this->getPlayers()->setNested(strtolower($player->getName()) . ".tag", null);
        $this->getPlayers()->save();
    }

    /**
     * @return string
     */
    public function getPrefix() : string {
        return $this->config->get("prefix");
    }

    /**
     * @return Config
     */
    public function getCfg() : Config {
        return $this->config;
    }

    /**
     * @return Config
     */
    public function getTags() : Config {
        return $this->tags;
    }

    /**
     * @return Config
     */
    public function getMessages() : Config {
        return $this->messages;
    }

    /**
     * @return Config
     */
    public function getPlayers() : Config {
        return $this->players;
    }

}
