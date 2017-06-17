<?php

namespace AI\Contracts;

interface StringManagement
{
    /**
     * @param string $string
     * @return array
     */
    public static function getArgv(string $string);
}
