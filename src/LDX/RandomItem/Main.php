<?php
namespace LDX\RandomItem;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\Player;
use pocketmine\plugin\PluginBase;
use pocketmine\Server;
use pocketmine\utils\TextFormat;
use pocketmine\item\Item;
class Main extends PluginBase {
  public function onEnable() {
    if(!file_exists($this->getDataFolder() . "config.yml")) {
      @mkdir($this->getDataFolder());
      file_put_contents($this->getDataFolder() . "config.yml",$this->getResource("config.yml"));
    }
    $c = yaml_parse(file_get_contents($this->getDataFolder() . "config.yml"));
    $t = $c["interval"] * 1200;
    $num = 0;
    foreach ($c["items"] as $i) {
      $r = explode(":",$i);
      $this->itemdata[$num] = array("id" => $r[0],"meta" => $r[1],"amount" => $r[2]);
      $num++;
    }
    $this->getServer()->getScheduler()->scheduleRepeatingTask(new Gift($this), $t);
  }
  public function onCommand(CommandSender $issuer,Command $cmd,$label,array $args) {
    if((strtolower($cmd->getName()) == "gift") && isset($args[0])) {
      if($this->getServer()->getPlayer($args[0]) instanceof Player) {
        $d = $this->generateData();
        $p = $this->getServer()->getPlayer($args[0]);
        $this->give($p,$d);
        $p->sendMessage("Random item given! (" . $data["id"] . ":" . $data["meta"] . ")");
      } else {
        $issuer->sendMessage("Player not connected.");
      }
      return true;
    } else if((strtolower($cmd->getName()) == "gift") && !(isset($args[0]))) {
      $this->giveAll();
      return true;
    } else {
      return false;
    }
  }
  public function give($p,$data) {
    $item = new Item($data["id"],$data["meta"],$data["amount"]);
    $p->getInventory()->addItem($item);
  }
  public function giveAll() {
    $data = $this->generateData();
    $this->broadcast("Random item given! (" . $data["id"] . ":" . $data["meta"] . ")");
    foreach($this->getServer()->getOnlinePlayers() as $p) {
      $this->give($p,$data);
    }
  }
  public function broadcast($m) {
    foreach($this->getServer()->getOnlinePlayers() as $p) {
      $p->sendMessage($m);
    }
    $this->getLogger()->info(TextFormat::YELLOW . $m);
  }
  public function generateData() {
    return $this->itemdata[rand(0,(count($this->itemdata) - 1))];
  }
}

