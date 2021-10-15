<?php

namespace darealaqua\utils;

use darealaqua\Main;
use libs\FormAPI\SimpleForm;
use pocketmine\command\ConsoleCommandSender;
use pocketmine\level\sound\BlazeShootSound;
use pocketmine\level\sound\AnvilBreakSound;
use pocketmine\Player;

class Utils{

    /**
     * @param Player $player
     * @return mixed
     */
    public static function getPlayerTag(Player $player)
    {
        return Main::getInstance()->tag->getAll()[strtolower($player->getName())]["tag"];
    }
    
    /**
     * @param Player $player
     */
    public static function setPlayerTag(Player $player, $tag) {
        Main::getInstance()->tag->setNested(strtolower($player->getName()) . ".tag", $tag);
        Main::getInstance()->tag->save();
    }

    /**
     * @param Player $player
     * @return SimpleForm
     */
    public static function openTagSelectorForm(Player $player){
        $form = new SimpleForm(function (Player $player, $data = NULL) {
            $result = $data;
            if ($result == null) {
                return;
            }
            switch ($result) {
                case 0:
                    return;
                case 1:
                    Utils::openTagForm($player);
                    return;
                case 2:
                    Utils::openTagShopForm($player);
                   return;
            }
        });
        $form->setTitle(Main::getInstance()->config["menu-selector"]["title"]);
        $form->setContent(str_replace(["{money}", "{tag}", "{line}"], [number_format(Main::getInstance()->economy->myMoney($player)), Utils::getPlayerTag($player), "\n"], Main::getInstance()->config["menu-selector"]["content"]));
        $form->addButton(str_replace("{line}", "\n", Main::getInstance()->config["menu-selector"]["exit-button"]), 0);
        $form->addButton(str_replace("{line}", "\n", Main::getInstance()->config["menu-selector"]["tags-button"]));
        $form->addButton(str_replace("{line}", "\n", Main::getInstance()->config["menu-selector"]["shop-button"]));
        $form->sendToPlayer($player);
        return $form;
    }

    /**
     * @param Player $player
     * @return SimpleForm
     */
    public static function openTagForm(Player $player){
        $form = new SimpleForm(function (Player $player, $data = NULL){
            if($data !== NULL) {
                $tag = array_values(Main::getInstance()->config["tags"])[$data];
                if (!$player->hasPermission($tag["perm"])) {
                    $player->sendMessage(Main::getInstance()->messages->getNested("messages.no-perm"));
                } else {
                    Utils::setPlayerTag($player, $tag["name"]);
                    $player->sendMessage(str_replace(["{tag}", "{line}"], [$tag["name"], "\n"], Main::getInstance()->messages->getNested("messages.selected")));
                }
            }
        });
        $form->setTitle(Main::getInstance()->config["menu-tags"]["title"]);
        $form->setContent(str_replace(["{tag}", "{line}"], [Utils::getPlayerTag($player), "\n"], Main::getInstance()->config["menu-tags"]["content"]));
        foreach (Main::getInstance()->config["tags"] as $tag){
            if ($player->hasPermission($tag["perm"])) {
                $form->addButton(str_replace(["{tag}", "{line}"], [$tag["name"], "\n"], Main::getInstance()->config["menu-tags"]["unlocked-button"]));
            } else {
                $form->addButton(str_replace(["{tag}", "{line}"], [$tag["name"], "\n"], Main::getInstance()->config["menu-tags"]["locked-button"]));
            }
        }
        $form->sendToPlayer($player);
        return $form;
    }

    /**
     * @param Player $player
     * @return SimpleForm
     */
    public static function openTagShopForm(Player $player){
        $form = new SimpleForm(function (Player $player, $data = NULL){
            if($data !== NULL) {
                $tag = array_values(Main::getInstance()->config["tags"])[$data];
                $myMoney = Main::getInstance()->economy->myMoney($player);
                $cost = $tag["cost"];
                if ((Main::getInstance()->config["perm_for_all"] != "" && $player->hasPermission(Main::getInstance()->config["perm_for_all"])) || $player->hasPermission($tag["perm"])) {
                    $player->sendMessage(str_replace(["{player}", "{tag}"], [$player->getName(), $tag["name"]], Main::getInstance()->messages->getNested("messages.already")));
                }else if($myMoney >= $cost){
                    Main::getInstance()->economy->reduceMoney($player, $cost);
                    Main::getInstance()->getServer()->dispatchCommand(new ConsoleCommandSender(), str_replace(["{player}", "{permission}"], [$player->getName(), $tag["perm"]], Main::getInstance()->config["menu-shop"]["command"]));
                    $player->getLevel()->addSound(new AnvilBreakSound($player));
                    $player->sendMessage(str_replace(["{player}", "{tag}"], [$player->getName(), $tag["name"]], Main::getInstance()->messages->getNested("messages.bought")));
                    return true;
                } else {
                    $player->sendMessage(str_replace(["{player}", "{tag}"], [$player->getName(), $tag["name"]], Main::getInstance()->messages->getNested("messages.no-money")));
                    $player->getLevel()->addSound(new BlazeShootSound($player));
                }

            }
            return true;
        });
        $form->setTitle(Main::getInstance()->config["menu-shop"]["title"]);
        $form->setContent(str_replace(["{money}", "{tag}", "{line}"], [number_format(Main::getInstance()->economy->myMoney($player)), Utils::getPlayerTag($player), "\n"], Main::getInstance()->config["menu-shop"]["content"]));
        foreach (Main::getInstance()->config["tags"] as $tag) {
            $form->addButton(str_replace(["{cost}", "{tag}", "{line}"], [number_format($tag["cost"]), $tag["name"], "\n"], Main::getInstance()->config["menu-shop"]["button"]));
        }
        $form->sendToPlayer($player);
        return $form;
    }
}
