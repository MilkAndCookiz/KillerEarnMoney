<?php

namespace KillerEarnMoney;

use pocketmine\plugin\PluginBase;
use pocketmine\event\Listener;
use pocketmine\utils\TextFormat;
use pocketmine\event\player\PlayerDeathEvent;
use pocketmine\event\entity\EntityDamageByEntityEvent;
use pocketmine\Player;

//coded by @ImCookieGame

class Main extends PluginBase implements Listener{
    
    public $economy = false;

    public function onLoad() {
        $this->getLogger()->info(TextFormat::BLUE . "Loading KillerEarnMoney v1.0.0");
    }

    public function onEnable() {
        $this->config = $this->getConfig();
        $this->getServer()->getPluginManager()->registerEvents($this, $this);
        if(!file_exists($this->getDataFolder() . "config.yml")) {
            @mkdir($this->getDataFolder());
             file_put_contents($this->getDataFolder() . "config.yml",$this->getResource("config.yml"));
        }
        if($this->config->get("Economy-Plugin") == "Economy") {
            if(is_dir($this->getServer()->getPluginPath()."EconomyAPI")) {
		$this->getLogger()->info(TextFormat::GREEN."KillerEarnMoney v1.0.0 Enabled with Economy!");
		$this->economy = true;
            }else{
		$this->getLogger()->info(TextFormat::RED."KillerEarnMoney could not be loaded, I can't find the Economy plugin");
		$this->economy = false;
            }
        }
        elseif($this->config->get("Economy-Plugin") == "PocketMoney") {
            if(is_dir($this->getServer()->getPluginPath()."PocketMoney")) {
		$this->getLogger()->info(TextFormat::GREEN."KillerEarnMoney Enabled with PocketMoney!");
		$this->economy = true;
            }else{
		$this->getLogger()->info(TextFormat::RED."KillerEarnMoney could not be loaded, I can't find the PocketMoney plugin");
		$this->economy = false;
            }
        }
    }

    public function onDisable() {
        $this->getLogger()->info(TextFormat::GREEN . "KillerEarnMoney v1.0.0 Disabled!");
    }
    
    public function onDeath(PlayerDeathEvent $event) {
        $cause = $event->getEntity()->getLastDamageCause();
        if($cause instanceof EntityDamageByEntityEvent) {
            $player = $event->getEntity();
            $killer = $event->getEntity()->getLastDamageCause()->getDamager();
            if($killer instanceof Player) {
                $imessage = str_replace("@coins", $this->config->get("Money"), $this->config->get("Message"));
                $message = str_replace("@player", $player->getName(), $imessage);
                if($this->config->get("Economy-Plugin") == "Economy") {
                    $this->getServer()->getPluginManager()->getPlugin("EconomyAPI")->addMoney($killer->getName(), $this->config->get("Money"));
                    $killer->sendMessage($message);
                }
                elseif($this->config->get("Economy-Plugin") == "PocketMoney") {
                    $this->getServer()->getPluginManager()->getPlugin("PocketMoney")->grantMoney($killer->getName(), $this->config->get("Money"));
                    $killer->sendMessage($message);
                }
            }
        }
    }

}
