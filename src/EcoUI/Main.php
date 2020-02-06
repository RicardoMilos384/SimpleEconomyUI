<?php
/** Plugin By DontTouchMeXD (RicardoMilos384)
 * Don't Edit This Plugin
 * _____________________
 * XD     XD  XDXDXDX
 *  XD   XD   XD    XD
 *   XD-XD    XD     XD
 *  XD   XD   XD    XD
 * XD     XD  XDXDXDX
 * —————————————————————
 * Copyright © by RicardoMilos384
 * Github: github.com\RicardoMilos384
 * Ok Thanks For Your Respect
 */

namespace EcoUI;

use pocketmine\plugin\PluginBase;
use pocketmine\Player; 
use pocketmine\Server;

use pocketmine\event\Listener;
use pocketmine\command\Command;
use pocketmine\utils\Config;
use pocketmine\command\CommandSender;

use jojoe77777\FormApi;
use onebone\economyapi\EconomyAPI;

class Main extends PluginBase implements Listener {
	
	public $plugin;

	public function onEnable(){
		$this->getLogger()->info("§aEconomyUI §bby §cDontTouchMeXD ");
		$this->getLogger()->info("Copyright © by RicardoMilos384
Github: github.com\RicardoMilos 
Ok Thanks For Your Respect ");
		$this->getServer()->getPluginManager()->registerEvents($this, $this);
		$this->eco = $this->getServer()->getPluginManager()->getPlugin("EconomyAPI");
	}
	
	public function onCommand(CommandSender $sender, Command $command, String $label, array $args) : bool {
        switch($command->getName()){
            case "ecoui":
            $this->FormEco($sender);
            return true;
        }
        return true;
	}
	
	 public function FormEco($sender){
        $formapi = $this->getServer()->getPluginManager()->getPlugin("FormAPI");
        $form = $formapi->createSimpleForm(function(Player $sender, $data){
          $result = $data;
          if($result === null){
          }
          switch($result){
              case 0:
              break;
              case 1:
			  $this->MyMoney($sender);
              break;
              case 2:
              $command = "topmoney";
              $this->getServer()->getCommandMap()->dispatch($sender,$command);
              break;
              case 3:
              $this->Pay($sender);
              break;
              case 4:
              $this->Poeple($sender);
              break;
          }
        });
        $config = $this->getConfig();
        $name = $sender->getName();
        $form->setTitle("§a§lEconomyUI");
        $form->setContent("§7Hello §c{$name} §7can i help you?");
        $form->addButton("§cExit\n§ftap to close");
		$form->addButton("§aMyMoney\n§fsee your money");
		$form->addButton("§aTopMoney\n§ftop richest players");
		$form->addButton("§aPay Player\n§fpay player");
        $form->addButton("§aPlayer Money\n§fsee money of other players");
        $form->sendToPlayer($sender);
	}
	
	public function MyMoney($player){
		$formapi = $this->getServer()->getPluginManager()->getPlugin("FormAPI");
		$form = $formapi->createSimpleForm(function(Player $player, $data){
			$result = $data[0];
			if($result === null){
			}
			switch($result){
			    case 0:
			    $this->FormEco($player);
			    break;
			}
		});
		$money = $this->eco->myMoney($player);
		$name = $player->getName();
		$form->setTitle("§a§lEconomyUI");
		$form->setContent("§cHello §a{$name}\n§eYour Money:\n§b{$money}\n\n\n\n\n");
		$form->addButton("§ckembali");
		$form->sendToPlayer($player);
	}
	
	public function Pay($player){
		$formapi = $this->getServer()->getPluginManager()->getPlugin("FormAPI");
		$form = $formapi->createCustomForm(function(Player $player, $result){
		    if($result === null){
            return;
            }
            if(trim($result[0]) === ""){
            $player->sendMessage("§cplease enter the player's name");
            return;
            }
            if(trim($result[1]) === ""){
            $player->sendMessage("§cplease enter the amount of money");
            return;
            }
            $this->getServer()->getCommandMap()->dispatch($player, "pay ".$result[0]." ".$result[1]);
		});
		$form->setTitle("§a§lEconomyUI");
		$form->addInput("§aEnter player's name:", "DontTouchMeXD");
		$form->addInput("§aEnter amount of money:", "10000");
		$form->sendToPlayer($player);
	}

	public function Poeple($player){
		$api = $this->getServer()->getPluginManager()->getPlugin("FormAPI");
		$form = $api->createCustomForm(function(Player $player, ?array $data){
			if(!isset($data)) return;
			if($this->getServer()->getOfflinePlayer($data[0])->hasPlayedBefore() || $this->getServer()->getOfflinePlayer($data[0])->isOnline() && EconomyAPI::getInstance()->myMoney($data[0]) !== null){
				$this->MoneyPlayerForm($player, $data[0]);
			}else{
				$player->sendMessage("§cPlayer Tidak Ditemukan");
			}
		});
		$form->setTitle("§a§lEconomyUI");
		$form->addInput("§aEnter player's name:", "DontTouchMeXD");
		$form->sendToPlayer($player);
	}

	public function MoneyPlayerForm(Player $player, string $target){
		$api = $this->getServer()->getPluginManager()->getPlugin("FormAPI");
		$form = $api->createCustomForm(function(Player $player, ?array $data){
		    
		});
		$uang = $this->eco->myMoney($target);
		$form->setTitle("§l§aEconomyUI");
		$form->addLabel("§bThe Money He Has:§c\n{$uang}$ \n\n\n\n\n\n");
		$form->sendToPlayer($player);
	}
}
