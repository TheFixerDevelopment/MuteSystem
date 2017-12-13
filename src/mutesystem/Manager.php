<?php

namespace mutesystem;

use mutesystem\permission\BlockList;
use mutesystem\permission\MuteList;

class Manager {
    
    public static function getNameMutes() : MuteList {
        $muteList = new MuteList("muted-players.txt");
        $muteList->load();
        return $muteList;
    }
    
    public static function getIPMutes() : MuteList {
        $muteList = new MuteList("muted-ips.txt");
        $muteList->load();
        return $muteList;
    }
}
