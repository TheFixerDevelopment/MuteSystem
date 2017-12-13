<?php

namespace mutesystem;

use mutesystem\TempMuteCommand;
use mutesystem\TempMuteIPCommand;
use mutesystem\UnMuteCommand;
use mutesystem\UnMuteIPCommand;
use mutesystem\MuteCommand;
use mutesystem\MuteIPCommand;
use mutesystem\MuteListCommand;
use pocketmine\event\Listener;
use pocketmine\permission\Permission;
use pocketmine\plugin\Plugin;
use pocketmine\plugin\PluginBase;


class Main extends PluginBase {
    private function removeCommand(string $command) {
        $commandMap = $this->getServer()->getCommandMap();
        $cmd = $commandMap->getCommand($command);
        if ($cmd == null) {
            return;
        }
        $cmd->setLabel("");
        $cmd->unregister($commandMap);
    }
    
    private function initializeCommands() {
        $commands = array("tempmute", "tempmuteip", "unmute", "unmuteip", "mute", "muteip");
        for ($i = 0; $i < count($commands); $i++) {
            $this->removeCommand($commands[$i]);
        }
        $commandMap = $this->getServer()->getCommandMap();
        $commandMap->registerAll("mutesystem", array(
            new TempMuteCommand(),
            new TempMuteIPCommand(),
            new UnMuteCommand(),
            new UnMuteIPCommand(),
            new MuteCommand(),
            new MuteIPCommand(),
            new MuteListCommand(),
        ));
    }
    
    /**
     * @param Permission[] $permissions
     */
    protected function addPermissions(array $permissions) {
        foreach ($permissions as $permission) {
            $this->getServer()->getPluginManager()->addPermission($permission);
        }
    }
    
    /**
     * 
     * @param Plugin $plugin
     * @param Listener[] $listeners
     */
    protected function registerListeners(Plugin $plugin, array $listeners) {
        foreach ($listeners as $listener) {
            $this->getServer()->getPluginManager()->registerEvents($listener, $plugin);
        }
    }
    
    private function initializeListeners() {
        $this->registerListeners($this, array(
            new PlayerChatListener(),
        ));
    }
    
    private function initializeFiles() {
        @mkdir($this->getDataFolder());
        if (!(file_exists("muted-players.txt") && is_file("muted-players.txt"))) {
            @fopen("muted-players.txt", "w+");
        }
        if (!(file_exists("muted-ips.txt") && is_file("muted-ips.txt"))) {
            @fopen("muted-ips.txt", "w+");
        }
    }
    
    private function initializePermissions() {
        $this->addPermissions(array(
            new Permission("mutesystem.command.tempmute", "Allows the player to tempmute a user.", Permission::DEFAULT_OP),
            new Permission("mutesystem.command.tempmuteip", "Allows the player to tempmute a player via ip.", Permission::DEFAULT_OP),
            new Permission("mutesystem.command.unmute", "Allows the player to Unmute a user.", Permission::DEFAULT_OP),
            new Permission("mutesystem.command.unmuteip", "Allows the player to unmute a player via IP."),
            new Permission("mutesystem.command.mute", "Allows the player to Mute a user.", Permission::DEFAULT_OP),
            new Permission("mutesystem.command.muteip", "Allows the player to prevent the given player from sending public chat message.", Permission::DEFAULT_OP),
            new Permission("mutesystem.command.mutelist", "Allows to see a list of muted players.", Permission::DEFAULT_OP),
        ));
    }
    
    private function removeMuteExpired() {
        Manager::getNameMutes()->removeExpired();
        Manager::getIPMutes()->removeExpired();
    }
    
    public function onLoad() {
        $this->getLogger()->info("MuteSystem is now loading... Please wait for completion.");
    }
    
    public function onEnable() {
        $this->getLogger()->info("MuteSystem has now been enabled succesfully. As far as we know, there's no errors on-enable.");
        $this->initializeCommands();
        $this->initializeListeners();
        $this->initializePermissions();
        $this->initializeFiles();
        $this->removeBanExpired();
    }
    
    public function onDisable() {
        $this->getLogger()->info("MuteSystem is now disabled. Did the server stop?");
    }
}

