<?php

namespace Lorisleiva\LaravelSearchString\Exceptions;

class InvalidSearchStringException extends \Exception
{
    protected $message;
    protected $step;
    protected $token;

    public function __construct($message = null, $step = 'Visitor', $token = null)
    {
        $this->message = $message;
        $this->step = $step;
        $this->token = $token;
        parent::__construct($this->__toString());
    }

    public static function fromLexer(?string $message = null, ?string $token = null)
    {
        return new static($message, 'Lexer', $token);
    }

    public static function fromParser(?string $message = null)
    {
        return new static($message, 'Parser');
    }

    public static function fromVisitor(?string $message = null)
    {
        return new static($message, 'Visitor');
    }

    public function getStep()
    {
        return $this->step;
    }

    public function getToken()
    {
        return $this->token;
    }

    public function __toString()
    {
        if ($this->message) {
            return $this->message;
        }

        return 'Invalid search string';
    }
}
