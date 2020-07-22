<?php


namespace Framework;

//
//use Ramsey\Uuid\Uuid;

abstract class Model
{
    protected $db;
    protected $events;
    protected $queryBuilder;
    protected $table;

    public function __construct($db)
    {
        $this->db = $db['db'];
        $this->queryBuilder = new QueryBuilder;

        if (!$this->table) {
            $table = explode('\\', \get_called_class());
            $table = array_pop($table);
            $this->table = strtolower($table)."s";
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
            ->get();

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
            ->get();

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
//        $uuid = Uuid::uuid4()->toString();

//        $data += ['uuid' => $uuid];

        $data = $this->setData($data);

        $query = $this->queryBuilder->insert($this->table, $data)
            ->get();

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
            ->get();

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

        $query = $this->queryBuilder->delete($this->table)
            ->where($conditions)
            ->get();

        $stmt = $this->db->prepare($query->sql);
        $stmt->execute($query->bind);

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