<?php

declare(strict_types=1);

namespace customtags\darealaqua\form;

use customtags\darealaqua\Main;
use customtags\darealaqua\utils\API;
use libs\FormAPI\SimpleForm;
use pocketmine\player\Player;

class AvailableTagsForm extends SimpleForm
{

    /**
     * @param Player $player
     * @return SimpleForm
     */
    public static function open(Player $player): SimpleForm
    {
        $form = new SimpleForm(function (Player $player, $data = null){
            $api = Main::getInstance()->getAPI();
            if($data !== null) {
                $tag = array_values($api->getTags()["tags"])[$data];
                if (!$player->hasPermission((string)$tag["perm"])) {
                    $player->sendMessage(str_replace([
                        "{prefix}", "{player}", "{tag}", "{line}"], [
                        $api->getPrefix(), $player->getName(), (string)$tag["name"], "\n"], $api->getMessages()->get("no-perm")
                    ));
                } else {
                    $api->setPlayerTag($player, $tag["name"]);
                    $player->sendMessage(str_replace([
                        "{prefix}", "{player}", "{tag}", "{line}"], [
                        $api->getPrefix(), $player->getName(), (string)$tag["name"], "\n"], $api->getMessages()->get("selected")
                    ));
                }
            }
        });
        $api = Main::getInstance()->getAPI();
        $form->setTitle($api->getCfg()->getNested("menu-tags.title"));
        $form->setContent(str_replace(["{prefix}", "{player}", "{tag}", "{line}"], [$api->getPrefix(), $player->getName(), $api->getPlayerTag($player, API::FORM_FORMAT), "\n"], $api->getCfg()->getNested("menu-tags.content")));
        foreach ($api->getTags()["tags"] as $tag){
            if ($player->hasPermission($tag["perm"])) {
                $form->addButton(str_replace(["{tag}", "{line}"], [$tag["name"], "\n"], $api->getCfg()->getNested("menu-tags.unlocked-button")));
            } else {
                $form->addButton(str_replace(["{tag}", "{line}"], [$tag["name"], "\n"], $api->getCfg()->getNested("menu-tags.locked-button")));
            }
        }
        return $form;
    }
}
