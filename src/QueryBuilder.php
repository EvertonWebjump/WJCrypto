<?php


namespace Framework;


class QueryBuilder
{
    private  $sql;
    private $bind = [];

    /**
     * @param String $table
     * @return QueryBuilder
     */
    public function select(String $table)
    {
        $this->sql = "SELECT * FROM `{$table}`";
        return $this;
    }

    /**
     * @param String $table
     * @param array $data
     * @return QueryBuilder
     */
    public function insert(String $table, array $data)
    {
        $sql = "INSERT INTO `{$table}` (%s) VALUES (%s)";

        $colums = array_keys($data);
        $values = array_fill(0, count($colums), '?');
        $this->bind = array_values($data);

        $this->sql = sprintf($sql, implode(',', $colums), implode(',', $values));

        return $this;
    }

    /**
     * @param String $table
     * @param array $data
     * @return QueryBuilder
     */
    public function update(String $table, array $data)
    {
        $sql = "UPDATE `{$table}` SET %s";

        $columns = array_keys($data);

        foreach ($columns as &$column) {
            $column = $column . '=?';
        }

        $this->bind = array_values($data);
        $this->sql = sprintf($sql, implode(', ', $columns));

        return $this;
    }

    /**
     * @param string $table
     * @return QueryBuilder
     */
    public function delete(string $table)
    {
        $this->sql = "DELETE FROM `{$table}`";
        return $this;
    }

    /**
     * @param $conditions
     * @return QueryBuilder
     * @throws \Exception
     */
    public function where($conditions)
    {
        if ($conditions == []) {
            return $this;
        }

        if (!$this->sql) {
            throw new \Exception("selete(), update() or delete() is required before where() method");
        }

        $columns = array_keys($conditions);

        foreach ($columns as &$column) {
            $column = $column . '=?';
        }

        $this->bind = array_merge($this->bind, array_values($conditions));
        $this->sql .= ' WHERE ' . implode(' and ', $columns);

        return $this;
    }

    /**
     * @return \stdClass
     */
    public function get()
    {
        $query = new \stdClass;
        $query->sql = $this->sql;
        $query->bind = $this->bind;

        $this->sql = null;
        $this->bind = [];

        return $query;
    }

}