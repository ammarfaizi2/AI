<?php

namespace AI\Contracts;

interface StatementManagement
{
    /**
     * Prepare a statement
     *
     * @param string      $text
     * @param string|null $actor
     */
    public function prepare(string $text, string $actor = null);

    /**
     * Execute statement
     */
    public function execute();

    /**
     * Get reply message
     */
    public function fetch_reply();
}
