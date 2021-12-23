<?php

declare(strict_types=1);

namespace customtags\darealaqua\form;

use customtags\darealaqua\Main;
use customtags\darealaqua\utils\API;
use libs\FormAPI\SimpleForm;
use pocketmine\player\Player;

class MainTagsForm extends SimpleForm
{

    /**
     * @param Player $player
     * @return SimpleForm
     */
    public static function open(Player $player): SimpleForm
    {
        $form = new SimpleForm(function (Player $player, $data = NULL) {
            $result = $data;
            if ($result == null) {
                return;
            }
            $api = Main::getInstance()->getAPI();
            switch ($result) {
                case 0:
                    return;
                case 1:
                    $player->sendMessage(str_replace(["{prefix}", "{player}", "{tag}"], [$api->getPrefix(), $player->getName(), "\n"], $api->getMessages()->get("reset")));
                    $api->resetPlayerTag($player);
                    return;
                case 2:
                    $player->sendForm(AvailableTagsForm::open($player));
                    return;
                case 3:
                    $player->sendForm(ShopTagsForm::open($player));
                    return;
            }
        });
        $main = Main::getInstance();
        $api = $main->getAPI();
        $form->setTitle($api->getCfg()->getNested("menu-selector.title"));
        $form->setContent(str_replace(["{prefix}", "{player}", "{money}", "{tag}", "{line}"], [$api->getPrefix(), $player->getName(), number_format($main->getEconomyAPI()->myMoney($player)), $api->getPlayerTag($player, API::FORM_FORMAT), "\n"], $api->getCfg()->getNested("menu-selector.content")));
        $form->addButton(str_replace("{line}", "\n", $api->getCfg()->getNested("menu-selector.exit-button")));
        $form->addButton(str_replace("{line}", "\n", $api->getCfg()->getNested("menu-selector.reset-button")));
        $form->addButton(str_replace("{line}", "\n", $api->getCfg()->getNested("menu-selector.tags-button")));
        $form->addButton(str_replace("{line}", "\n", $api->getCfg()->getNested("menu-selector.shop-button")));
        $form->sendToPlayer($player);
        return $form;
    }
}
