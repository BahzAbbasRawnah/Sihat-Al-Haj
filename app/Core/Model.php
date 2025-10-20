<?php

namespace App\Core;

/**
 * Base Model Class
 * 
 * Provides basic database operations and ORM-like functionality
 */
abstract class Model
{
    protected $database;
    protected $table;
    protected $primaryKey = 'id';
    protected $fillable = [];
    protected $hidden = [];
    protected $timestamps = true;
    
    public function __construct()
    {
        $this->database = Database::getInstance();
    }
    
    /**
     * Find a record by ID
     */
    public function find($id)
    {
        $sql = "SELECT * FROM {$this->table} WHERE {$this->primaryKey} = :id LIMIT 1";
        $stmt = $this->database->prepare($sql);
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        
        $result = $stmt->fetch(\PDO::FETCH_ASSOC);
        return $result ? $this->hideFields($result) : null;
    }
    
    /**
     * Find all records
     */
    public function all($limit = null, $offset = 0)
    {
        $sql = "SELECT * FROM {$this->table}";
        
        if ($limit) {
            $sql .= " LIMIT {$limit} OFFSET {$offset}";
        }
        
        $stmt = $this->database->prepare($sql);
        $stmt->execute();
        
        $results = $stmt->fetchAll(\PDO::FETCH_ASSOC);
        return array_map([$this, 'hideFields'], $results);
    }
    
    /**
     * Find records with conditions
     */
    public function where($conditions, $limit = null, $offset = 0)
    {
        $whereClause = $this->buildWhereClause($conditions);
        $sql = "SELECT * FROM {$this->table} WHERE {$whereClause['sql']}";
        
        if ($limit) {
            $sql .= " LIMIT {$limit} OFFSET {$offset}";
        }
        
        $stmt = $this->database->prepare($sql);
        
        foreach ($whereClause['params'] as $key => $value) {
            $stmt->bindValue($key, $value);
        }
        
        $stmt->execute();
        
        $results = $stmt->fetchAll(\PDO::FETCH_ASSOC);
        return array_map([$this, 'hideFields'], $results);
    }
    
    /**
     * Find first record with conditions
     */
    public function whereFirst($conditions)
    {
        $results = $this->where($conditions, 1);
        return !empty($results) ? $results[0] : null;
    }
    
    /**
     * Create a new record
     */
    public function create($data)
    {
        $data = $this->filterFillable($data);
        
        if ($this->timestamps) {
            $data['created_at'] = date('Y-m-d H:i:s');
            $data['updated_at'] = date('Y-m-d H:i:s');
        }
        
        $columns = implode(', ', array_keys($data));
        $placeholders = ':' . implode(', :', array_keys($data));
        
        $sql = "INSERT INTO {$this->table} ({$columns}) VALUES ({$placeholders})";
        $stmt = $this->database->prepare($sql);
        
        foreach ($data as $key => $value) {
            $stmt->bindValue(":{$key}", $value);
        }
        
        if ($stmt->execute()) {
            $id = $this->database->lastInsertId();
            return $this->find($id);
        }
        
        return false;
    }
    
    /**
     * Update a record
     */
    public function update($id, $data)
    {
        $data = $this->filterFillable($data);
        
        if ($this->timestamps) {
            $data['updated_at'] = date('Y-m-d H:i:s');
        }
        
        $setClause = [];
        foreach ($data as $key => $value) {
            $setClause[] = "{$key} = :{$key}";
        }
        
        $sql = "UPDATE {$this->table} SET " . implode(', ', $setClause) . " WHERE {$this->primaryKey} = :id";
        $stmt = $this->database->prepare($sql);
        
        $stmt->bindValue(':id', $id);
        foreach ($data as $key => $value) {
            $stmt->bindValue(":{$key}", $value);
        }
        
        if ($stmt->execute()) {
            return $this->find($id);
        }
        
        return false;
    }
    
    /**
     * Delete a record
     */
    public function delete($id)
    {
        $sql = "DELETE FROM {$this->table} WHERE {$this->primaryKey} = :id";
        $stmt = $this->database->prepare($sql);
        $stmt->bindParam(':id', $id);
        
        return $stmt->execute();
    }
    
    /**
     * Count records
     */
    public function count($conditions = [])
    {
        $sql = "SELECT COUNT(*) as count FROM {$this->table}";
        
        if (!empty($conditions)) {
            $whereClause = $this->buildWhereClause($conditions);
            $sql .= " WHERE {$whereClause['sql']}";
        }
        
        $stmt = $this->database->prepare($sql);
        
        if (!empty($conditions)) {
            foreach ($whereClause['params'] as $key => $value) {
                $stmt->bindValue($key, $value);
            }
        }
        
        $stmt->execute();
        $result = $stmt->fetch(\PDO::FETCH_ASSOC);
        
        return (int) $result['count'];
    }
    
    /**
     * Execute raw SQL query
     */
    public function query($sql, $params = [])
    {
        $stmt = $this->database->prepare($sql);
        
        foreach ($params as $key => $value) {
            $stmt->bindValue($key, $value);
        }
        
        $stmt->execute();
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }
    
    /**
     * Build WHERE clause from conditions array
     */
    private function buildWhereClause($conditions)
    {
        $sql = [];
        $params = [];
        $paramIndex = 0;
        
        foreach ($conditions as $key => $value) {
            $paramName = ":param{$paramIndex}";
            
            if (is_array($value)) {
                // Handle operators like ['>', 10] or ['LIKE', '%search%']
                $operator = $value[0];
                $paramValue = $value[1];
                $sql[] = "{$key} {$operator} {$paramName}";
                $params[$paramName] = $paramValue;
            } else {
                // Simple equality
                $sql[] = "{$key} = {$paramName}";
                $params[$paramName] = $value;
            }
            
            $paramIndex++;
        }
        
        return [
            'sql' => implode(' AND ', $sql),
            'params' => $params
        ];
    }
    
    /**
     * Filter data to only include fillable fields
     */
    private function filterFillable($data)
    {
        if (empty($this->fillable)) {
            return $data;
        }
        
        return array_intersect_key($data, array_flip($this->fillable));
    }
    
    /**
     * Hide specified fields from result
     */
    private function hideFields($data)
    {
        if (empty($this->hidden)) {
            return $data;
        }
        
        foreach ($this->hidden as $field) {
            unset($data[$field]);
        }
        
        return $data;
    }
    
    /**
     * Get table name
     */
    public function getTable()
    {
        return $this->table;
    }
    
    /**
     * Get primary key
     */
    public function getPrimaryKey()
    {
        return $this->primaryKey;
    }
}

