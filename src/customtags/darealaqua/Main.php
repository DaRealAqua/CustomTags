<?php

/*
  ____        ____            _    _
 |  _ \  __ _|  _ \ ___  __ _| |  / \   __ _ _   _  __ _
 | | | |/ _` | |_) / _ \/ _` | | / _ \ / _` | | | |/ _` |
 | |_| | (_| |  _ <  __/ (_| | |/ ___ \ (_| | |_| | (_| |
 |____/ \__,_|_| \_\___|\__,_|_/_/   \_\__, |\__,_|\__,_|
                                          |_|
*/

declare(strict_types=1);

namespace customtags\darealaqua;

use customtags\darealaqua\command\TagCommand;
use customtags\darealaqua\utils\API;
use pocketmine\utils\SingletonTrait;
use pocketmine\utils\TextFormat as C;
use pocketmine\plugin\PluginBase;

class Main extends PluginBase
{

    /** SingletonTrait */
    use SingletonTrait;

    /** @var API */
    private $api;

    private $economy;

    /** @var string[]  */
    public const DEPENCIES = [
        "ECONOMY_PLUGIN" => "EconomyAPI"
    ];

    /**
     * @return void
     */
    public function onEnable(): void
    {
        self::setInstance($this);
        $this->api = new API($this);
        $this->getServer()->getPluginManager()->registerEvents(new EventListener($this), $this);
        $this->getServer()->getCommandMap()->register($this->getName(), new TagCommand());
        $this->depencies();
    }

    /**
     * @return void
     */
    public function depencies(): void
    {
        $this->economy = $this->getServer()->getPluginManager()->getPlugin($depency = self::DEPENCIES["ECONOMY_PLUGIN"]);
        if(!$this->economy){
            $this->getLogger()->info(C::RED . $depency . " could not be found, so the plugin could not be enabled!");
            $this->getServer()->getPluginManager()->disablePlugin($this);
        }
    }

    /**
     * @return API
     */
    public function getAPI(): API
    {
        return $this->api;
    }

    /**
     * @return mixed
     */
    public function getEconomyAPI()
    {
        return $this->economy;
    }
}
