<?php

namespace Test;

class Http
{

    public static function getHost()
    {
        return $_SERVER['HTTP_HOST'].'</br>';
    }
}