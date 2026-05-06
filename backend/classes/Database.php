<?php

class Database
{
    private static ?\PDO $pdo = null;
    public static $lastError;

    public static function getConnection()
    {
        if (self::$pdo == null) {
            self::$pdo = getDatabaseConnection();
        }
        return self::$pdo;
    }

    /**
     **Iniciar Transaction
     * Transaction são usadas quando se executa duas queries DML (Insert, Delete, Update) e todas as execuções devem ser obrigatoriamente bem sucessidas.
     * Em caso de falha todas as operações feitas até a falha serão desfeitas, para isso usa-se rollback()
     * Deve ser colocado dentro de try{} e posteriormente usar rollback no catch{}
     */
    public static function beginTransaction()
    {
        self::getConnection()->beginTransaction();
    }

    /**
     * Rollback em caso de falha na transição
     */
    public static function rollBack()
    {
        self::getConnection()->rollBack();
    }

    /**
     **Executar uma funcao num transation
     * Transaction são usadas quando se executa duas queries DML (Insert, Delete, Update) e todas as execuções devem ser obrigatoriamente bem sucessidas.
     * ? Crie uma função que execute as suas queries e envie esta função como argumento para este metodo
     * @param callback - função a ser executada dentro de uma transaction
     * @var callback - deve ser uma função que execute uma query usando a conexao fornecida por esta classe
     */
    public static function executeTransaction(callable $callback)
    {
        try {
            self::beginTransaction();
            $callback();
            return true;
        } catch (Exception $ex) {
            self::rollBack();
        }
        return false;
    }

    public static function prepare($sql, $params = [])
    {
        $conn = self::getConnection();
        $stmt = $conn->prepare($sql);
        $stmt = self::bindParams($stmt, $params);
        $stmt->execute();
        self::$lastError = $stmt->errorInfo();
        return $stmt;
    }

    /**
     * executar uma consulta SQL, pode passar parametros de bindParams dentro de um array,
     * Pronto para ser usado com ->fetch() ou fetchAll()     */
    public static function execute($sql, $params = [])
    {
        $conn = self::getConnection();
        $stmt = $conn->prepare($sql);
        $stmt = self::bindParams($stmt, $params);
        $stmt->execute();
        self::$lastError = $stmt->errorInfo();
        return $stmt;
    }

    /**
     * @param PDOStatement $stmt
     */
    private static function bindParams($stmt, $params)
    {
        foreach ($params as $key => $value) {
            $key = is_integer($key) ? $key + 1 : $key; // PDO::PARAM_INT
            $stmt->bindValue($key, $value);
        }
        return $stmt;
    }

    public static function lastError()
    {
        self::$pdo->errorInfo();
    }

}
