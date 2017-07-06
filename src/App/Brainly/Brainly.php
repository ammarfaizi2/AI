<?php

namespace App\Brainly;

/**
 * @author Ammar Faizi <ammarfaizi2@gmail.com>
 * @package App\Brainly
 */

defined("storage") or die("Storage not defined!");

use Curl;

class Brainly
{
    /**
     * @var string
     */
    private $q;

    /**
     * @var int
     */
    private $limit;

    /**
     * @var string
     */
    private $hash;

    /**
     * @var array
     */
    private $search_result;

    /**
     * @var array
     */
    private $cache_data;

    /**
     * Constructor.
     *
     * @param string $query
     * @param int    $limit
     */
    public function __construct($q, $limit = 100)
    {
        $this->q = $q;
        $this->limit = (int) $limit;
        $this->hash = sha1($q.$limit);
        is_dir(storage."/Brainly") or mkdir(storage."/Brainly");
        is_dir(storage."/Brainly/cache") or mkdir(storage."/Brainly/cache");
    }

    /**
     * Execute
     */
    public function execute()
    {
        if ($this->check_cache()) {
            $this->search_result = json_decode(file_get_contents(storage."/Brainly/cache/".$this->hash.".txt"), true);
            if (!is_array($this->search_result)) {
                $this->search_result = $this->online_search();
            }
        } else {
            $this->search_result = $this->online_search();
        }
    }

    /**
     * @return array
     */
    public function get_result()
    {
        return $this->search_result;
    }

    /**
     * Load cache data.
     */
    private function load_cache_data()
    {
        if (file_exists(storage."/Brainly/cache_control.txt")) {
            $this->cache_data = json_decode(file_get_contents(storage."/Brainly/cache_control.txt"), true);
            $this->cache_data = is_array($this->cache_data) ? $this->cache_data : array();
        } else {
            $this->cache_data = array();
        }
    }

    /**
     * Check cache.
     * @return bool
     */
    private function check_cache()
    {
        $this->load_cache_data();
        if (isset($this->cache_data[$this->hash])) {
            return ($this->cache_data[$this->hash] > time()) and file_exists(storage."/Brainly/cache/".$this->hash.".txt");
        }
        return false;
    }

    /**
     * Save cache.
     */
    private function save_cache()
    {
        file_put_contents(storage."/Brainly/cache_control.txt", json_encode($this->cache_data, 128));
    }

    /**
     * Online search.
     * @return array
     */
    private function online_search()
    {
        $ch = new Curl("https://brainly.co.id/api/28/api_tasks/suggester?limit=".$this->limit."&query=".urlencode($this->q));
        $out = json_decode($ch->exec(), true);
        file_put_contents(storage."/Brainly/cache/".$this->hash.".txt", json_encode($out, 128), LOCK_EX);
        $this->cache_data[$this->hash] = time()+1209600;
        $this->save_cache();
        return $out;
    }
}
