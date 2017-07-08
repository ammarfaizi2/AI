<?php

namespace AI\AppConnector;

/**
 * @author Ammar Faizi <ammarfaizi2@gmail.com>
 */

class Brainly
{
    /**
     * @var array
     */
    private $return = array();

    /**
     * @var string
     */
    private $hash;

    /**
     * @param string
     */
    public function __construct($q)
    {
        if (!$this->edge_cache($q)) {
            $st = new \App\Brainly\Brainly($q, 100, $this->hash);
            $st->execute();
            $st = $st->get_result();
            if (isset($st['data']['tasks']['items']) and ((bool) count($st['data']['tasks']['items']))) {
                $st = $st['data']['tasks']['items'];
                $sim = $lev = [];
                foreach ($st as $key => $val) {
                    $val = html_entity_decode(strip_tags($val['task']['content']), ENT_QUOTES, 'UTF-8');
                    $lev[$key] = levenshtein($val, $q);
                    similar_text($val, $q, $n);
                    $sim[$key] = $n;
                }
                $fx = function ($str) {
                    return html_entity_decode(str_replace("<br />", "\n", $str), ENT_QUOTES, 'UTF-8');
                };
                if (min($lev) <= 5) {
                    $key = array_search(min($lev), $st);
                    $rt = array($fx($st[$key]['task']['content']), $fx($st[$key]['responses'][0]['content']));
                } else {
                    $key = array_search(max($sim), $st);
                    $rt = array($fx($st[$key]['task']['content']), $fx($st[$key]['responses'][0]['content']));
                }
                $this->return = $rt;
            }
            $this->save_edge_cache();
        }
    }

    /**
     * @return array
     */
    public function get_result()
    {
        return $this->return;
    }

    /**
     * @param string
     */
    private function edge_cache($q)
    {
        $this->hash = sha1($q);
        if (file_exists(storage."/Brainly/edge_cache.txt")) {
            $a = json_decode(file_get_contents(storage."/Brainly/edge_cache.txt"), true);
            if (isset($a[$this->hash]) && $a[$this->hash]['expired'] > time()) {
                $this->return = $a[$this->hash]['content'];
                return true;
            }
        }
        return false;
    }

    /**
     * Save edge cache
     */
    private function save_edge_cache()
    {
        if (file_exists(storage."/Brainly/edge_cache.txt")) {
            $a = json_decode(file_get_contents(storage."/Brainly/edge_cache.txt"), true);
            $a[$this->hash] = ["content"=>$this->return, "expired"=>time()+(3600*24)];
            file_put_contents(storage."/Brainly/edge_cache.txt", json_encode($a, 128));
        } else {
            file_put_contents(storage."/Brainly/edge_cache.txt", json_encode(array(
                    $this->hash => ["content"=>$this->return, "expired"=>time()+(3600*24)]
                ), 128));
        }
    }
}





