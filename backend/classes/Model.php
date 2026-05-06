<?php


class Model
{
    protected $table;
    protected $pkey;
    private \PDO $database;
    public function __construct($tableName, $primaryKey = "id")
    {
        $this->table =  htmlspecialchars($tableName);
        $this->pkey = htmlspecialchars($primaryKey);
        $this->database = Database::getConnection();
        $this->database->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
        
    }

    public function getAll()
    {
        $result = $this->database->query("SELECT * FROM $this->table");
        return $result->fetchAll();
    }

    public function get($id)
    {   // SELECT field FROM table WHERE id = ?
        $query = $this->database->prepare("SELECT * FROM $this->table WHERE $this->pkey = ? LIMIT 1");
        $query->bindValue(1, $id);
        $query->execute();
        return $query->fetch();
    }
    

    /**
     * Busca por um registro na tabela que tenha o campo com o dado valor
     */
    public function find($field, $value)
    {
        $field = htmlspecialchars($field);
        $value = htmlspecialchars($value);
        $query = $this->database->prepare("SELECT * FROM $this->table WHERE $field=:value LIMIT 1");
        $query->bindValue(':value', $value);
        $query->execute();
        return $query->fetch();
    }

    public function findAll($field, $value, int $limit = 0)
    {
        $field = htmlspecialchars($field);
        $value = $value;
        $limit = $limit > 0 ? 'LIMIT ' . filter_var($limit, FILTER_SANITIZE_NUMBER_INT) : '';
        $query = $this->database->prepare("SELECT * FROM $this->table WHERE $field=? $limit");
        $query->bindValue(1, $value);
        $query->execute();
        return $query->fetchAll() ?? [];
    }

    /**
     * @param array $data  key value pair: db_tbl_field => value 
     */
    public function insert($data)
    {   // INSERT INTO table VALUES (?,?,...)
        $fields = implode(',', array_keys($data));
        $values = implode(',', array_fill(0, count($data), '?'));  // 
        $query = $this->database->prepare("INSERT INTO $this->table($fields) VALUES ($values) ");
        $i = 1;
        foreach ($data as $value) {
            $query->bindValue($i++, $value);
        }
        $query->execute();
        return $query->rowCount() > 0;
    }

    /**
     ** Update the Model xxx Table
     * (new Model('User'))->update(1,['email' => 'stranger@gmail.who', 'name'  => 'stranger'])
     */
    public function update($id, $data)
    {   // UPDATE table SET field=? ... WHERE id = ?
        if (empty($data)) {
            return false;
        }
        $fields = implode('=?, ', array_keys($data));
        $query = $this->database->prepare("UPDATE $this->table SET $fields=? WHERE $this->pkey = ?");
        $i = 1;
        foreach ($data as $value) {
            $query->bindValue($i++, $value);
        }
        $query->bindValue($i, $id);
        $query->execute();
        return $query->rowCount() > 0;
    }

    public function delete($id)
    {   // DELETE FROM table WHERE id = ?
        $query = $this->database->prepare("DELETE FROM $this->table WHERE $this->pkey=?");
        $query->bindValue(1, $id);
        $query->execute();
        return $query->rowCount() > 0;
    }

}

/**
 * HOW TO USE?
 * 
 * Examples:
 *
 * $users = new Model('tbl_user');
 * $list = $users->getAll();
 * $users->insert(['nome' => 'Jhon', 'apelido' => 'Doe']);
 * $users->delete(17);
 * $users->update(17,['nome'=>'Mister', 'apelido' => 'shackle']);
 * $admin = $users->get(1);
 * $admin_enterprise = (new Model('tbl_empresa','id_empresa'))->get($admin->emp_id);

*/

