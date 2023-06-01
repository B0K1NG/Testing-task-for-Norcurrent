<?php 
declare(strict_types=1);
namespace Example\Exceptions;
use Exception;
use Throwable;

class PlayerNotFoundException extends Exception
{
    public function __construct(Throwable $previous = null) {
        if (!is_null($previous))
        {
            $this -> previous = $previous;
        }
        parent::__construct("Player not found!", 1, $previous);
    }

    public function __toString() {
        return __CLASS__ . ": [{$this->code}]: {$this->message}\n";
    }
}