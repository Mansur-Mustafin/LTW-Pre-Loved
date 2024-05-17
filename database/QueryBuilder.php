<?php

declare(strict_types = 1);

require_once(__DIR__.'/../database/connection.db.php');

class QueryBuilder
{
    private array $queryParts = [];
    private ?string $modelClass = null;

    public function __construct(string $modelClass = null)
    {
        $this->modelClass = $modelClass;
    }

    public function select(string $select = '*'): static
    {
        $this->queryParts['select'] = $select;
        return $this;
    }

    public function from(string $from): static
    {
        $this->queryParts['from'] = $from;
        return $this;
    }

    public function where(array $where, string $operator = 'AND'): static
    {
        $this->queryParts['where'][] = ['condition' => $where, 'operator' => $operator];
        return $this;
    }

    public function orderBy(string $orderBy): static
    {
        $this->queryParts['orderBy'] = $orderBy;
        return $this;
    }

    public function modelClass(string $class): static
    {
        $this->modelClass = $class;
        return $this;
    }

    public function all()
    {
        $db = getDatabaseConnection();

        [$query, $bindParams] = $this->getQuery();
        
        $stmt = $this->createCommand($db, $query, $bindParams);
        
        $stmt->execute();

        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        return $this->modelClass ? $this->convertToModels($result) : $result;
    }

    public function createCommand($db, $query, $params = [])
    {
        $stmt = $db->prepare($query);

        foreach ($params as $key => $value) {
            $stmt->bindValue($key, $value);
            ?> <!-- <br> <?= var_dump($key) ?> => <?= var_dump($value) ?> <br> --> <?php
        }
        
        return $stmt;
    }

    public function getQuery(): array
    {
        $query = "SELECT {$this->queryParts['select']} FROM {$this->queryParts['from']}";
        $bindParams = [];

        if (!empty($this->queryParts['where'])) {
            $conditions = [];
            $index = 1;
            $query .= ' WHERE ';

            foreach ($this->queryParts['where'] as $i => $part) {
                if (count($part['condition']) !== 3) {
                    throw new Exception("Each 'where' condition must have exactly 3 elements.");
                }

                [$column, $operator, $value] = $part['condition'];
                $paramName = ":q" . $index++;
                $conditions[] = "{$column} {$operator} {$paramName}";
                $bindParams[$paramName] = $value;
                if ($i < count($this->queryParts['where']) - 1) {
                    $conditions[] = $part['operator'];
                }
            }

            $query .= implode(' ', $conditions);
        }

        return [$query, $bindParams];
    }

    private function convertToModels(array $result)
    {
        $models = [];
        
        foreach ($result as $row) {

            $model = $this->convertToModel($this->modelClass, $row);

            foreach ($row as $key => $value) {
                $model->{$key} = $value;
                ?> <!-- <?= $key; ?> <?= $value; ?> <br> --><?php
            }
            $model->ensureDefaultValues();
            $models[] = $model;
        }

        return $models;
    }

    private function convertToModel(string $model, array $row) 
    {
        switch($model)
        {
            case 'User':
                return new User($row['username'], $row['password'], $row['email']);
        }
    }
}
