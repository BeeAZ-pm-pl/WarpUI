<?php

namespace BeeAZZ\WarpUI;

use BeeAZZ\WarpUI\libs\SimpleForm;
use pocketmine\plugin\PluginBase;
use pocketmine\event\Listener;
use pocketmine\command\{Command, CommandSender};
use pocketmine\player\Player;
use pocketmine\Server;
use pocketmine\utils\Config;
use pocketmine\world\World;
use pocketmine\world\Position;

class Main extends PluginBase implements Listener{
  
  public $warp;
  public $i;
  
  public function onEnable(): void{
    $this->saveDefaultConfig();
    $this->getServer()->getPluginManager()->registerEvents($this, $this);
    $this->saveResource("warp.yml");
    $this->warp = new Config($this->getDataFolder(). "warp.yml",Config::YAML);
  }
  public function onCommand(CommandSender $sender, Command $command, string $label, array $args): bool{
   switch($command->getName()){
    case "warpui":
     if($sender instanceof Player){
      $this->warpui($sender);
     }else{
      $sender->sendMessage("Please Use Command Ingame");
        }
      break;
     }
    return true;
   }
  
  public function warpui(Player $sender){
   $form = new SimpleForm(function(Player $sender, $data){
   if($data == null){
     return true;
   }
   $x = $this->warp->get(strtolower($data))["x"];
   $y = $this->warp->get(strtolower($data))["y"];
   $z = $this->warp->get(strtolower($data))["z"];
   $world = $this->warp->get($data)["world"];
   $sender->sendMessage($this->warp->get($data)["msg"]);
   $this->getServer()->getWorldManager()->loadWorld($world);
   $sender->teleport(new Position(floatval($x), floatval($y), floatval($z), $this->getServer()->getWorldManager()->getWorldByName($world)));
   });
     for($i = 0;$i <= 20;$i++){
    if($this->warp->exists($i)){
     $form->addButton($this->warp->get(strtolower($i))["name"],0, "textures/ui/icon_trailer");
     }
    }
     $form->setTitle($this->getConfig()->get("Title"));
     $form->setContent($this->getConfig()->get("Description"));
     $form->sendToPlayer($sender);
}
}
