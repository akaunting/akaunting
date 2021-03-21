<?php

declare(strict_types=1);

namespace NunoMaduro\Larastan\Properties;

use PhpParser;
use PhpParser\NodeFinder;

/**
 * @see https://github.com/psalm/laravel-psalm-plugin/blob/master/src/SchemaAggregator.php
 */
final class SchemaAggregator
{
    /** @var array<string, SchemaTable> */
    public $tables = [];

    /**
     * @param array<int, PhpParser\Node\Stmt> $stmts
     */
    public function addStatements(array $stmts): void
    {
        $nodeFinder = new NodeFinder();

        /** @var PhpParser\Node\Stmt\Class_[] $classes */
        $classes = $nodeFinder->findInstanceOf($stmts, PhpParser\Node\Stmt\Class_::class);

        foreach ($classes as $stmt) {
            $this->addClassStatements($stmt->stmts);
        }
    }

    /**
     * @param array<int, PhpParser\Node\Stmt> $stmts
     */
    private function addClassStatements(array $stmts): void
    {
        foreach ($stmts as $stmt) {
            if ($stmt instanceof PhpParser\Node\Stmt\ClassMethod
                && $stmt->name->name !== 'down'
                && $stmt->stmts
            ) {
                $this->addUpMethodStatements($stmt->stmts);
            }
        }
    }

    /**
     * @param PhpParser\Node\Stmt[] $stmts
     */
    private function addUpMethodStatements(array $stmts): void
    {
        $nodeFinder = new NodeFinder();
        $methods = $nodeFinder->findInstanceOf($stmts, PhpParser\Node\Stmt\Expression::class);

        foreach ($methods as $stmt) {
            if ($stmt instanceof PhpParser\Node\Stmt\Expression
                && $stmt->expr instanceof PhpParser\Node\Expr\StaticCall
                && ($stmt->expr->class instanceof PhpParser\Node\Name)
                && $stmt->expr->name instanceof PhpParser\Node\Identifier
                && ($stmt->expr->class->toCodeString() === '\Illuminate\Support\Facades\Schema' || $stmt->expr->class->toCodeString() === '\Schema')
            ) {
                switch ($stmt->expr->name->name) {
                    case 'create':
                        $this->alterTable($stmt->expr, true);
                        break;

                    case 'table':
                        $this->alterTable($stmt->expr, false);
                        break;

                    case 'drop':
                    case 'dropIfExists':
                        $this->dropTable($stmt->expr);
                        break;

                    case 'rename':
                        $this->renameTable($stmt->expr);
                }
            }
        }
    }

    private function alterTable(PhpParser\Node\Expr\StaticCall $call, bool $creating): void
    {
        if (! isset($call->args[0])
            || ! $call->args[0]->value instanceof PhpParser\Node\Scalar\String_
        ) {
            return;
        }

        $tableName = $call->args[0]->value->value;

        if ($creating) {
            $this->tables[$tableName] = new SchemaTable($tableName);
        }

        if (! isset($call->args[1])
            || ! $call->args[1]->value instanceof PhpParser\Node\Expr\Closure
            || count($call->args[1]->value->params) < 1
            || ($call->args[1]->value->params[0]->type instanceof PhpParser\Node\Name
                && $call->args[1]->value->params[0]->type->toCodeString()
                !== '\\Illuminate\Database\Schema\Blueprint')
        ) {
            return;
        }

        $updateClosure = $call->args[1]->value;

        if ($call->args[1]->value->params[0]->var instanceof PhpParser\Node\Expr\Variable
            && is_string($call->args[1]->value->params[0]->var->name)
        ) {
            $argName = $call->args[1]->value->params[0]->var->name;

            $this->processColumnUpdates($tableName, $argName, $updateClosure->stmts);
        }
    }

    /**
     * @param string                $tableName
     * @param string                $argName
     * @param PhpParser\Node\Stmt[] $stmts
     *
     * @throws \Exception
     */
    private function processColumnUpdates(string $tableName, string $argName, array $stmts): void
    {
        if (! isset($this->tables[$tableName])) {
            return;
        }

        $table = $this->tables[$tableName];

        foreach ($stmts as $stmt) {
            if ($stmt instanceof PhpParser\Node\Stmt\Expression
                && $stmt->expr instanceof PhpParser\Node\Expr\MethodCall
                && $stmt->expr->name instanceof PhpParser\Node\Identifier
            ) {
                $rootVar = $stmt->expr;

                $firstMethodCall = $rootVar;

                $nullable = false;

                while ($rootVar instanceof PhpParser\Node\Expr\MethodCall) {
                    if ($rootVar->name instanceof PhpParser\Node\Identifier
                        && $rootVar->name->name === 'nullable'
                    ) {
                        $nullable = true;
                    }

                    $firstMethodCall = $rootVar;
                    $rootVar = $rootVar->var;
                }

                if ($rootVar instanceof PhpParser\Node\Expr\Variable
                    && $rootVar->name === $argName
                    && $firstMethodCall->name instanceof PhpParser\Node\Identifier
                ) {
                    $firstArg = $firstMethodCall->args[0]->value ?? null;
                    $secondArg = $firstMethodCall->args[1]->value ?? null;

                    if (! $firstArg instanceof PhpParser\Node\Scalar\String_) {
                        if ($firstMethodCall->name->name === 'timestamps'
                            || $firstMethodCall->name->name === 'timestampsTz'
                            || $firstMethodCall->name->name === 'nullableTimestamps'
                            || $firstMethodCall->name->name === 'nullableTimestampsTz'
                            || $firstMethodCall->name->name === 'rememberToken'
                        ) {
                            switch (strtolower($firstMethodCall->name->name)) {
                                case 'droptimestamps':
                                case 'droptimestampstz':
                                    $table->dropColumn('created_at');
                                    $table->dropColumn('updated_at');
                                    break;

                                case 'remembertoken':
                                    $table->setColumn(new SchemaColumn('remember_token', 'string', $nullable));
                                    break;

                                case 'dropremembertoken':
                                    $table->dropColumn('remember_token');
                                    break;

                                case 'timestamps':
                                case 'timestampstz':
                                case 'nullabletimestamps':
                                    $table->setColumn(new SchemaColumn('created_at', 'string', true));
                                    $table->setColumn(new SchemaColumn('updated_at', 'string', true));
                                    break;
                            }

                            continue;
                        }

                        if ($firstMethodCall->name->name === 'softDeletes'
                            || $firstMethodCall->name->name === 'softDeletesTz'
                            || $firstMethodCall->name->name === 'dropSoftDeletes'
                            || $firstMethodCall->name->name === 'dropSoftDeletesTz'
                        ) {
                            $columnName = 'deleted_at';
                        } else {
                            continue;
                        }
                    } else {
                        $columnName = $firstArg->value;
                    }

                    $secondArgArray = null;

                    if ($secondArg instanceof PhpParser\Node\Expr\Array_) {
                        $secondArgArray = [];

                        foreach ($secondArg->items as $array_item) {
                            if ($array_item !== null && $array_item->value instanceof PhpParser\Node\Scalar\String_) {
                                $secondArgArray[] = $array_item->value->value;
                            }
                        }
                    }

                    switch (strtolower($firstMethodCall->name->name)) {
                        case 'biginteger':
                        case 'increments':
                        case 'integer':
                        case 'integerincrements':
                        case 'mediumincrements':
                        case 'mediuminteger':
                        case 'smallincrements':
                        case 'smallinteger':
                        case 'tinyincrements':
                        case 'tinyinteger':
                        case 'unsignedbiginteger':
                        case 'unsignedinteger':
                        case 'unsignedmediuminteger':
                        case 'unsignedsmallinteger':
                        case 'unsignedtinyinteger':
                        case 'bigincrements':
                            $table->setColumn(new SchemaColumn($columnName, 'int', $nullable));
                            break;

                        case 'char':
                        case 'datetimetz':
                        case 'date':
                        case 'datetime':
                        case 'ipaddress':
                        case 'json':
                        case 'jsonb':
                        case 'linestring':
                        case 'longtext':
                        case 'macaddress':
                        case 'mediumtext':
                        case 'multilinestring':
                        case 'string':
                        case 'text':
                        case 'time':
                        case 'timestamp':
                        case 'uuid':
                        case 'binary':
                            $table->setColumn(new SchemaColumn($columnName, 'string', $nullable));
                            break;

                        case 'boolean':
                            $table->setColumn(new SchemaColumn($columnName, 'bool', $nullable));
                            break;

                        case 'geometry':
                        case 'geometrycollection':
                        case 'multipoint':
                        case 'multipolygon':
                        case 'multipolygonz':
                        case 'point':
                        case 'polygon':
                        case 'computed':
                            $table->setColumn(new SchemaColumn($columnName, 'mixed', $nullable));
                            break;

                        case 'double':
                        case 'float':
                        case 'unsigneddecimal':
                        case 'decimal':
                            $table->setColumn(new SchemaColumn($columnName, 'float', $nullable));
                            break;

                        case 'dropcolumn':
                        case 'dropifexists':
                        case 'dropsoftdeletes':
                        case 'dropsoftdeletestz':
                        case 'removecolumn':
                        case 'drop':
                            $table->dropColumn($columnName);
                            break;

                        case 'dropforeign':
                        case 'dropindex':
                        case 'dropprimary':
                        case 'dropunique':
                        case 'foreign':
                        case 'index':
                        case 'primary':
                        case 'renameindex':
                        case 'spatialIndex':
                        case 'unique':
                        case 'dropspatialindex':
                            break;

                        case 'dropmorphs':
                            $table->dropColumn($columnName.'_type');
                            $table->dropColumn($columnName.'_id');
                            break;

                        case 'enum':
                            $table->setColumn(new SchemaColumn($columnName, 'enum', $nullable, $secondArgArray));
                            break;

                        case 'morphs':
                            $table->setColumn(new SchemaColumn($columnName.'_type', 'string', $nullable));
                            $table->setColumn(new SchemaColumn($columnName.'_id', 'int', $nullable));
                            break;

                        case 'nullablemorphs':
                            $table->setColumn(new SchemaColumn($columnName.'_type', 'string', true));
                            $table->setColumn(new SchemaColumn($columnName.'_id', 'int', true));
                            break;

                        case 'nullableuuidmorphs':
                            $table->setColumn(new SchemaColumn($columnName.'_type', 'string', true));
                            $table->setColumn(new SchemaColumn($columnName.'_id', 'string', true));
                            break;

                        case 'rename':
                        case 'renamecolumn':
                            if ($secondArg instanceof PhpParser\Node\Scalar\String_) {
                                $table->renameColumn($columnName, $secondArg->value);
                            }
                            break;

                        case 'set':
                            $table->setColumn(new SchemaColumn($columnName, 'set', $nullable, $secondArgArray));
                            break;

                        case 'softdeletestz':
                        case 'timestamptz':
                        case 'timetz':
                        case 'year':
                        case 'softdeletes':
                            $table->setColumn(new SchemaColumn($columnName, 'string', true));
                            break;

                        case 'uuidmorphs':
                            $table->setColumn(new SchemaColumn($columnName.'_type', 'string', $nullable));
                            $table->setColumn(new SchemaColumn($columnName.'_id', 'string', $nullable));
                            break;

                        default:
                            // We know a property exists with a name, we just don't know its type.
                            $table->setColumn(new SchemaColumn($columnName, 'mixed', $nullable));
                            break;
                    }
                }
            }
        }
    }

    private function dropTable(PhpParser\Node\Expr\StaticCall $call): void
    {
        if (! isset($call->args[0])
            || ! $call->args[0]->value instanceof PhpParser\Node\Scalar\String_
        ) {
            return;
        }

        $tableName = $call->args[0]->value->value;

        unset($this->tables[$tableName]);
    }

    private function renameTable(PhpParser\Node\Expr\StaticCall $call): void
    {
        if (! isset($call->args[0], $call->args[1])
            || ! $call->args[0]->value instanceof PhpParser\Node\Scalar\String_
            || ! $call->args[1]->value instanceof PhpParser\Node\Scalar\String_
        ) {
            return;
        }

        $oldTableName = $call->args[0]->value->value;
        $newTableName = $call->args[1]->value->value;

        if (! isset($this->tables[$oldTableName])) {
            return;
        }

        $table = $this->tables[$oldTableName];

        unset($this->tables[$oldTableName]);

        $table->name = $newTableName;

        $this->tables[$newTableName] = $table;
    }
}
