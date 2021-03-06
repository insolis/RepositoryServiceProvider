<?php

namespace Insolis;

use Doctrine\DBAL\Connection;

/**
 * Represents a base Repository.
 */
abstract class Repository
{
    /**
     * @return string
     */
    abstract public function getTableName();

    /**
     * @var Connection
     */
    public $db;

    /**
     * @param Connection $db
     */
    public function __construct(Connection $db)
    {
        $this->db = $db;
    }

    /**
     * Inserts a table row with specified data.
     *
     * @param array $data An associative array containing column-value pairs.
     * @return integer The number of affected rows.
     */
    public function insert(array $data)
    {
        return $this->db->insert($this->getTableName(), $data);
    }

    /**
     * Executes an SQL UPDATE statement on a table.
     *
     * @param array $data An associative array containing column-value pairs.
     * @param array $identifier The update criteria. An associative array containing column-value pairs.
     * @return integer The number of affected rows.
     */
    public function update(array $data, array $identifier)
    {
        return $this->db->update($this->getTableName(), $data, $identifier);
    }

    /**
     * Executes an SQL DELETE statement on a table.
     *
     * @param array $identifier The deletion criteria. An associateve array containing column-value pairs.
     * @return integer The number of affected rows.
     */
    public function delete(array $identifier)
    {
        return $this->db->delete($this->getTableName(), $identifier);
    }

    /**
     * Returns a record by supplied id
     * 
     * @param mixed $id 
     * @return array
     */
    public function find($id)
    {
        return $this->db->fetchAssoc(sprintf('SELECT * FROM %s WHERE id = ? LIMIT 1', $this->getTableName()), array($id));
    }

    /**
     * Returns all records from this repository's table
     * 
     * @return array
     */
    public function findAll()
    {
        return $this->db->fetchAll(sprintf('SELECT * FROM %s;', $this->getTableName()));
    }

    /**
     * Returns the record count of this repository's table
     *
     * @return int
     */
    public function count()
    {
        return $this->db->fetchColumn(sprintf("SELECT COUNT(*) FROM %s;", $this->getTableName()));
    }
}
