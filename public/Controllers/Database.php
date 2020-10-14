<?php

namespace Controllers;

use Required\env;
use PDO;

class Database
{
    private $db = null;

    /**
     * @throws \ErrorException
     */
    public function connect(): void
    {
        $ip = env::DATABASE_IP;
        $username = env::DATABASE_USERNAME;
        $password = env::DATABASE_PASSWORD;
        $schema = env::DATABASE_SCHEMA;

        if (!$this->db) {
            if ($ip && $username && $password && $schema) {
                $db = new PDO(sprintf('mysql:dbname=%s;host=%s;charset=utf8', $schema, $ip), $username, $password);
                $db->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
                $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                $this->db = $db;
            } else {
                throw new \ErrorException('Missing database credentials!');
            }
        }
    }

    public function selectFromTable(string $table, array $columns = [], array $conditions = [])
    {
        if ($columns) {
            $columns = implode(',', $columns);
        } else {
            $columns = '*';
        }

        $params = ['columns' => $columns, 'table' => $table];
        $condition = '';
        $counter = 0;

        foreach ($conditions as $key => $value) {
            if ($condition != '') {
                $condition = sprintf('%s AND ', $condition);
            } else {
                $condition = 'WHERE ';
            }
            $condition = sprintf('%s :value%s = :value%s', $condition, $counter++, $counter++);
            $params[sprintf('value%s', $counter - 2)] = $key;
            $params[sprintf('value%s', $counter - 1)] = $value;
        }

        $stmt = $this->db->prepare(sprintf('select :columns from :table %s', $condition));
        $stmt->execute($params);
        return $stmt->get_result();
    }

    public function insertIntoTable(string $table, array $values)
    {
        $keys = [];
        $vals = [];
        foreach ($values as $key => $value) {
            array_push($keys, $key);
            array_push($vals, $value);
            $params[$key] = $value;
        }

        $keys = implode(',', $keys);
        $vals = implode(',', $vals);

        $params = [
            'table' => $table,
            'keys' => $keys,
            'values' => $vals
        ];

        $stmt = $this->db->prepare('insert into :table (:keys) values(:values)');
        $stmt->execute($params);
        return $stmt->get_result();
    }

    public function executeRawQuery(string $sql)
    {
        $sql = filter_var($sql, FILTER_SANITIZE_STRING);
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}