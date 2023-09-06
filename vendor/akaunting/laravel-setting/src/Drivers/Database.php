<?php

namespace Akaunting\Setting\Drivers;

use Akaunting\Setting\Contracts\Driver;
use Akaunting\Setting\Support\Arr;
use Closure;
use Illuminate\Database\Connection;
use Illuminate\Support\Arr as LaravelArr;
use Illuminate\Support\Facades\Crypt;

class Database extends Driver
{
    /**
     * The database connection instance.
     *
     * @var \Illuminate\Database\Connection
     */
    protected $connection;

    /**
     * The table to query from.
     *
     * @var string
     */
    protected $table;

    /**
     * The key column name to query from.
     *
     * @var string
     */
    protected $key;

    /**
     * The value column name to query from.
     *
     * @var string
     */
    protected $value;

    /**
     * Keys which should be encrypt automatically.
     *
     * @var string
     */
    protected $encrypted_keys;

    /**
     * Any query constraints that should be applied.
     *
     * @var Closure|null
     */
    protected $query_constraint;

    /**
     * Any extra columns that should be added to the rows.
     *
     * @var array
     */
    protected $extra_columns = [];

    /**
     * @param \Illuminate\Database\Connection $connection
     * @param string $table
     */
    public function __construct(Connection $connection, $table = null, $key = null, $value = null, $encrypted_keys = [])
    {
        $this->connection = $connection;
        $this->table = $table ?: 'settings';
        $this->key = $key ?: 'key';
        $this->value = $value ?: 'value';
        $this->encrypted_keys = $encrypted_keys ?: [];
    }

    /**
     * Set the table to query from.
     *
     * @param string $table
     */
    public function setTable($table)
    {
        $this->table = $table;
    }

    /**
     * Set the key column name to query from.
     *
     * @param string $key
     */
    public function setKey($key)
    {
        $this->key = $key;
    }

    /**
     * Set the value column name to query from.
     *
     * @param string $value
     */
    public function setValue($value)
    {
        $this->value = $value;
    }

    /**
     * Set the query constraint.
     *
     * @param Closure $callback
     */
    public function setConstraint(Closure $callback)
    {
        $this->data = [];
        $this->loaded = false;
        $this->query_constraint = $callback;
    }

    /**
     * Set extra columns to be added to the rows.
     *
     * @param array $columns
     */
    public function setExtraColumns(array $columns)
    {
        $this->extra_columns = $columns;
    }

    /**
     * Get extra columns added to the rows.
     *
     * @return array
     */
    public function getExtraColumns()
    {
        return $this->extra_columns;
    }

    /**
     * {@inheritdoc}
     */
    public function forget($key)
    {
        parent::forget($key);

        // because the database driver cannot store empty arrays, remove empty
        // arrays to keep data consistent before and after saving
        $segments = explode('.', $key);
        array_pop($segments);

        while ($segments) {
            $segment = implode('.', $segments);

            // non-empty array - exit out of the loop
            if ($this->get($segment)) {
                break;
            }

            // remove the empty array and move on to the next segment
            $this->forget($segment);
            array_pop($segments);
        }
    }

    /**
     * {@inheritdoc}
     */
    protected function write(array $data)
    {
        // Get current data
        $db_data = $this->newQuery()->get([$this->key, $this->value])->toArray();

        $insert_data = LaravelArr::dot($data);
        $update_data = [];
        $delete_keys = [];

        foreach ($db_data as $db_row) {
            $key = $db_row->{$this->key};
            $value = $db_row->{$this->value};

            $is_in_insert = $is_different_in_db = $is_same_as_fallback = false;

            if (isset($insert_data[$key])) {
                $is_in_insert = true;
                $is_different_in_db = (string) $insert_data[$key] != (string) $value;
                $is_same_as_fallback = $this->isEqualToFallback($key, $insert_data[$key]);
            }

            if ($is_in_insert) {
                if ($is_same_as_fallback) {
                    // Delete if new data is same as fallback
                    $delete_keys[] = $key;
                } elseif ($is_different_in_db) {
                    // Update if new data is different from db
                    $update_data[$key] = $insert_data[$key];
                }
            } else {
                // Delete if current db not available in new data
                $delete_keys[] = $key;
            }

            unset($insert_data[$key]);
        }

        foreach ($update_data as $key => $value) {
            $value = $this->prepareValue($key, $value);

            $this->newQuery()
                ->where($this->key, '=', $key)
                ->update([$this->value => $value]);
        }

        if ($insert_data) {
            $this->newQuery(true)
                ->insert($this->prepareInsertData($insert_data));
        }

        if ($delete_keys) {
            $this->newQuery()
                ->whereIn($this->key, $delete_keys)
                ->delete();
        }
    }

    /**
     * Transforms settings data into an array ready to be insterted into the
     * database. Call array_dot on a multidimensional array before passing it
     * into this method!
     *
     * @param array $data Call array_dot on a multidimensional array before passing it into this method!
     *
     * @return array
     */
    protected function prepareInsertData(array $data)
    {
        $db_data = [];

        if ($this->getExtraColumns()) {
            foreach ($data as $key => $value) {
                $value = $this->prepareValue($key, $value);

                // Don't insert if same as fallback
                if ($this->isEqualToFallback($key, $value)) {
                    continue;
                }

                $db_data[] = array_merge(
                    $this->getExtraColumns(),
                    [$this->key => $key, $this->value => $value]
                );
            }
        } else {
            foreach ($data as $key => $value) {
                $value = $this->prepareValue($key, $value);

                // Don't insert if same as fallback
                if ($this->isEqualToFallback($key, $value)) {
                    continue;
                }

                $db_data[] = [$this->key => $key, $this->value => $value];
            }
        }

        return $db_data;
    }

    /**
     * Checks if the provided key should be encrypted or not.
     * Also type casts the given value to a string so errors with booleans or integers are handeled.
     * Otherwise it returns the original value.
     *
     * @param  string $key   Key to check if it's inside the encryptedValues variable.
     * @param  mixed $value  Info: Encryption only supports strings.
     *
     * @return string
     */
    protected function prepareValue(string $key, $value)
    {
        // Check if key should be encrypted
        if (in_array($key, $this->encrypted_keys)) {
            // Cast to string to avoid error when a user passes a boolean value
            return Crypt::encryptString((string) $value);
        }

        return $value;
    }

    /**
     * Checks if the provided key should be decrypted or not.
     * Otherwise it returns the original value.
     *
     * @param  string $key   Key to check if it's inside the encryptedValues variable.
     * @param  mixed $value  Info: Encryption only supports strings.
     *
     * @return string
     */
    protected function unpackValue(string $key, $value)
    {
        // Check if key should be encrypted
        if (in_array($key, $this->encrypted_keys)) {
            // Cast to string to avoid error when a user passes a boolean value
            return Crypt::decryptString((string) $value);
        }

        return $value;
    }

    /**
     * {@inheritdoc}
     */
    protected function read()
    {
        return $this->parseReadData($this->newQuery()->get());
    }

    /**
     * Parse data coming from the database.
     *
     * @param array $data
     *
     * @return array
     */
    public function parseReadData($data)
    {
        $results = [];

        foreach ($data as $row) {
            if (is_array($row)) {
                $key = $row[$this->key];
                $value = $row[$this->value];
            } elseif (is_object($row)) {
                $key = $row->{$this->key};
                $value = $row->{$this->value};
            } else {
                $msg = 'Expected array or object, got ' . gettype($row);
                throw new \UnexpectedValueException($msg);
            }

            // Encryption
            $value = $this->unpackValue($key, $value);

            Arr::set($results, $key, $value);
        }

        return $results;
    }

    /**
     * Create a new query builder instance.
     *
     * @param bool $insert
     *
     * @return \Illuminate\Database\Query\Builder
     */
    protected function newQuery($insert = false)
    {
        $query = $this->connection->table($this->table);

        if (!$insert) {
            foreach ($this->getExtraColumns() as $key => $value) {
                $query->where($key, '=', $value);
            }
        }

        if ($this->query_constraint !== null) {
            $callback = $this->query_constraint;
            $callback($query, $insert);
        }

        return $query;
    }
}
