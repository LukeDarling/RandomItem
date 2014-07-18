<?php
namespace LDX\RandomItem;
use pocketmine\scheduler\PluginTask;
use pocketmine\Server;
class Gift extends PluginTask {
  public function __construct($plugin) {
    $this->plugin = $plugin;
    parent::__construct($plugin);
  }
  public function onRun($ticks) {
    $this->plugin->giveAll();
  }
}
?>
