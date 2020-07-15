<?php


namespace Framework;


abstract class Model
{
    protected $db;
    protected $events;
    protected $queryBuilder;
    protected $table;

    public function __construct($db)
    {
        $this->db = $db;
        $this->queryBuilder = new QueryBuilder;

        if (!$this->table) {
            $table = explode('\\', \get_called_class());
            $table = array_pop($table);
            $this->table = strtolower($table);
        }
    }

    /**
     * @param array $conditions
     * @return mixed
     * @throws \Exception
     */
    public function get(array $conditions)
    {
        $query = $this->queryBuilder->select($this->table)
            ->where($conditions)
            ->getData();

        $stmt = $this->db->prepare($query->sql);
        $stmt->execute($query->bind);

        return $stmt->fetch(\PDO::FETCH_ASSOC);
    }

    /**
     * @param array $conditions
     * @return mixed
     * @throws \Exception
     */
    public function all(array $conditions)
    {
        $query = $this->queryBuilder->select($this->table)
            ->where($conditions)
            ->getData();

        $stmt = $this->db->prepare($query->sql);
        $stmt->execute($query->bind);

        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    /**
     * @param array $data
     * @return mixed
     * @throws \Exception
     */
    public function create(array $data)
    {

        $data = $this->setData($data);

        $query = $this->queryBuilder->insert($this->table, $data)
            ->getData();

        $stmt = $this->db->prepare($query->sql);
        $stmt->execute($query->bind);

        $result = $this->get(['id'=>$this->db->lastInsertId()]);


        return $result;
    }

    /**
     * @param array $conditions
     * @param array $data
     * @return mixed
     * @throws \Exception
     */
    public function update(array $conditions, array $data)
    {

        $data = $this->setData($data);

        $query = $this->queryBuilder->update($this->table, $data)
            ->where($conditions)
            ->getData();

        $stmt = $this->db->prepare($query->sql);
        $stmt->execute(array_values($query->bind));

        $result = $this->get($conditions);

        return $result;
    }

    /**
     * @param array $conditions
     * @return mixed
     * @throws \Exception
     */
    public function delete(array $conditions)
    {
        $result = $this->get($conditions);

        $this->events->trigger('deleting.' . $this->table, null, $result);

        $query = $this->queryBuilder->delete($this->table)
            ->where($conditions)
            ->getData();

        $stmt = $this->db->prepare($query->sql);
        $stmt->execute($query->bind);

        $this->events->trigger('deleted.' . $this->table, null, $result);

        return $result;
    }

    /**
     * @param array $data
     * @return array
     */
    protected function setData(array $data)
    {
        foreach ($data as $field => $value) {
            $method = str_replace('_', '', $field);
            $method = ucwords($method);
            $method = str_replace(' ', '', $method);
            $method = "set{$method}";
            if (method_exists($this, $method)) {
                $data[$field] = $this->$method($value);
            }
        }

        return $data;
    }

}