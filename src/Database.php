<?php

namespace App;

class Database
{

    public static function select(
        array $coluna = ['*'],
        string $tabela = '',
        array $join = [],
        array $condicao = [],
        ?int $limit = null,
        array $order = []
    ): array {

        if (
            !self::validate($coluna, false) ||
            !self::validate($tabela, false)
        ) {
            return [];
        }

        $sql = '
            SELECT ' . implode(', ', $coluna) . '
              FROM ' . $tabela . '
        ';

        $sql .= self::addJoin($join);
        $sql .= self::addCondition($condicao);
        $sql .= self::addOrder($order);

        if (is_numeric($limit)) {
            $sql .= " LIMIT {$limit} ";
        }

        $result = self::execute($sql);

        //Convertemos nosso resultado em array associativo
        if ($result) {
            if (pg_num_rows($result) === 0) {
                $result = [];
            } else {
                $result = pg_fetch_all($result);
            }
        } else {
            $result = [];
        }

        return $result;
    }

    public static function insert(
        string $tabela = '',
        array $dados = []
    ): bool {
        if (
            !self::validate($tabela, false) ||
            !self::validate($dados, true)
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

        $result = self::execute($sql, $dados);

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

        $update = [];

        foreach (array_keys($dados) as $key => $coluna) {
            $update[] = "{$coluna}=$" . $key + 1;
        }

        $sql = "
            UPDATE $tabela
               SET " . implode(',', $update) . "
        ";

        $sql .= self::addCondition($condicao);

        $result = self::execute($sql, $dados);

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

        $sql .= self::addCondition($condicao);

        $result = self::execute($sql);

        return $result ? true : false;
    }

    private static function addJoin(array $joins = []): string
    {
        $sql = '';
        if (count($joins) > 0) {
            foreach ($joins as $type => $join) {
                if (
                    !self::validate($type) &&
                    !self::validate($join, false)
                ) {
                    continue;
                }
                $sql .= !is_string($type) || isEmpty($type) ? ' JOIN ' : $type . ' JOIN ';
                foreach ($join as $operator => $condition) {
                    if (
                        !self::validate($operator) &&
                        !self::validate($condition, false)
                    ) {
                        continue;
                    }
                    $operator = !is_string($operator) ? '' : $operator;
                    $sql .= "{$operator} {$condition}";
                }

            }
        }
        return $sql;
    }

    private static function addCondition(array $condicao = []): string
    {
        $sql = '';
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
                if (!is_string($operator) || isEmpty($operator)) {
                    //Se o SQL só contém o where ainda e nada além disso, não adicionamos operador nenhum
                    $operator = trim($sql) == 'WHERE' ? '' : ' AND ';
                }

                $sql .= " {$operator} {$condition} ";
            }
        }
        return $sql;
    }

    private static function addOrder(array $ordem = []): string
    {
        $sql = '';
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

    private static function convertBool(mixed $value)
    {
        if (is_bool($value)) {
            return $value ? 't' : 'f';
        }
        return $value;
    }

    /**
     * Sanitiza strings básicas removendo caracteres de controle e normalizando espaços.
     * Para arrays, aplica recursivamente.
     */
    private static function sanitize(mixed $info): mixed
    {
        if (is_array($info)) {
            return array_map(fn($v) => self::sanitize($v), $info);
        }

        if (!is_string($info)) {
            return $info;
        }

        // Remove caracteres de controle e normaliza espaços
        $s = preg_replace('/[\x00-\x1F\x7F]/u', '', $info);
        $s = preg_replace('/\s+/u', ' ', $s);
        return trim($s);
    }

    /**
     * Verifica se o token string parece um identificador SQL seguro (tabela/coluna/alias).
     * Permite letras, números, underscore e ponto. Também permite alias com AS.
     */
    private static function isSafeIdentifier(string $s): bool
    {
        $s = trim($s);
        // identificador simples ou com alias: name OR schema.name OR name AS alias
        // permite também '*' isolado ou 'schema.*' / 'table.*'
        return (bool) preg_match('/^(?:\*|[A-Za-z0-9_]+(?:\.(?:\*|[A-Za-z0-9_]+))?(?:\s+AS\s+[A-Za-z0-9_]+)?)$/i', $s);
    }

    /**
     * Detecta padrões perigosos frequentemente utilizados em SQL injection.
     */
    private static function containsDangerousPatterns(string $s): bool
    {
        // comentários, ponto e vírgula, aspas, barras, padrões de palavras-chave perigosas
        // busca comentários, ponto-e-vírgula, aspas simples/duplas e barra invertida
        if (preg_match("/(--|\/\\*|\\*\/|;|'|\"|\\\\)/i", $s)) {
            return true;
        }

        if (preg_match('/\b(union|select|insert|update|delete|drop|alter|truncate|exec|execute|declare|xp_)\b/i', $s)) {
            return true;
        }

        return false;
    }

    private static function validate(mixed $info, bool $bAllowEmpty = true): bool
    {
        if (isEmpty($info)) {
            return $bAllowEmpty;
        }

        // considerar arrays: validar todos os elementos
        if (is_array($info)) {
            foreach ($info as $v) {
                if (!self::validate($v, $bAllowEmpty)) {
                    return false;
                }
            }
            return true;
        }

        // sanitize básico
        if (is_string($info)) {
            $s = self::sanitize($info);

            if ($s === '' || isEmpty($s)) {
                return $bAllowEmpty;
            }

            // rejeita padrões óbvios de injeção
            if (self::containsDangerousPatterns($s)) {
                return false;
            }

            // se for um identificador simples (tabela/coluna), exige formato seguro
            // Caso contrário (expressão com operadores), permitimos desde que sem padrões perigosos
            // Detecta se aparenta ser um identificador (sem espaços e sem operadores comuns)
            if (!preg_match('/[\s=<>\(\)\,]/', $s)) {
                return self::isSafeIdentifier($s);
            }

            // para condições/expressões mais complexas: já removemos padrões perigosos,
            // então aceitamos (mas recomenda-se sempre usar parâmetros em valores)
            return true;
        }

        return true;
    }

    private static function execute(string $sql = '', array $params = [])
    {
        try {
            $connect = self::connect();
            $result = false;
            if ($connect) {

                //convertemos valores para termos uma execução correta
                $values = array_map(function ($value) {
                    //Caso seja um valor booleano, precisamos converter para 't' ou 'f', pois se não o pg_query_params transforma em " "
                    //causando erro sql
                    $value = self::convertBool($value);
                    return $value;
                }, array_values($params));

                $result = pg_query_params($connect, $sql, array_values($values));
            }
            return $result;
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    private static function connect()
    {
        global $connectDb;
        if (!$connectDb) {
            //pegamos as informações database
            $connect = new Connection();

            //iniciamos a conexão com o banco de dados
            $connectDb = pg_connect($connect->getStringConnection());
        }
        return $connectDb;
    }

}