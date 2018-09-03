<?php

namespace MilkAndCookiz\KillerEarnMoney;

//Internal
use pocketmine\plugin\PluginBase;
use pocketmine\event\Listener;
use pocketmine\utils\TextFormat as TF;
use pocketmine\event\player\PlayerDeathEvent;
use pocketmine\event\entity\EntityDamageByEntityEvent;
use pocketmine\Player;

//External
use onebone\economyapi\EconomyAPI;

class Main extends PluginBase implements Listener{

    public function onLoad() {
        $this->getServer()->getLogger()->info(TF::BLUE . "Loading...");
    }

    public function onEnable() {
        $this->saveDefaultConfig();
        $this->getServer()->getPluginManager()->registerEvents($this, $this);
		$this->getServer()->getLogger()->info(TF::GREEN . "v1.0.1 Enabled!");
    }

    public function onDisable() {
        $this->getServer()->getLogger()->info(TF::RED . "v1.0.1 Disabled!");
    }
    
    public function onDeath(PlayerDeathEvent $event) {
        $cause = $event->getEntity()->getLastDamageCause();
		
        if($cause instanceof EntityDamageByEntityEvent) {
            $player = $event->getEntity();
            $killer = $cause->getDamager();
			
            if($killer instanceof Player) {
                $message1 = str_replace("@coins", $this->getConfig()->get("money"), $this->getConfig()->get("message"));
                $message2 = str_replace("@player", $killer->getName(), $message1);
				$killer->sendMessage($message2);
				EconomyAPI::getInstance()->addMoney($killer->getName(), $this->config->get("money"));
            }
        }
    }
}
