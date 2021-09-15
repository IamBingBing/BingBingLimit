<?php
namespace bing;

use pocketmine\plugin\PluginBase;
use pocketmine\event\Listener;
use pocketmine\event\entity\EntityTeleportEvent;
use pocketmine\Player;
use pocketmine\utils\Config;
use pocketmine\event\entity\EntitySpawnEvent;

class BingBingLimit extends PluginBase implements Listener
{

    public function onEnable()
    {
        @mkdir($this->getDataFolder());
        $this->getServer()
            ->getPluginManager()
            ->registerEvents($this, $this);
        $this->d = new Config($this->getDataFolder() . 'setting.yml', Config::YAML, [
            'world' => []
        ]);
        $this->dd = $this->d->getAll();
    }

    public function teleport(EntityTeleportEvent $event)
    {
        $en = $event->getEntity();
        if ($en instanceof Player) {
            foreach ($this->dd as $b => $p) {
                if ($event->getTo()
                    ->getLevel()
                    ->getName() == $b) {
                    if ($en->getName() !== $p) {
                        $event->setTo($event->getFrom());
                        $en->sendMessage('접근 권한이 없습니다');
                    }
                }
            }
        }
    }

    public function spawn(EntitySpawnEvent $event)
    {
        $en = $event->getEntity();
        if ($en instanceof Player) {
            foreach ($this->dd as $b => $p) {
                if ($event->getLevel()->getName() == $b) {
                    if ($en->getName() !== $p) {
                        $en->teleport($this->getServer()
                            ->getLevelByName("world"));
                        $en->sendMessage('접근 권한이 없습니다');
                    }
                }
            }
        }
    }
}