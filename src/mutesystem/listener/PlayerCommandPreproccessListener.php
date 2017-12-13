<?php

namespace mutesystem\listener;

use mutesystem\Manager;
use mutesystem\util\date\Countdown;
use DateTime;
use pocketmine\event\Listener;
use pocketmine\event\player\PlayerCommandPreprocessEvent;
use pocketmine\utils\TextFormat;

class PlayerCommandPreproccessListener implements Listener {
    
    public function onPlayerCommandPreproccess(PlayerCommandPreprocessEvent $event) {
        $player = $event->getPlayer();
        $muteList = Manager::getNameMutes();
        $str = str_split($event->getMessage());
        if ($str[0] != "/") {
            return true;
        }
        if ($muteList->isMuted($player->getName())) {
            $muteMessage = "";
            $entries = $muteList->getEntries();
            $entry = $entries[strtolower($player->getName())];
            if ($entry->getExpires() == null) {
                $reason = $entry->getReason();
                if ($reason != null || $reason != "") {
                    $muteMessage = TextFormat::RED . "You're currently Muted for " . TextFormat::AQUA . $reason . TextFormat::RED . ".";
                } else {
                    $muteMessage = TextFormat::RED . "You're currently Muted.";
                }
            } else {
                $expiry = Countdown::expirationTimerToString($entry->getExpires(), new DateTime());
                if ($entry->hasExpired()) {
                    $muteList->remove($entry->getName());
                    return true;
                }
                $muteReason = $entry->getReason();
                if ($muteReason != null || $muteReason != "") {
                    $muteReason = TextFormat::RED . "You're currently muted for " . TextFormat::AQUA . $muteReason . TextFormat::RED . " until " . TextFormat::AQUA . $expiry . TextFormat::RED . ".";
                } else {
                    $muteReason = TextFormat::RED . "You're currently muted until " . TextFormat::AQUA . $expiry . TextFormat::RED . ".";
                }
            }
            $event->setCancelled(true);
            $player->sendMessage($muteMessage);
        }
    }
    
    public function onPlayerCommandPreproccess2(PlayerCommandPreprocessEvent $event) {
        $player = $event->getPlayer();
        $muteList = Manager::getIPMutes();
        $str = str_split($event->getMessage());
        if ($str[0] != "/") {
            return true;
        }
        if ($muteList->isMuted($player->getAddress())) {
            $muteMessage = "";
            $entries = $muteList->getEntries();
            $entry = $entries[strtolower($player->getAddress())];
            if ($entry->getExpires() == null) {
                $reason = $entry->getReason();
                if ($reason != null || $reason != "") {
                    $muteMessage = TextFormat::RED . "You're currently IP muted for " . TextFormat::AQUA . $reason . TextFormat::RED . ".";
                } else {
                    $muteMessage = TextFormat::RED . "You're currently IP muted.";
                }
            } else {
                $expiry = Countdown::expirationTimerToString($entry->getExpires(), new DateTime());
                if ($entry->hasExpired()) {
                    $muteList->remove($entry->getName());
                    return true;
                }
                $blockReason = $entry->getReason();
                if ($muteReason != null || $muteReason != "") {
                    $muteReason = TextFormat::RED . "You're currently IP Muted for " . TextFormat::AQUA . $muteReason . TextFormat::RED . " until " . TextFormat::AQUA . $expiry . TextFormat::RED . ".";
                } else {
                    $muteReason = TextFormat::RED . "You're currently IP Mured until " . TextFormat::AQUA . $expiry . TextFormat::RED . ".";
                }
            }
            $event->setCancelled(true);
            $player->sendMessage($muteMessage);
        }
    }
}
