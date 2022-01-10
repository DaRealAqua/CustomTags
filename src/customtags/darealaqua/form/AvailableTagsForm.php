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

namespace customtags\darealaqua\form;

use customtags\darealaqua\Main;
use customtags\darealaqua\utils\API;
use libs\FormAPI\SimpleForm;
use pocketmine\player\Player;

class AvailableTagsForm extends SimpleForm {

    /**
     * @param Player $player
     * @return SimpleForm
     */
    public static function open(Player $player) : SimpleForm {
        $form = new SimpleForm(function (Player $player, $data = null) {
            $api = Main::getInstance()->getAPI();
            if ($data !== null) {
                $tag = array_values($api->getTags()->getAll()["tags"])[$data];
                if (!$player->hasPermission((string) $tag["perm"])) {
                    $player->sendMessage(str_replace(["{prefix}", "{player}", "{tag}", "{line}"], [$api->getPrefix(), $player->getName(), (string) $tag["name"], "\n"], $api->getMessages()->get("no-perm")));
                } else {
                    $api->setPlayerTag($player, (string) $tag["name"]);
                    $player->sendMessage(str_replace(["{prefix}", "{player}", "{tag}", "{line}"], [$api->getPrefix(), $player->getName(), (string) $tag["name"], "\n"], $api->getMessages()->get("selected")));
                }
            }
        });
        $api = Main::getInstance()->getAPI();
        $form->setTitle($api->getCfg()->getNested("menu-tags.title"));
        $form->setContent(str_replace(["{prefix}", "{player}", "{tag}", "{line}"], [$api->getPrefix(), $player->getName(), $api->getPlayerTag($player, API::FORM_FORMAT), "\n"], $api->getCfg()->getNested("menu-tags.content")));
        foreach ($api->getTags()->getAll()["tags"] as $tag) {
            if ($player->hasPermission($tag["perm"])) {
                $form->addButton(str_replace(["{tag}", "{line}"], [$tag["name"], "\n"], $api->getCfg()->getNested("menu-tags.unlocked-button")));
            } else {
                $form->addButton(str_replace(["{tag}", "{line}"], [$tag["name"], "\n"], $api->getCfg()->getNested("menu-tags.locked-button")));
            }
        }
        return $form;
    }

}
