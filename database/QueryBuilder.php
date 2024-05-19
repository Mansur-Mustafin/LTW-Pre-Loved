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
        if ($select != '*'){
            $this->queryParts['select'][] = $select;
        } else {
            // previous selec columns will be selected by '*'
            $this->queryParts['select'] = ['*'];
        }
        
        return $this;
    }

    public function from(string $from): static
    {
        $this->queryParts['from'] = $from;
        return $this;
    }

    public function join(string $tableName, string $condition): static
    {
        $this->queryParts['join'][] = ['table' => $tableName,'condition'=> $condition];
        return $this;
    }

    public function where(array $where, string $operator = 'AND'): static
    {
        $this->queryParts['where'][] = ['condition' => $where, 'operator' => $operator];
        return $this;
    }

    public function order(string $order, string $dir = 'DESC'): static
    {
        if ($dir != 'ASC' && $dir != 'DESC')
            return $this;

        if (isset($this->queryParts['order'])) {
            $this->queryParts['order'] .= ", {$order} {$dir}";
        } else {
            $this->queryParts['order'] = "{$order} {$dir}";
        }
        return $this;
    }

    public function offset(int $offset): static
    {
        $this->queryParts['offset'] = $offset;
        return $this;
    }

    public function limit(int $limit): static
    {
        $this->queryParts['limit'] = $limit;
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
        }
        
        return $stmt;
    }

    public function getQuery(): array
    {
        $select = !empty($this->queryParts['select']) ? implode(", ", $this->queryParts['select']) : '*';

        $fromTable = $this->queryParts['from'];
        
        $query = "SELECT {$select} FROM {$fromTable}";
        $bindParams = [];
        
        // Handle joins
        if (!empty($this->queryParts['join'])) {
            foreach ($this->queryParts['join'] as $join) {
                $query .= " LEFT JOIN {$join['table']} ON {$join['condition']}";
            }
        }
        
        // Handle where
        if (!empty($this->queryParts['where'])) {
            $conditions = [];
            $index = 1;
            $query .= ' WHERE ';

            foreach ($this->queryParts['where'] as $i => $part) {
                if (count($part['condition']) !== 3) {
                    continue;
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
        
        // Handle order
        if (!empty($this->queryParts['order'])) {
            $query .= " ORDER BY " . $this->queryParts['order'];
        }

        // Handle limit
        if (isset($this->queryParts['limit'])) {
            $query .= " LIMIT :limit";
            $bindParams[':limit'] = $this->queryParts['limit'];
        }

        // Handle offset
        if (isset($this->queryParts['offset'])) {
            $query .= " OFFSET :offset";
            $bindParams[':offset'] = $this->queryParts['offset'];
        }

        return [$query, $bindParams];
    }

    public function convertToModels(array $result)
    {
        $models = [];
        
        foreach ($result as $row) {

            $model = $this->convertToModel($this->modelClass, $row);

            foreach ($row as $key => $value) {
                $model->{$key} = $value;
            }
            $model->ensureDefaultValues();
            $models[] = $model;
        }

        return $models;
    }

    public function convertToModel(string $model, array $row) 
    {
        return match ($model) {
            'User' => new User($row['username'], $row['password'], $row['email']),
            'Item' => new Item($row['price'], $row['user_id']),
            default => null,
        };
    }

    // Some functions that for specific classes:
    
    public function setupItemJoins() : static 
    {
        $this->join("Condition", "Items.condition_id = Condition.id")
            ->join("Models", "Items.model_id = Models.id")
            ->join("Categories", "Items.category_id = Categories.id")
            ->join("Size", "Items.size_id = Size.id")
            ->join("Brands", "Models.brand_id = Brands.id");

        return $this;
    }

    public function setupItemSelect() : static 
    {
        $this->select("Items.*")
            ->select("Condition.name AS condition")
            ->select("Models.name AS model")
            ->select("Categories.name AS category")
            ->select("Size.name AS size")
            ->select("Brands.name AS brand");

        return $this;
    }
}
