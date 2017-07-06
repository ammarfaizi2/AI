<?php

namespace AI;

use AI\Traits\Chat;
use AI\Traits\SimpleCommand;
use System\Contracts\AIContract;
use System\Exceptions\AIException;

/**
 * @author Ammar Faizi <ammarfaizi2@gmail.com>
 * @version 0.0.2.1
 * @license BSD
 * @package AI
 */

class AI implements AIContract
{
    use Chat, SimpleCommand;

    const VERSION = "0.0.2.1";

    /**
     * @var string
     */
    private $abs_input;

    /**
     * @var string
     */
    private $abs_first_word;

    /**
     * @var string
     */
    private $abs_param;

    /**
     * @var string
     */
    private $input;
    
    /**
     * @var string
     */
    private $first_word;

    /**
     * @var string
     */
    private $param;

    /**
     * @var string
     */
    private $actor;

    /**
     * @var string
     */
    private $timezone;

    /**
     * @var string
     */
    private $lang = "id";

    /**
     * @var object
     */
    private $invoke;

    /**
     * @var array
     */
    private $output = array();

    /**
     * Constructor.
     * @throws System\Exception\AIException
     * @param object $invoke
     */
    public function __construct()
    {
        if (! (defined("data") and defined("logs") and defined("storage"))) {
            $this->syslog("Fatal Error", $error = $this->sysstr("error_constants"));
            throw new AIException($error, 1);
            die("Avoid catch AIException");
        }
        is_dir(data) or mkdir(data);
        is_dir(logs) or mkdir(logs);
        is_dir(storage) or mkdir(storage);
        is_dir(data) or shell_exec("mkdir -p ".data);
        is_dir(logs) or shell_exec("mkdir -p ".logs);
        is_dir(storage) or shell_exec("mkdir -p ".storage);
        $this->invoke = func_get_args();
    }

    /**
     * @param string $input
     * @param string $actor
     */
    public function input($input, $actor = "")
    {
        $this->actor     = $actor;
        $this->input     = strtolower(trim($input));
        $this->abs_input = $input;
    }

    /**
     * @param string
     */
    public function set_timezone($timezone)
    {
        $this->timezone = $timezone;
    }

    /**
     * @param string
     */
    public function set_lang($lang_id)
    {
        $this->lang = $lang_id;
    }

    /**
     * @return bool
     */
    public function execute()
    {
        $a = $this->_prexecute();
        if (!$a) {
            foreach ($this->invoke as $key => $inv) {
                if (!is_object($inv)) {
                    $this->syslog("Fatal Error", $error = "__construct param is not fully object.");
                    throw new AIException($error, 1);
                    die("Avoid catch AIException");
                } else {
                    $inv($this);
                }
            }
        }
        return $a;
    }

    /**
     * Private execute.
     */
    private function _prexecute()
    {
        /**
         * Fixed input
         */
        $a = explode(" ", $this->input, 2);
        $this->first_word = trim($a[0]);
        $this->param = isset($a[1]) ? $a[1] : false;

        /**
         * Absolute input
         */
        $a = explode(" ", $this->abs_input, 2);
        $this->abs_first_word = $a[0];
        $this->abs_param = isset($a[1]) ? $a[1] : false;

        if (!$this->simple_command()) {
            if (!$this->simple_chat()) {
                if (!$this->elastic_command()) {
                    return false;
                }
            }
        }
        return true;
    }

    /**
     * Output
     * @return mixed
     */
    public function output()
    {
        return $this->output;
    }

    /**
     * @param string
     * @return string
     */
    private function sysstr($key)
    {
        $class = "\\AI\\Lang\\".$this->lang;
        return $class::$system[$key];
    }

    /**
     * AI log
     */
    private function syslog($action, $info = "")
    {
        $handle = fopen(logs."/sys.log", "a");
        fwrite($handle, "[".date("Y-m-d H:i:s")."] ".$action." | ".$info."\n");
        fclose($handle);
    }
}
