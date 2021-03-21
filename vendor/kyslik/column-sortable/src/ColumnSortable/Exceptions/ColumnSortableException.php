<?php

namespace Kyslik\ColumnSortable\Exceptions;

use Exception;

class ColumnSortableException extends Exception
{

    public function __construct($message = '', $code = 0, Exception $previous = null)
    {
        switch ($code) {
            case 0:
                $message = 'Invalid sort argument.';
                break;
            case 1:
                $message = 'Relation \''.$message.'\' does not exist.';
                break;
            case 2:
                $message = 'Relation \''.$message.'\' is not instance of HasOne or BelongsTo.'; //hasMany
                break;
        }

        parent::__construct($message, $code, $previous);
    }
}
