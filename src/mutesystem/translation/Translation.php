<?php

namespace mutesystem\translation;

use mutesystem\exception\TranslationFailedException;
use InvalidArgumentException;
use pocketmine\command\Command;
use pocketmine\utils\TextFormat;

class Translation {
    
    public static function translate(string $translation) : string {
        switch ($translation) {
            case "noPermission":
                return TextFormat::RED . "Not showing command information due to self-leak issues.";
            case "playerNotFound":
                return TextFormat::GOLD . "Player is not online.";
            case "ipAlreadyMuted":
                return TextFormat::GOLD . "IP address is already muted.";
            case "playerAlreadyMuted":
                return TextFormat::GOLD . "Player is already muted.";
            case "playerNotMuted":
                return TextFormat::GOLD . "Player is not muted.";
            case "ipNotMuted":
                return TextFormat::GOLD . "IP address is not muted.";
            default:
                throw new TranslationFailedException("Failed to translate.");
        }
    }
    
    public static function translateParams(string $translation, array $parameters) : string {
        if (empty($parameters)) {
            throw new InvalidArgumentException("Parameter is empty.");
        }
        switch ($translation) {
            case "usage":
                $command = $parameters[0];
                if ($command instanceof Command) {
                    return TextFormat::DARK_GREEN . "Usage: " . TextFormat::GREEN . $command->getUsage();
                } else {
                    throw new InvalidArgumentException("Parameter index 0 must be the type of Command.");
                }
        }
    }
}
