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

use customtags\darealaqua\command\TagCommand;
use customtags\darealaqua\utils\API;
use pocketmine\plugin\Plugin;
use pocketmine\utils\SingletonTrait;
use pocketmine\plugin\PluginBase;

class Main extends PluginBase {

    /** SingletonTrait */
    use SingletonTrait;

    /** @var API */
    private API $api;

    /** @var Plugin */
    private Plugin $economyAPI;

    /**
     * @return void
     */
    protected function onEnable() : void {
        self::setInstance($this);
        $this->api = new API($this);
        $this->economyAPI = $this->getServer()->getPluginManager()->getPlugin("EconomyAPI");
        $this->getServer()->getPluginManager()->registerEvents(new EventListener($this), $this);
        $this->getServer()->getCommandMap()->register($this->getName(), new TagCommand($this));
    }

    /**
     * @return API
     */
    public function getAPI() : API {
        return $this->api;
    }

    /**
     * @return Plugin
     */
    public function getEconomyAPI() : Plugin {
        return $this->economyAPI;
    }

}
