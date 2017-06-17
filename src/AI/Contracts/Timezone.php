<?php

namespace AI\Contracts;

interface Timezone
{
    /**
     * Set default timezone
     *
     * @param string $timezone
     */
    public function set_timezone(string $timezone);
}
