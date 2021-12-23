<?php

declare(strict_types=1);

namespace customtags\darealaqua\command;

use customtags\darealaqua\form\MainTagsForm;
use customtags\darealaqua\Main;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\player\Player;

class TagCommand extends Command
{

    /**
     * TagCommand constructor.
     */
    public function __construct()
    {
        parent::__construct("tag", "Tags System", "", ["tags"]);
    }

    /**
     * @param CommandSender $sender
     * @param string $commandLabel
     * @param array $args
     * @return void
     */
    public function execute(CommandSender $sender, string $commandLabel, array $args): void
    {
        $api = Main::getInstance()->getAPI();
        if (!$sender instanceof Player) {
            $sender->sendMessage($api->getPrefix() . "You can use this command only in-game!");
        } else {
            $sender->sendForm(MainTagsForm::open($sender));
        }
    }
}
