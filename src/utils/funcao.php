<?php

function isEmpty($info)
{
    return empty($info) || $info == '' || $info == null || (is_array($info) && count($info) == 0);
}

function getDadosPostJson(): array
{
    $json = file_get_contents('php://input');
    $data = json_decode($json, true);
    return is_array($data) ? $data : [];
}