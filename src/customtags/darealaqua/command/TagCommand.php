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

namespace customtags\darealaqua\command;

use customtags\darealaqua\form\MainTagsForm;
use customtags\darealaqua\Main;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\player\Player;

class TagCommand extends Command {

    /**
     * @param Main $main
     */
    public function __construct(
        private Main $main
    ) {
        parent::__construct("tag", "Tags.", "", ["tags"]);
    }

    /**
     * @param CommandSender $sender
     * @param string $commandLabel
     * @param array $args
     * @return void
     */
    public function execute(CommandSender $sender, string $commandLabel, array $args) : void {
        if (!$sender instanceof Player) {
            $sender->sendMessage($this->main->getAPI()->getPrefix() . "You can use this command only in-game!");
        } else {
            $sender->sendForm(MainTagsForm::open($sender));
        }
    }

}
