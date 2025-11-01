<?php

function isEmpty($info)
{
    return empty($info) || $info == '' || $info == null || (is_array($info) && count($info) == 0);
}