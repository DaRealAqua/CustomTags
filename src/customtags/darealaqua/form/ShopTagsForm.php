<?php

declare(strict_types=1);

namespace customtags\darealaqua\form;

use customtags\darealaqua\Main;
use customtags\darealaqua\utils\API;
use libs\FormAPI\SimpleForm;
use pocketmine\console\ConsoleCommandSender;
use pocketmine\player\Player;
use pocketmine\Server;
use pocketmine\world\sound\AnvilBreakSound;
use pocketmine\world\sound\BlazeShootSound;

class ShopTagsForm extends SimpleForm
{

    /**
     * @param Player $player
     * @return SimpleForm
     */
    public static function open(Player $player): SimpleForm
    {
        $form = new SimpleForm(function (Player $player, $data = null){
            $main = Main::getInstance();
            $api = $main->getAPI();
            if($data !== null) {
                $tag = array_values($api->getTags()->getAll()["tags"])[$data];
                $myMoney = $main->getEconomyAPI()->myMoney($player);
                $cost = $tag["cost"];
                if ($player->hasPermission((string)$tag["perm"])) {
                    $player->sendMessage(str_replace([
                        "{prefix}", "{player}", "{tag}", "{line}"], [
                        $api->getPrefix(), $player->getName(), (string)$tag["name"], "\n"], $api->getMessages()->get("already")
                    ));
                }else if($myMoney >= $cost){
                    $main->getEconomyAPI()->reduceMoney($player, $cost);
                    Main::getInstance()->getServer()->dispatchCommand(new ConsoleCommandSender($server = Server::getInstance(), $server->getLanguage()), str_replace(["{player}", "{permission}"], [$player->getName(), $tag["perm"]], $api->getCfg()->getNested("menu-shop.command")));
                    $player->getWorld()->addSound($player->getPosition(), new AnvilBreakSound());
                    $player->sendMessage(str_replace([
                        "{prefix}", "{player}", "{tag}", "{line}"], [
                        $api->getPrefix(), $player->getName(), (string)$tag["name"], "\n"], $api->getMessages()->get("bought")
                    ));
                    return true;
                } else {
                    $player->sendMessage(str_replace([
                        "{prefix}", "{player}", "{tag}", "{line}"], [
                        $api->getPrefix(), $player->getName(), (string)$tag["name"], "\n"], $api->getMessages()->get("no-money")
                    ));
                    $player->getWorld()->addSound($player->getPosition(), new BlazeShootSound());
                }
            }
            return true;
        });
        $main = Main::getInstance();
        $api = $main->getAPI();
        $form->setTitle($api->getCfg()->getNested("menu-shop.title"));
        $form->setContent(str_replace(["{prefix}", "{player}", "{money}", "{tag}", "{line}"], [$api->getPrefix(), $player->getName(), number_format($main->getEconomyAPI()->myMoney($player)), $api->getPlayerTag($player, API::FORM_FORMAT), "\n"], $api->getCfg()->getNested("menu-shop.content")));
        foreach ($api->getTags()->getAll()["tags"] as $tag) {
            $form->addButton(str_replace(["{cost}", "{tag}", "{line}"], [number_format($tag["cost"]), $tag["name"], "\n"], $api->getCfg()->getNested("menu-shop.button")));
        }
        return $form;
    }
}
