<?php

namespace App\GoogleTranslate;

/**
 * @author Ammar Faizi <ammarfaizi2@gmail.com>
 * @package App\GoogleTranslate
 */

use Curl;

class GoogleTranslate
{
    /**
     * @var string
     */
    private $a;

    /**
     * @var string
     */
    private $b;

    /**
     * @var string
     */
    private $c;

    /**
     * @var string
     */
    private $hash;

    /**
     * @var array
     */
    private $cache;

    /**
     * @param string $a
     * @param string $b
     * @param string $c
     */
    public function __construct($a, $b = "en", $c = "id")
    {
        $this->a = $a;
        $this->b = trim($b);
        $this->c = trim($c);
        $this->hash = sha1($a.$b.$c);
        is_dir(storage."/GoogleTranslate") or mkdir(storage."/GoogleTranslate");
        is_dir(storage."/GoogleTranslate/cache") or mkdir(storage."/GoogleTranslate/cache");
        is_dir(storage."/GoogleTranslate/cookie") or mkdir(storage."/GoogleTranslate/cookie");
        $this->load_cache();
    }

    private function load_cache()
    {
        if (file_exists(storage."/GoogleTranslate/cache_control.txt")) {
            $this->cache = json_decode(file_get_contents(storage."/GoogleTranslate/cache_control.txt"), true);
            $this->cache = is_array($this->cache) ? $this->cache : array();
        } else {
            $this->cache = array();
        }
    }

    /**
     * @param string $_r
     */
    private function save_cache($_r)
    {
        if ($_r!==false) {
            $this->cache[$this->hash] = 1;
            file_put_contents(storage."/GoogleTranslate/cache_control.txt", json_encode($this->cache, 128));
            file_put_contents(storage."/GoogleTranslate/cache/".$this->hash.".txt", $_r);
        }
    }

    /**
     * @return mixed
     */
    public function getResult()
    {
        if (!isset($this->cache[$this->hash])) {
            $ch = new Curl("https://translate.google.com/m?hl=id&sl=".$this->b."&tl=".$this->c."&ie=UTF-8&prev=_m&q=".urlencode($this->a));
            $ch->set_opt([
                    CURLOPT_COOKIEJAR => storage."/GoogleTranslate/cookie/cookie_data",
                    CURLOPT_COOKIEFILE => storage."/GoogleTranslate/cookie_data",
                    CURLOPT_REFERER => "https://translate.google.com/m"
                ]);
            $src = $ch->exec();
            var_dump($src);
            $a = explode('<div dir="ltr" class="t0">', $src, 2);
            if (isset($a[1])) {
                $a = explode("<", $a[1], 2);
                $_r = trim(html_entity_decode($a[0], ENT_QUOTES, 'UTF-8'));
                $a = explode('<div dir="ltr" class="o1">', $src, 2);
                if (isset($a[1])) {
                    $a = explode("<", $a[1], 2);
                    $_r2 = html_entity_decode($a[0], ENT_QUOTES, 'UTF-8');
                    $_r.= " (".trim($_r2).")";
                }
            }
            isset($_r) and $this->save_cache($_r);
        } else {
            $_r = file_get_contents(storage."/GoogleTranslate/cache/".$this->hash.".txt");
        }
        return isset($_r) ? $_r : false;
    }
}
