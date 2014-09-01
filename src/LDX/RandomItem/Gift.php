<?php
namespace LDX\RandomItem;
use pocketmine\scheduler\PluginTask;
use pocketmine\Server;
class Gift extends PluginTask {
  public function __construct($plugin) {
    $this->plugin = $plugin;
    $this->start = false;
    parent::__construct($plugin);
  }
  public function onRun($ticks) {
    if($this->start) {
      $this->plugin->giveAll();
    } else {
      $this->start = true;
    }
  }
}
?>
