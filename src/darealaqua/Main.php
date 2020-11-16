<?php

namespace darealaqua;

use darealaqua\command\TagCommand;
use darealaqua\task\TextTask;
use darealaqua\utils\Utils;
use pocketmine\Player;
use pocketmine\utils\Config;
use pocketmine\utils\TextFormat as C;
use pocketmine\plugin\PluginBase;
class Main extends PluginBase{

    public $config;
    public $messages;
    public $tag;
    public $economy;
    public static $instance;
    
    /**
     * @return Main
     */
    public static function getInstance(): Main {
        return self::$instance;
    }

    public function onEnable()
    {
        self::$instance = $this;
        $this->initResources();
        $this->initConfigs();
        $this->checks();
        $this->getLogger()->info(C::GREEN."Plugin Enabled!");
        $this->getServer()->getPluginManager()->registerEvents(new EventListener($this), $this);
        $this->getServer()->getCommandMap()->register("tagsystem", new TagCommand($this));
    }

    public function checks(){
        $this->economy = $this->getServer()->getPluginManager()->getPlugin("EconomyAPI");
        if($this->economy){
            $this->getLogger()->info(C::GREEN."EconomyAPI was found!");
        }else{
            $this->getLogger()->info(C::RED."EconomyAPI was not found!");
            $this->getServer()->getPluginManager()->disablePlugin($this);
        }
    }

    public function initResources()
    {
        $this->saveResource("config.yml");
        $this->saveResource("messages.yml");
        $this->saveResource("tag.yml");
    }

    public function initConfigs(){
        $this->config = new Config($this->getDataFolder() . "config.yml", Config::YAML);
        $this->config = $this->config->getAll();
        $this->messages = new Config($this->getDataFolder() . "messages.yml", Config::YAML);
        $this->tag = new Config($this->getDataFolder()."tag.yml", Config::YAML);
    }

    public function onDisable()
    {
        $this->getLogger()->info(C::RED."Plugin Disabled!");
    }
}
