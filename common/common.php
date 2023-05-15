<?php

function p($value, $exit = true)
{
    // header("content-type:text/html;charset=utf-8");
    echo '<pre>';print_r($value);
    $exit && exit;
}

function d($value, $exit = true)
{
    // header("content-type:text/html;charset=utf-8");
    echo '<pre>';var_dump($value);
    $exit && exit;
}

