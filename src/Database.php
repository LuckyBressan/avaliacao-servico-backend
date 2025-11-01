<?php

require_once('utils/funcao.php');

class Database
{

    public static function select(
        array $coluna = ['*'],
        string $tabela = '',
        array $condicao = [],
        ?int $limit = null,
        array $order = []
    ) {

        if (
            !self::validate($coluna, false) ||
            !self::validate($tabela, false)
        ) {
            return null;
        }

        $sql = '
            SELECT ' . implode(', ', $coluna) . '
              FROM ' . $tabela . '
        ';

        $sql .= self::addCondition($sql, $condicao);
        $sql .= self::addOrder($sql, $order);

        if (is_numeric($limit)) {
            $sql .= " LIMIT {$limit} ";
        }

        $connect = self::connect();
        $result = pg_query($connect, $sql);

        return $result ? $result : null;
    }

    public static function insert(
        string $tabela = '',
        array $dados = []
    ): bool {
        if (
            !self::validate($tabela, false) ||
            !self::validate($dados, false)
        ) {
            return false;
        }

        $colunas = '';

        //Se as keys do array de dados forem string, significa que somente preenchemos os dados de colunas específicas
        if (is_string(array_keys($dados)[0])) {
            $colunas = '(' . implode(',', array_keys($dados)) . ')';
        }

        $values = [];

        for ($i = 1; $i <= count($dados); $i++) {
            $values[] = "$" . $i;
        }

        $sql = "
            INSERT INTO {$tabela} {$colunas}
                 VALUES (" . implode(',', $values) . ")
        ";

        $connect = self::connect();

        $result = pg_query_params($connect, $sql, array_values($dados));

        return $result ? true : false;
    }

    public static function update(
        string $tabela = '',
        array $dados = [],
        array $condicao = []
    ): bool {
        if (
            !self::validate($tabela, false) ||
            !self::validate($dados, false) ||
            !self::validate($condicao)
        ) {
            return false;
        }

        $sql = "
            UPDATE $tabela
               SET " . implode(',', $dados) . "
        ";

        $sql .= self::addCondition($sql, $condicao);

        $connect = self::connect();

        $result = pg_query($connect, $sql);

        return $result ? true : false;
    }

    public static function delete(
        string $tabela = '',
        array $condicao = []
    ): bool {
        if (
            !self::validate($tabela, false) ||
            !self::validate($condicao)
        ) {
            return false;
        }

        $sql = "
            DELETE
              FROM $tabela
        ";

        $sql .= self::addCondition($sql, $condicao);

        $connect = self::connect();

        $result = pg_query($connect, $sql);

        return $result ? true : false;
    }

    private static function addCondition(
        string $sql = '',
        array $condicao = []
    ): string {
        if (count($condicao) > 0) {
            $sql .= ' WHERE ';
            foreach ($condicao as $operator => $condition) {
                if (
                    !self::validate($operator) &&
                    !self::validate($condition, false)
                ) {
                    continue;
                }

                //Se não foi definido operador, automaticamente é AND
                $operator = is_string($operator) ? $operator : ' AND ';

                $sql .= "{$operator} {$condition}";
            }
        }
        return $sql;
    }

    private static function addOrder(
        string $sql = '',
        array $ordem = []
    ): string {
        if (count($ordem) > 0) {
            $sql .= ' ORDER BY ';
            foreach ($ordem as $coluna => $order) {
                if (
                    !self::validate($coluna, false) &&
                    !self::validate($order, false)
                ) {
                    continue;
                }

                $sql .= "{$coluna} {$order}";
            }
        }
        return $sql;
    }

    private static function validate(mixed $info, bool $bAllowEmpty = true): bool
    {
        if (isEmpty($info))
            return $bAllowEmpty;

        return true;
    }

    private static function execute(string $sql = '', array $params = [])
    {
        try {
            $connect = self::connect();

            $result = pg_query_params($connect, $sql, array_values($params));

            return $result ? true : false;
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    private static function connect()
    {
        global $connectDb;
        if (!$connectDb) {
            //pegamos as informações database do env
            $connectionParams = [
                'host' => getenv('DATABASE_HOST'),
                'port' => getenv('DATABASE_PORT'),
                'dbname' => getenv('DATABASE_NAME'),
                'user' => getenv('DATABASE_USER'),
                'password' => getenv('DATABASE_PASSWORD')
            ];
            //montamos a string de conexão com o banco de dados
            $connectionString = '';
            foreach ($connectionParams as $key => $value) {
                $connectionString .= "{$key}={$value}";
            }
            //iniciamos a conexão com o banco de dados
            $connectDb = pg_connect($connectionString);
        }
        return $connectDb;
    }

}