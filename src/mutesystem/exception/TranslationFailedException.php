<?php

namespace mutesystem\exception;

use RuntimeException;

class TranslationFailedException extends RuntimeException {
    
    public function __construct(string $message = "", int $code = 0, Throwable $previous = null) {
        parent::__construct($message, $code, $previous);
    }
}
