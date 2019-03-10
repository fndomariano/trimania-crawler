<?php

namespace Trimania\Core;

use Trimania\Core\Interfaces\QueryBuilderInterface;
use Trimania\Core\Interfaces\DatabaseInterface;

class QueryBuilder implements QueryBuilderInterface
{
    private $database;

    private $options = [];

    public function __construct(DatabaseInterface $database)
    {
        $this->database = $database;
    }

    public function __call($name, $arguments)
    {
        $options = count($arguments) == 1 ? current($arguments) : $arguments;

        $this->options[$name] = $options;

        return $this;
    }

    public function select()
    {
        $options = $this->options;
        
        if (!isset($options['table'])) {
            throw new \InvalidArgumentException('Table is not defined!');
        }

        $query[] = "SELECT";
        
        if (!isset($options['columns']) || !is_array($options['columns']) || empty($options['columns'])) {
            $options['columns'] = ['*'];
        }

        $query[] = implode(', ', $options['columns']);
        $query[] = "FROM {$options['table']}";
        
        if (isset($options['where']) && is_array($options['where']) && !empty($options['where'])) {
            $where = implode(' ', $options['where']);
            $query[] = "WHERE {$where}";
        }

        $query = implode(' ', $query);
        
        return $this->database->executeSelect($query);
    }

    public function insert()
    {
        $options = $this->options;

        if (!isset($options['table'])) {
            throw new \InvalidArgumentException('Table is not defined!');
        }
        
        if (!isset($options['columns']) || !is_array($options['columns']) || empty($options['columns'])) {
            throw new \InvalidArgumentException('Columns is not defined!');
        }

        if (!isset($options['values']) || !is_array($options['values']) || empty($options['values'])) {            
            throw new \InvalidArgumentException('Values is not defined!');
        }
    
        $columns  = implode(',', $options['columns']);
        $values   = [];

        $query[]  = "INSERT INTO {$options['table']} ({$columns})";
        
        foreach ($options['values'] as $value) {
            if (is_string($value)) {
                $values[] = "'$value'";
            } elseif (is_null($value)) {
                $values[] = 'null';
            } else {
                $values[] = $value;
            }
        }

        $query[]  = "VALUES";
        $query[]  = '('.implode(',', $values).')';;
        $query = implode(' ', $query);

        return (int) $this->database->executeInsert($query);
    }

    public function update()
    {
    }

    public function delete()
    {
    }
}
