<?php

namespace AI;

defined('data') or die('Error : data not defined !');

use AI\Chat;
use AI\Command;
use App\Brainly;
use App\ChitChat;
use AI\PHPVirtual;
use App\WhatAnime;
use AI\RootCommand;
use App\MyAnimeList;
use App\SaferScript;
use AI\Hub\ChatFace;
use AI\AIAbstraction;
use AI\Hub\Singleton;
use App\JadwalSholat;
use Teacrypt\Teacrypt;
use App\GoogleTranslate;
use AI\Contracts\Timezone;
use AI\Exceptions\AIException;
use AI\Contracts\StringManagement;
use AI\Contracts\StatementManagement;

/**
 * @version 0.1
 * @package AI
 * @author  Ammar Faizi <ammarfaizi2@gmail.com>
 */

class AI extends AIAbstraction implements Timezone, StatementManagement, StringManagement
{
    const DATA              = '/ai/';
    const VERSION           = "0.1";
    const ERROR_EXCEPTION   = 4;
    const DEFAULT_TIMEZONE  = "Asia/Jakarta";
    const USER_AGENT        = "Mozilla/5.0 (X11; Crayner; Linux i686; rv:46.0) Crayner System AI Firefox/51.0";

    /**
     * Load singleton pattern
     */
    use Singleton;

    use Chat;
    use Command;
    use RootCommand;

    /**
     * Message type
     *
     * @var string
     */
    private $type;

    /**
     * Command
     *
     * @var string
     */
    private $cmd_e;

    /**
     * Message in lower case
     *
     * @var string
     */
    private $msg;

    /**
     * Absolute message
     *
     * @var string
     */
    private $absmsg;

    /**
     * Actor name
     *
     * @var string
     */
    private $actor;

    /**
     * ChitChat string
     *
     * @deprecated
     * @var        string
     */
    private $chitchat;

    /**
     * AI Reply
     *
     * @var string
     */
    private $reply;

    /**
     * Timezone
     *
     * @var string
     */
    private $timezone;

    /**
     * Error Message
     *
     * @var string
     */
    private $error_message;


    /**
     * Suggest
     *
     * @var bool
     */
    private $suggest = false;

    /**
     * Nama hari dalam bahasa indonesia
     *
     * @var array
     */
    private $hari = array(
            "Minggu",
            "Senin",
            "Selasa",
            "Rabu",
            "Kamis",
            "Jum'at",
            "Sabtu"
        );

    /**
     * Nama bulan dalam bahasa indonesia
     *
     * @var array
     */
    private $bulan = array(
            "Januari",
            "Februari",
            "Maret",
            "April",
            "Mei",
            "Juni",
            "Juli",
            "Agustus",
            "September",
            "Oktober",
            "November",
            "Desember"
        );

    /**
     * Constructor
     */
    public function __construct()
    {
        /**
         * Create directory for AI data
         */
        (is_dir(data.self::DATA) or mkdir(data.self::DATA)) xor
        (is_dir(data.self::DATA.'/logs') or mkdir(data.self::DATA.'/logs')) xor
        (is_dir(data.self::DATA.'/status') or (mkdir(data.self::DATA.'/status') and
        (file_put_contents(data.self::DATA.'/status/chit_chat_on', '1')))) xor
        (is_dir(data.self::DATA.'/chat_logs') or mkdir(data.self::DATA.'/chat_logs'));

        if (!is_dir(data.self::DATA)) {
            throw new AIException("Cannot create data directory ".data.self::DATA, self::ERROR_EXCEPTION);
        }

        /**
         * ChitChat directory
         *
         * @deprecated
         */
        $this->chitchat = file_exists(data.self::DATA.'/status/chit_chat_on');
    }
    
    /**
     * Prepare message to AI
     *
     * @param  string $text
     * @param  string $actor
     * @return object  AI Instance
     */
    public function prepare(string $text, string $actor=null): self {
        $this->msg      = trim(strtolower($text));
        $this->absmsg   = $text;
        $this->actor    = $actor;
        return $this;
    }

    /**
     * Execute message to AI
     *
     * @throws AI\Exceptions\AIException
     * @return bool
     */
    public function execute(): bool {
        if (!isset($this->absmsg)) {
            throw new AIException("Cannot access execute method directly, you must prepared a message first!", self::ERROR_EXCEPTION);
            $this->die();
        }

        if (!isset($this->timezone)) {
            $this->set_timezone(self::DEFAULT_TIMEZONE);
        }

        $cmd = explode(' ', $this->msg, 2);
        $cmd = $cmd[0];
        $this->cmd_e = $cmd;
        if ($this->root_command($cmd)) {
            $rt = true;
        } elseif ($this->command($cmd)) {
            $rt = true;
        }

        /**
         * @deprecated
         */
        /*elseif ($this->chitchat) {
            $st = new ChitChat('Carik');
            $st->prepare($this->msg)->execute();
            if (true) {
                $this->reply = $st->fetch_reply();
                var_dump($st);
                $rt = $this->reply===null ? false : true;
            } else {
                $rt = false;
            }
        }*/

        else {
            $rt = $this->chat();
        }

        /* reply a suggest message */
        if ($this->suggest && !$rt) {
            $rt = $this->suggest_act();
        }

        /* save chat to log */
        $this->clog();

        return $rt;
    }

    /**
     * Get AI response
     *
     * @return mixed
     */
    public function fetch_reply()
    {
        return isset($this->reply) ? $this->reply : false;
    }

    /**
     * @return bool
     */
    private function suggest_act(): bool
    {
        $return = false;
        $suggets_diff = 3;
        foreach ($this->command_list as $key => $value) {
            $count_diff = levenshtein($this->cmd_e, $key);
            $lv[$key] = $count_diff;
            if ($count_diff < $suggets_diff && !isset($pick_suggest)) {
                $pick_suggest = true;
            }
        }
        if (isset($pick_suggest) && $pick_suggest) {
            $command = array_search(min($lv), $lv);
            $p_msg = substr($this->absmsg, strlen($command));
            $this->reply = "Mungkin yang anda maksud adalah \"".trim($command." ".$p_msg)."\"";
            $return      = true;
        } else {
            if (is_array($this->superuser) && in_array($this->actor, $this->superuser)) {
                foreach ($this->rootcommand_list as $key => $value) {
                    $count_diff = levenshtein($this->cmd_e, $key);
                    $lv[$key] = $count_diff;
                    if ($count_diff < $suggets_diff && !isset($pick_suggest)) {
                        $pick_suggest = true;
                    }
                }
                if (isset($pick_suggest) && $pick_suggest) {
                    $command = array_search(min($lv), $lv);
                    $p_msg = substr($this->absmsg, strlen($command));
                    $this->reply = "Mungkin yang anda maksud adalah \"".trim($command." ".$p_msg)."\"";
                    $return      = true;
                }
            }
        }
        return $return;
    }

    /**
     * Set super user
     *
     * @param string|array $superuser
     */
    public function set_superuser($superuser)
    {
        if (!is_array($superuser) && is_string($superuser)) {
            throw new AIException("Set super user only can use with string or array type!", self::ERROR_EXCEPTION);
            $this->die();
        }
        $this->superuser = $superuser;
    }

    /**
     * Error Log (future)
     */
    private function errorLog($message, $errno = 1)
    {
        file_put_contents(data.self::DATA.'/error_log', "\nError : {$errno} {$message}\n\n", FILE_APPEND | LOCK_EX);
    }
    
    /**
     * Set default timezone
     *
     * @param string $timezone
     */
    public function set_timezone(string $timezone)
    {
        if (in_array($timezone, $this->allowed_timezones)) {
            $this->timezone = $timezone;
            date_default_timezone_set($timezone);
        } else {
            throw new AIException("Not allowed timezone !", self::ERROR_EXCEPTION);
        }
    }

    /**
     * Convert time character to absolute time
     *
     * @param  string $string
     * @return string  $string
     */
    protected function fdate(string $string)
    {
        $pure = $string;
        $a = explode("#d(", $string);
        if (!isset($a[1])) {
            return $string;
        }
        $a = explode(")", $a[1]);
        $b = explode("+", $a[0]);
        if (count($b)==1) {
            $b = explode("-", $a[0]);
            (count($b)==1) and ($out = $b[0] xor $tc = false) or ($tc = true xor $op = "-");
        } else {
            ($op = "+" xor $tc = true);
        }
        if ($tc) {
            $replacer = "#d(".$b[0].$op.$b[1].")";
            $c = strtotime(date("Y-m-d H:i:s").$op.$b[1], strtotime("Y-m-d H:i:s"));
            $b = $b[0];
        } else {
            $replacer = "#d(".$b[0].")";
            $c = strtotime(date("Y-m-d H:i:s"));
            $b = $b[0];
        }
        switch ($b) {

            /**
             *  Untuk hari.
             */
        case 'day': case 'days':
                $c = $this->hari[date("w", $c)];
            break;

            /**
             *  Untuk jam.
             */
        case 'jam':
            $c = date("h:i:s", $c);
            break;

            /**
             *  Untuk bulan.
             */
        case 'bulan': case 'month':
                $c = $this->bulan[(int)date("m", $c)];
            break;

            /**
             *  Untuk tanggal.
             */
        case 'date_c':
            $c = date("d", $c)." ".($this->bulan[(int)date("m", $c)])." ".date("Y", $c);
            break;

            /**
             *  Tidak dikenal.
             */
        default:
            $c = "unknown_param({$c})";
            break;
        }
        $return = str_replace($replacer, $c, $pure);
        !(strpos($return, "#d(")===false) and $return = $this->fdate($return);
        return $return;
    }

    /**
     * @param string
     * @return array
     */
    public static function getArgv(string $string): array
    {
        $unprintable_chars1 = chr(0).chr(0);
        $unprintable_chars2 = chr(1).chr(1);
        $_argv = [];
        $strtmp = $string;
        /**
         * db = double quotes
         * sb = single quotes
         */
        $get_db = function (&$strtmp) use ($unprintable_chars1) {
            $q = "";
            $x = strpos($strtmp, "\"");
            $v = strpos($strtmp, " ");
            if ($x === false || ($v!==false && $v < $x)) {
                if ($v === false) {
                    $tmp = $strtmp;
                    $strtmp = "";
                    return $tmp;
                } else {
                    $tmp = $strtmp;
                    $strtmp = substr($strtmp, $v+1);
                    return substr($tmp, 0, $v);
                }
            }
            $x = strpos($strtmp, "\"", $x+1);
            for ($i=1;$i<$x;$i++) {
                $q.=$strtmp[$i];
            }
            $strtmp = substr($strtmp, $x+1);
            return str_replace($unprintable_chars1, "\"", $q);
        };

        $get_sb = function (&$strtmp) use ($unprintable_chars2) {
            $q = "";
            $x = strpos($strtmp, "'");
            $v = strpos($strtmp, " ");
            if ($x === false || ($v!==false && $v < $x)) {
                if ($v === false) {
                    $tmp = $strtmp;
                    $strtmp = "";
                    return $tmp;
                } else {
                    $tmp = $strtmp;
                    $strtmp = substr($strtmp, $v+1);
                    return substr($tmp, 0, $v);
                }
            }
            $x = strpos($strtmp, "\"", $x+1);
            for ($i=1;$i<$x;$i++) {
                $q.=$strtmp[$i];
            }
            $strtmp = substr($strtmp, $x+1);
            return str_replace($unprintable_chars2, "\"", $q);
        };
        $dbpos = strpos($string, "\"");
        $sbpos = strpos($string, "'");
        if ($dbpos!==false || $sbpos!==false) {
            do {
                $dbpos = strpos($string, "\"");
                $sbpos = strpos($string, "'");
                if (($dbpos!==false && ($dbpos < $sbpos)) || $sbpos === false) {
                    $strtmp = str_replace("\\\"", $unprintable_chars1, $strtmp);
                    $zx =  $get_db($strtmp);
                    !empty($zx) and $_argv[] = $zx;
                } elseif (($sbpos!==false && ($sbpos < $dbpos)) || $dbpos === false) {
                    $strtmp = str_replace("\\\"", $unprintable_chars2, $strtmp);
                    $zx =  $get_sb($strtmp);
                    !empty($zx) and $_argv[] = $zx;
                }
            } while ($strtmp!=="");
        } else {
            do {
                $zx =  $get_db($strtmp);
                !empty($zx) and $_argv[] = $zx;
            } while ($strtmp!=="");
        }
        return $_argv;
    }

    /**
     * Turn on command suggestion
     */
    public function turnOnSuggest()
    {
        $this->suggest = true;
    }

    /**
     * Turn off command suggestion
     */
    public function turnOffSuggest()
    {
        $this->suggest = false;
    }

    /**
     * Chat Log
     *
     * void
     */
    private function clog()
    {
        $file = data.self::DATA.'/chat_logs/'.date('Y-m-d').'.txt';
        $data = file_exists($file) ? json_decode(file_get_contents($file), true) : array();
        $data = $data===null ? array() : $data;
        $data[] = array(
                'time'  => (date('Y-m-d H:i:s')),
                'actor' => $this->actor,
                'msg'   => $this->absmsg,
                'reply' => $this->reply,
            );
        file_put_contents($file, json_encode($data, 128));
    }

    /**
     * Count average.
     *
     * @param  array $array
     * @return int|float
     */
    public static function average($array)
    {
        return array_sum($array)/count($array);
    }

    /**
     * Future
     *
     * @return string
     */
    public function errorInfo()
    {
        return isset($this->error_message) ? $this->error_message : "";
    }

    /**
     * __toString()
     */
    public function __toString()
    {
        return isset($this->reply) ? $this->reply : "Not Available";
    }

    /**
     * var_dump return
     */
    public function __debugInfo()
    {
        return array("reply"=>$this->__toString());
    }

    /**
     * Destruct
     */
    public function __destruct()
    {
    }

    private function die()
    {
        $this->__destruct();
        /*avoid try catch*/
        die("Don't try catch AIException!");
    }

    /**
     * Allowed Timezones
     * @var array
     */
    public $allowed_timezones = array("Africa/Abidjan","Africa/Accra","Africa/Addis_Ababa","Africa/Algiers","Africa/Asmara","Africa/Bamako","Africa/Bangui","Africa/Banjul","Africa/Bissau","Africa/Blantyre","Africa/Brazzaville","Africa/Bujumbura","Africa/Cairo","Africa/Casablanca","Africa/Ceuta","Africa/Conakry","Africa/Dakar","Africa/Dar_es_Salaam","Africa/Djibouti","Africa/Douala","Africa/El_Aaiun","Africa/Freetown","Africa/Gaborone","Africa/Harare","Africa/Johannesburg","Africa/Juba","Africa/Kampala","Africa/Khartoum","Africa/Kigali","Africa/Kinshasa","Africa/Lagos","Africa/Libreville","Africa/Lome","Africa/Luanda","Africa/Lubumbashi","Africa/Lusaka","Africa/Malabo","Africa/Maputo","Africa/Maseru","Africa/Mbabane","Africa/Mogadishu","Africa/Monrovia","Africa/Nairobi","Africa/Ndjamena","Africa/Niamey","Africa/Nouakchott","Africa/Ouagadougou","Africa/Porto-Novo","Africa/Sao_Tome","Africa/Tripoli","Africa/Tunis","Africa/Windhoek","Asia/Aden","Asia/Almaty","Asia/Amman","Asia/Anadyr","Asia/Aqtau","Asia/Aqtobe","Asia/Ashgabat","Asia/Atyrau","Asia/Baghdad","Asia/Bahrain","Asia/Baku","Asia/Bangkok","Asia/Barnaul","Asia/Beirut","Asia/Bishkek","Asia/Brunei","Asia/Chita","Asia/Choibalsan","Asia/Colombo","Asia/Damascus","Asia/Dhaka","Asia/Dili","Asia/Dubai","Asia/Dushanbe","Asia/Famagusta","Asia/Gaza","Asia/Hebron","Asia/Ho_Chi_Minh","Asia/Hong_Kong","Asia/Hovd","Asia/Irkutsk","Asia/Jakarta","Asia/Jayapura","Asia/Jerusalem","Asia/Kabul","Asia/Kamchatka","Asia/Karachi","Asia/Kathmandu","Asia/Khandyga","Asia/Kolkata","Asia/Krasnoyarsk","Asia/Kuala_Lumpur","Asia/Kuching","Asia/Kuwait","Asia/Macau","Asia/Magadan","Asia/Makassar","Asia/Manila","Asia/Muscat","Asia/Nicosia","Asia/Novokuznetsk","Asia/Novosibirsk","Asia/Omsk","Asia/Oral","Asia/Phnom_Penh","Asia/Pontianak","Asia/Pyongyang","Asia/Qatar","Asia/Qyzylorda","Asia/Riyadh","Asia/Sakhalin","Asia/Samarkand","Asia/Seoul","Asia/Shanghai","Asia/Singapore","Asia/Srednekolymsk","Asia/Taipei","Asia/Tashkent","Asia/Tbilisi","Asia/Tehran","Asia/Thimphu","Asia/Tokyo","Asia/Tomsk","Asia/Ulaanbaatar","Asia/Urumqi","Asia/Ust-Nera","Asia/Vientiane","Asia/Vladivostok","Asia/Yakutsk","Asia/Yangon","Asia/Yekaterinburg","Asia/Yerevan","America/Adak","America/Anchorage","America/Anguilla","America/Antigua","America/Araguaina","America/Argentina/Buenos_Aires","America/Argentina/Catamarca","America/Argentina/Cordoba","America/Argentina/Jujuy","America/Argentina/La_Rioja","America/Argentina/Mendoza","America/Argentina/Rio_Gallegos","America/Argentina/Salta","America/Argentina/San_Juan","America/Argentina/San_Luis","America/Argentina/Tucuman","America/Argentina/Ushuaia","America/Aruba","America/Asuncion","America/Atikokan","America/Bahia","America/Bahia_Banderas","America/Barbados","America/Belem","America/Belize","America/Blanc-Sablon","America/Boa_Vista","America/Bogota","America/Boise","America/Cambridge_Bay","America/Campo_Grande","America/Cancun","America/Caracas","America/Cayenne","America/Cayman","America/Chicago","America/Chihuahua","America/Costa_Rica","America/Creston","America/Cuiaba","America/Curacao","America/Danmarkshavn","America/Dawson","America/Dawson_Creek","America/Denver","America/Detroit","America/Dominica","America/Edmonton","America/Eirunepe","America/El_Salvador","America/Fort_Nelson","America/Fortaleza","America/Glace_Bay","America/Godthab","America/Goose_Bay","America/Grand_Turk","America/Grenada","America/Guadeloupe","America/Guatemala","America/Guayaquil","America/Guyana","America/Halifax","America/Havana","America/Hermosillo","America/Indiana/Indianapolis","America/Indiana/Knox","America/Indiana/Marengo","America/Indiana/Petersburg","America/Indiana/Tell_City","America/Indiana/Vevay","America/Indiana/Vincennes","America/Indiana/Winamac","America/Inuvik","America/Iqaluit","America/Jamaica","America/Juneau","America/Kentucky/Louisville","America/Kentucky/Monticello","America/Kralendijk","America/La_Paz","America/Lima","America/Los_Angeles","America/Lower_Princes","America/Maceio","America/Managua","America/Manaus","America/Marigot","America/Martinique","America/Matamoros","America/Mazatlan","America/Menominee","America/Merida","America/Metlakatla","America/Mexico_City","America/Miquelon","America/Moncton","America/Monterrey","America/Montevideo","America/Montserrat","America/Nassau","America/New_York","America/Nipigon","America/Nome","America/Noronha","America/North_Dakota/Beulah","America/North_Dakota/Center","America/North_Dakota/New_Salem","America/Ojinaga","America/Panama","America/Pangnirtung","America/Paramaribo","America/Phoenix","America/Port-au-Prince","America/Port_of_Spain","America/Porto_Velho","America/Puerto_Rico","America/Punta_Arenas","America/Rainy_River","America/Rankin_Inlet","America/Recife","America/Regina","America/Resolute","America/Rio_Branco","America/Santarem","America/Santiago","America/Santo_Domingo","America/Sao_Paulo","America/Scoresbysund","America/Sitka","America/St_Barthelemy","America/St_Johns","America/St_Kitts","America/St_Lucia","America/St_Thomas","America/St_Vincent","America/Swift_Current","America/Tegucigalpa","America/Thule","America/Thunder_Bay","America/Tijuana","America/Toronto","America/Tortola","America/Vancouver","America/Whitehorse","America/Winnipeg","America/Yakutat","America/Yellowknife","Arctic/Longyearbyen","Antarctica/Casey","Antarctica/Davis","Antarctica/DumontDUrville","Antarctica/Macquarie","Antarctica/Mawson","Antarctica/McMurdo","Antarctica/Palmer","Antarctica/Rothera","Antarctica/Syowa","Antarctica/Troll","Antarctica/Vostok","Atlantic/Azores","Atlantic/Bermuda","Atlantic/Canary","Atlantic/Cape_Verde","Atlantic/Faroe","Atlantic/Madeira","Atlantic/Reykjavik","Atlantic/South_Georgia","Atlantic/St_Helena","Atlantic/Stanley","Australia/Adelaide","Australia/Brisbane","Australia/Broken_Hill","Australia/Currie","Australia/Darwin","Australia/Eucla","Australia/Hobart","Australia/Lindeman","Australia/Lord_Howe","Australia/Melbourne","Australia/Perth","Australia/Sydney","Europe/Amsterdam","Europe/Andorra","Europe/Astrakhan","Europe/Athens","Europe/Belgrade","Europe/Berlin","Europe/Bratislava","Europe/Brussels","Europe/Bucharest","Europe/Budapest","Europe/Busingen","Europe/Chisinau","Europe/Copenhagen","Europe/Dublin","Europe/Gibraltar","Europe/Guernsey","Europe/Helsinki","Europe/Isle_of_Man","Europe/Istanbul","Europe/Jersey","Europe/Kaliningrad","Europe/Kiev","Europe/Kirov","Europe/Lisbon","Europe/Ljubljana","Europe/London","Europe/Luxembourg","Europe/Madrid","Europe/Malta","Europe/Mariehamn","Europe/Minsk","Europe/Monaco","Europe/Moscow","Europe/Oslo","Europe/Paris","Europe/Podgorica","Europe/Prague","Europe/Riga","Europe/Rome","Europe/Samara","Europe/San_Marino","Europe/Sarajevo","Europe/Saratov","Europe/Simferopol","Europe/Skopje","Europe/Sofia","Europe/Stockholm","Europe/Tallinn","Europe/Tirane","Europe/Ulyanovsk","Europe/Uzhgorod","Europe/Vaduz","Europe/Vatican","Europe/Vienna","Europe/Vilnius","Europe/Volgograd","Europe/Warsaw","Europe/Zagreb","Europe/Zaporozhye","Europe/Zurich","Indian/Antananarivo","Indian/Chagos","Indian/Christmas","Indian/Cocos","Indian/Comoro","Indian/Kerguelen","Indian/Mahe","Indian/Maldives","Indian/Mauritius","Indian/Mayotte","Indian/Reunion","Pacific/Apia","Pacific/Auckland","Pacific/Bougainville","Pacific/Chatham","Pacific/Chuuk","Pacific/Easter","Pacific/Efate","Pacific/Enderbury","Pacific/Fakaofo","Pacific/Fiji","Pacific/Funafuti","Pacific/Galapagos","Pacific/Gambier","Pacific/Guadalcanal","Pacific/Guam","Pacific/Honolulu","Pacific/Kiritimati","Pacific/Kosrae","Pacific/Kwajalein","Pacific/Majuro","Pacific/Marquesas","Pacific/Midway","Pacific/Nauru","Pacific/Niue","Pacific/Norfolk","Pacific/Noumea","Pacific/Pago_Pago","Pacific/Palau","Pacific/Pitcairn","Pacific/Pohnpei","Pacific/Port_Moresby","Pacific/Rarotonga","Pacific/Saipan","Pacific/Tahiti","Pacific/Tarawa","Pacific/Tongatapu","Pacific/Wake","Pacific/Wallis","Africa/Asmera","Africa/Timbuktu","America/Argentina/ComodRivadavia","America/Atka","America/Buenos_Aires","America/Catamarca","America/Coral_Harbour","America/Cordoba","America/Ensenada","America/Fort_Wayne","America/Indianapolis","America/Jujuy","America/Knox_IN","America/Louisville","America/Mendoza","America/Montreal","America/Porto_Acre","America/Rosario","America/Santa_Isabel","America/Shiprock","America/Virgin","Antarctica/South_Pole","Asia/Ashkhabad","Asia/Calcutta","Asia/Chongqing","Asia/Chungking","Asia/Dacca","Asia/Harbin","Asia/Istanbul","Asia/Kashgar","Asia/Katmandu","Asia/Macao","Asia/Rangoon","Asia/Saigon","Asia/Tel_Aviv","Asia/Thimbu","Asia/Ujung_Pandang","Asia/Ulan_Bator","Atlantic/Faeroe","Atlantic/Jan_Mayen","Australia/ACT","Australia/Canberra","Australia/LHI","Australia/North","Australia/NSW","Australia/Queensland","Australia/South","Australia/Tasmania","Australia/Victoria","Australia/West","Australia/Yancowinna","Brazil/Acre","Brazil/DeNoronha","Brazil/East","Brazil/West","Canada/Atlantic","Canada/Central","Canada/East-Saskatchewan","Canada/Eastern","Canada/Mountain","Canada/Newfoundland","Canada/Pacific","Canada/Saskatchewan","Canada/Yukon","CET","Chile/Continental","Chile/EasterIsland","CST","CDT","Cuba","EET","Egypt","Eire","EST","EST","EDT","Etc/GMT","Etc/GMT","Etc/GMT","Etc/GMT","Etc/GMT","Etc/GMT","Etc/GMT","Etc/GMT","Etc/GMT","Etc/GMT","Etc/GMT","Etc/GMT","Etc/GMT","Etc/GMT","Etc/GMT-","Etc/GMT-","Etc/GMT-","Etc/GMT-","Etc/GMT-","Etc/GMT-","Etc/GMT-","Etc/GMT-","Etc/GMT-","Etc/GMT-","Etc/GMT-","Etc/GMT-","Etc/GMT-","Etc/GMT-","Etc/GMT-","Etc/GMT","Etc/Greenwich","Etc/UCT","Etc/Universal","Etc/UTC","Etc/Zulu","Europe/Belfast","Europe/Nicosia","Europe/Tiraspol","Factory","GB","GB-Eire","GMT","GMT","GMT-","GMT","Greenwich","Hongkong","HST","Iceland","Iran","Israel","Jamaica","Japan","Kwajalein","Libya","MET","Mexico/BajaNorte","Mexico/BajaSur","Mexico/General","MST","MST","MDT","Navajo","NZ","NZ-CHAT","Pacific/Johnston","Pacific/Ponape","Pacific/Samoa","Pacific/Truk","Pacific/Yap","Poland","Portugal","PRC","PST","PDT","ROC","ROK","Singapore","Turkey","UCT","Universal","US/Alaska","US/Aleutian","US/Arizona","US/Central","US/East-Indiana","US/Eastern","US/Hawaii","US/Indiana-Starke","US/Michigan","US/Mountain","US/Pacific","US/Pacific-New","US/Samoa","UTC","W-SU","WET","Zulu");

    /**
     * Command
     */
    private $command_list = array(
        '<?php'      => 2,
        'ask'        => 2,
        '#ask'       => 2,
        'menu'       => 2,
        'jadwal'     => 2,
        'hitung'     => 2,
        'i_anime'    => 2,
        'i_manga'    => 2,
        'q_anime'    => 2,
        'q_manga'    => 2,
        'teacrypt'   => 2,
        'translate'  => 2,
        'whatanime'  => 2,
        'ctranslate' => 3,
    );

    /**
     * @param string
     * @return boolean
     */
    private function command($cmd)
    {
        if (isset($this->command_list[$cmd])) {
            $rt = false;
            $msg = explode(' ', $this->absmsg, 2);
            unset($msg[0]);
            switch ($cmd) {
            case '<?php':
                    $this->type = "text";
                    $st = new PHPVirtual($this->absmsg);
                    $st->execute();
                    $r = $st->show_result();
                    $this->reply = $r ? $r : "~~";
                break;
                /**
                *   Untuk pertanyaan
                */
            case 'ask': case 'ask':
                    $this->type = "text";
                    if (!isset($msg[1]) or empty($msg[1])) {
                        $this->reply = "Mohon maaf, untuk bertanya silahkan ketik ask [spasi] pertanyaan\n\nKetik \"menu\" untuk melihat daftar perintah";
                        $this->type = "text";
                    } else {
                        $n = new Brainly();
                        $n->prepare($msg = implode(' ', $msg));
                        if ($n->execute()) {
                            $result = $n->fetch_result();
                            $this->reply = "Hasil pencarian dari pertanyaan ".($this->actor)."\n\nPertanyaan yang mirip :\n".($result[0])."\n\nJawaban : \n".($result[1])."\n";
                        } else {
                            $this->reply = "Mohon maaf, saya tidak bisa menjawab pertanyaan \"".($msg)."\".";
                        }
                    }
                break;
                
                /**
                 *   Show menu
                 */
            case 'menu':
                    $this->type = "text";
                    $this->reply = "Menu : \n1. ask [spasi] pertanyaan : Untuk bertanya\n2. menu : Untuk menampilkan menu ini\n3. ctranslate [spasi] from [spasi] to [spasi] kalimat : Untuk translate dari berbagai bahasa\n4. translate [spasi] kalimat : Untuk translate dari bahasa apapun ke bahasa Indonesia\n5. whatanime [spasi] url_gambar : Untuk mencari judul anime berdasarkan gambar";
                break;

                /**
                 *   Jadwal
                 */
            case 'jadwal':
                $msg = explode(" ", strtolower($msg[1]));
                switch ($msg[0]) {
                case 'sholat': case 'solat': case 'shalat':
                            $this->type = "text";
                            $st = new JadwalSholat();
                            $get_kota = ucfirst(strtolower(trim($msg[1])));
                            if ($jadwal = $st->get_jadwal($get_kota)) {
                                $ret = "Jadwal Sholat untuk daerah {$get_kota} dan sekitarnya\nTanggal ".(date("d F Y"))."\n\n";
                                $jadwal = array_merge(array('imsyak'=>(date("h:i", strtotime($jadwal['subuh'])-300))), $jadwal);
                                foreach ($jadwal as $key => $jam) {
                                    $ret .= ucfirst($key) . " : " . $jam . "\n";
                                }
                                $this->reply = $ret;
                            } else {
                                if ($suggest_kota = self::jadwal_sholat_suggest($st->get_list_kota(), $get_kota)) {
                                    if (is_array($suggest_kota)) {
                                        if ($jadwal = $st->get_jadwal($suggest_kota[0])) {
                                            $ret = "Jadwal Sholat untuk daerah {$suggest_kota[0]} dan sekitarnya\nTanggal ".(date("d F Y"))."\n\n";
                                            $jadwal = array_merge(array('imsyak'=>(date("h:i", strtotime($jadwal['subuh'])-300))), $jadwal);
                                            foreach ($jadwal as $key => $jam) {
                                                $ret .= ucfirst($key) . " : " . $jam . "\n";
                                            }
                                            $this->reply = $ret;
                                        }
                                    } else {
                                        $this->reply = "Mohon maaf, jadwal sholat kota \"{$get_kota}\" tidak ditemukan. Mungkin yang anda maksud adalah kota {$suggest_kota}";
                                    }
                                } else {
                                    $this->reply = "Mohon maaf, jadwal sholat kota \"{$get_kota}\" tidak ditemukan.";
                                }
                            }
                    break;
                        
                default:
                        $this->reply = null;
                    break;
                }
                break;


                /**
                 *   Hitung
                 */
            case 'hitung':
                if (!isset($msg[1])) {
                    $this->reply = "Untuk menghitung, ketik 'hitung [spasi] perhitungan'\n\nContoh :\nhitung 100+100";
                } else {
                    $a = array('x','=','?');
                    $b = array('*','','');
                    $st = new SaferScript("\$q = ".str_replace($a, $b, $msg[1]));
                    $st->allowHarmlessCalls(true);
                    if (count($st->parse())) {
                        $this->reply = "Perhitungan tidak ditemukan !";
                    } else {
                        $this->reply = $st->execute();
                    }
                }
                break;

                /**
                 *   Mencari ID Anime
                 */
            case 'q_anime': case 'q_manga':
                    $search = (new MyAnimeList('ammarfaizi2', 'triosemut123'))->search($msg[1], $cmd);
                    $this->reply = $search ? ($search)."\n\nUntuk mencari info anime ketik \"i_anime [spasi] id_anime\"\nContoh :\ni_anime 100" : "Mohon maaf anime \"".$msg[1]."\" tidak ditemukan !";
                break;

                /**
                 *   Untuk mencari info anime
                 */
            case 'i_anime': case 'i_manga':
                    $this->type = "text";
                    $msg[1] = trim($msg[1]);
                    if (is_numeric($msg[1])) {
                        $search = (new MyAnimeList('ammarfaizi2', 'triosemut123'))->get_info($msg[1], $cmd);
                        $this->type = is_array($search) ? "text+image" : "text";
                        $this->reply = $search ? $search : "Mohon maaf, anime dengan id ".$msg[1]." tidak ditemukan !";
                    } else {
                        $this->reply = "Mohon maaf, pencarian info anime hanya bisa dilakukan dengan ID anime !";
                    }
                break;

                /**
                 *   Untuk translate berbagai bahasa
                 */
            case 'ctranslate':
                $this->type = "text";
                    $t = explode(' ', $this->absmsg, 4);
                    $n = new GoogleTranslate();
                    $st = $n->prepare($t[3], ($t[1].'_'.$t[2]));
                    $st->execute();
                if ($err = $st->error()) {
                    $this->reply = $err;
                } else {
                    $this->reply = $st->fetch_result();
                }
                break;

                /**
                 *  Enkripsi dan Dekripsi Teacrypt
                 */
            case 'teacrypt':
                $this->type = "text";
                $msg = self::getArgv($this->absmsg);
                if (strtolower($msg[1]) == "enc") {
                    if (!isset($msg[3]) or empty($msg[3])) {
                        $this->reply = "Key harus diisi !";
                    } else {
                        $this->reply = Teacrypt::encrypt($msg[2], $msg[3]);
                    }
                } elseif (strtolower($msg[1]) == "dec") {
                    if (!isset($msg[3]) or empty($msg[3])) {
                        $this->reply = "Key harus diisi !";
                    } else {
                        $this->reply = Teacrypt::decrypt($msg[2], $msg[3]);
                    }
                } else {
                    $this->reply = "Perintah tidak dikenal !";
                }
                break;

                /**
                 *   Untuk translate bahasa asing ke indonesia
                 */
            case 'translate':
                $this->type = "text";
                    $t = explode(' ', $this->absmsg, 2);
                    $n = new GoogleTranslate();
                    $st = $n->prepare($t[1]);
                    $st->execute();
                if ($err = $st->error()) {
                    $this->reply = $err;
                } else {
                    $this->reply = $st->fetch_result();
                }
                break;

                /**
                 *   Mencari judul anime dengan URL gambar
                 */
            case 'whatanime':
                $this->type = "text";
                    $t = new WhatAnime(trim($msg[1]));
                    $t->execute();
                    $result = $t->fetch_result();
                    $reply = '';
                foreach ($result['docs'][0] as $key => $value) {
                    $reply .= ucwords(str_replace("_", " ", $key))." : ".$value."\n";
                }
                    $this->reply = $reply;
                break;
                /**
                 *   Command not found !
                 */
            default:
                    $this->type = "text";
                    $this->reply = "Error System !";
                break;
            }
            return isset($this->reply) ? true : false;
        }
    }


    /**
     * Extend method
     */
    private static function jadwal_sholat_suggest($list_jadwal, $kota_request)
    {
        foreach ($list_jadwal as $key => $value) {
            $count_diff = levenshtein($key, $kota_request);
            $list[$key] = $count_diff;
            if ($count_diff < 5 && !isset($pick_suggest)) {
                $pick_suggest = true;
            }
        }
        if (isset($pick_suggest) && $pick_suggest) {
            $min = min($list);
            $get_mirip = array_search($min, $list);
            $get_kota_mirip = $min < 3 ? array($get_mirip) : $get_mirip;
        }
        return isset($get_kota_mirip) ? $get_kota_mirip : false;
    }

    private static function trigonometri()
    {
        /**
         * Mini trigonometri
         */
        $trigonometri['sin'] = array(
                        0  => "0",
                        30 => "(1/2)",
                        45 => "(1/2)*sqrt(2)",
                        60 => "(1/2)*sqrt(3)",
                        90 => "1",
                    );
        $trigonometri['cos'] = array(
                        0  => "1",
                        30 => "(1/2)*sqrt(3)",
                        45 => "(1/2)*sqrt(2)",
                        60 => "(1/2)",
                        90 => "0"
                    );
        $trigonometri['tan'] = array(
                        0  => "0",
                        30 => "(1/3)*sqrt(3)",
                        45 => "1",
                        60 => "sqrt(3)",
                        90 => "~"
                    );
    }

    /**
     * End Command
     */


    /**
     * Root Command
     */

    /**
     * Super User
     *
     * @var array|string
     */
    private $superuser;

    private $rootcommand_list = array(
                'shell_exec' => 2,
                'shexec'     => 2,
                'eval'       => 2,
            );

    /**
     *   @param  string
     *   @return boolean
     */
    private function root_command($cmd)
    {
        $this->superuser === null and $this->superuser = "all";
        
        if (((is_array($this->superuser) && in_array($this->actor, $this->superuser))||$this->superuser=="all") && isset($this->rootcommand_list[$cmd])) {
            $rt = false;
            $msg = explode(' ', $this->absmsg, 2);
            unset($msg[0]);
            switch ($cmd) {
                /**
                 * Shell Exec
                 */
            case 'shexec': case 'shell_exec':
                    $sh = shell_exec($msg[1]);
                    $this->reply = empty($sh) ? "~" : $sh;
                break;

                /**
                 * Eval
                 */
            case 'eval':
                    $sh = new SaferScript($msg[1]);
                    $sh->allowHarmlessCalls();
                    $sh->parse();
                    $ex = $sh->execute();
                    $this->reply = (empty($ex)) ? '~' : $ex;
                break;

                /**
                 * Command not found !
                 */
            default:
                    $this->reply = "Error System !";
                    $this->errorLog("Error, \"{$cmd}\" is not RootCommand!", 1);
                break;
            }
            return isset($this->reply) ? true : false;
        }
    }

    /**
     * End Root Command
     */


    /**
     * Chat
     */

    /**
     * Wordlist
     *
     * @var array
     */
    private $wl = array();

    /**
     * Timereply memory
     *
     * @var array
     */
    private $timereply;

    /**
     *
     *
     * @var float
     */
    private $similarity_minimal = 65.0;

    /**
     *
     *
     * @var array
     */
    private $similar_word_temporary;

    /**
    *   Wordlist loader
    */
    private function load_wordlist()
    {
        $this->msg = strip_tags($this->msg);
        $this->wl = array(

            "hai,hay,hi,hy"=>array(
            1,array(
                    "Hai juga ^@. Apa kabar?",
                    "Hay juga ^@. Apa kabar?"
            ),true,5,25,null,false),

            "halo,hallo,allo,helo,hola,alo,ello"=>array(
            1,array(
                    "Halo juga kang ^@ :)",
                    "Halo juga kang ^@",
                    "Halo juga kak ^@"
            ),false,10,50,null,false),

            /**
             *  Tanya kabar.
             */
            "pa+kabar,pa+kbr,pa+kbar"=>array(
            1,array(
                    "Kabar baik disini.",
                    "Kabar baik, kang ^@ apa kabar?"
            ),false,10,50,null,false),

            /**
             *  Menanyakan jam (Bahasa Inggris)
             */
            "what+time"=>array(
            1,array(
                    "Time #d(jam_pq)"
            ),false,10,50,null,false),

            /**
             *  Menanyakan jam (Bahasa Indonesia)
             */
            "jam+ber,jam+brp,jam+pro,jam+piro"=>array(
            1,array(
                    "0-11"=>array("Sekarang jam #d(jam) pagi"),
                    "11-14"=>array("Sekarang jam #d(jam) siang"),
                    "14-17"=>array("Sekarang jam #d(jam) sore"),
                    "18-24"=>array("Sekarang jam #d(jam) malam")
            ),false,10,50,null,true),

            /**
             *  Menanyakan hari besok.
             */
            "besok+hari+apa"=>array(
            1,array(
                    "besok hari #d(day+1day)"
            ),false,10,50,null,false),

            /**
             *  Menanyakan hari kemarin.
             */
            "kemarin+hari+apa"=>array(
            1,array(
                    "kemarin hari #d(day-1day)"
            ),false,10,50,null,false),

            /**
             *  Menanyakan hari sekarang.
             */
            "hari+apa"=>array(
            1,array(
                    "sekarang hari #d(day)"
            ),false,10,50,null,false),

            /**
             *  Menanyakan bulan sekarang.
             */
            "bulan+apa"=>array(
            1,array(
                    "sekarang bulan #d(bulan)"
            ),false,10,50,null,false),

            /**
             *  Menanyakan tanggal kemarin.
             */
            "kemarin+tanggal+brp,kemarin+tanggal+berapa,kemarin+tgl+brp,kemarin+tgl+berapa"=>array(
            1,array(
                    "Kemarin tanggal #d(date_c-1day)"
            ),false,10,50,null,false),

            /**
             *  Menanyakan tanggal besok.
             */
            "besok+tanggal+brp,besok+tanggal+berapa,besok+tgl+brp,besok+tgl+berapa"=>array(
            1,array(
                    "Besok tanggal #d(date_c+1day)"
            ),false,10,50,null,false),


            /**
             *  Menanyakan tanggal sekarang.
             */
            "tanggal+brp,tanggal+berapa,tgl+brp,tgl+berapa"=>array(
            1,array(
                    "Sekarang tanggal #d(date_c)"
            ),false,10,50,null,false),


            /**
             *  Sapaan di pagi hari.
             */
            "pagi"=>array(
            1,array(
                    "1-10"=>array(
                        "Selamat pagi kang ^@. Selamat beraktifitas."
                    ),
                    "11-12"=>array(
                        "Selamat pagi menjelang siang kang ^@"
                    ),
                    "13-14"=>array(
                        "Ini udah siang kang ^@ 😌"
                    ),
                    "15-18"=>array(
                        "Ini udah sore kang ^@ 😌"
                    ),
                    "19-23,0"=>array(
                        "Ini sudah malem kang ^@ 😌"
                    )
            ),false,8,35,null,true),

            /**
             *  Sapaan di siang hari.
             */
            "siang,ciang,siank"=>array(
            1,array(
                    "0-10"=>array(
                        "Ini masih pagi lho kang ^@ 😌"
                    ),
                    "11-14"=>array(
                        "Selamat siang kang ^@, selamat beraktifitas."
                    ),
                    "15-18"=>array(
                        "Ini udah sore kang ^@ 😌"
                    ),
                    "19-24"=>array(
                        "Ini udah malem kang ^@ 😌"
                    )
            ),false,8,35,null,true),

            /**
             *  Sapaan di sore hari.
             */
            "sore"=>array(
            1,array(
                    "0-10"=>array(
                        "Ini masih pagi lho kang ^@ 😌"
                    ),
                    "11-13"=>array(
                        "Ini masih siang lho kang ^@"
                    ),
                    "14-18"=>array(
                        "Selamat sore kang ^@, selamat beristirahat."
                    ),
                    "19-24"=>array(
                        "Ini udah malem kang ^@ 😌"
                    )
            ),true,8,35,null,true),


            /**
             *  Sapaan di malam hari.
             */
            "malem,malam"=>array(
            1,array(
                    "0-4"=>array(
                        "Ini udah pagi kang ^@, selamat pagi, selamat beraktifitas."
                    ),
                    "5-10"=>array(
                        "Ini masih pagi kang ^@"
                    ),
                    "11-14"=>array(
                        "Ini masih siang kang ^@",
                    ),
                    "15-18"=>array(
                        "Ini masih sore loh kang ^@"
                    ),
                    "19-23"=>array(
                        "Selamat malam kang ^@, selamat beristirahat."
                    )
            ),true,9,65,null,true),


            /**
             *  Sapaan di pagi hari.
             */
            "ohayo"=>array(
            1,array(
                    "0-9,24"=>array(
                        "Ohayou kang ^@, selamat beraktiftas 😙"
                    ),
                    "10-11"=>array(
                        "Selamat pagi menjelang siang ^@"
                    ),
                    "12-14"=>array(
                        "Ini udah siang kang ^@ :v"
                    ),
                    "15-18"=>array(
                        "Ini udah sore kang ^@"
                    ),
                    "19-23"=>array(
                        "Ini udah malem kang ^@"
                    )
            ),false,9,65,null,true),

            /**
             *  Sapaan di siang hari.
             */
            "koniciwa,konnichiwa,konichiwa,konniciwa"=>array(
            1,array(
                    "0-9,24"=>array(
                        "Ini masih pagi kang ^@"
                    ),
                    "10-18"=>array(
                        "Konnichiwa kang ^@, selamat beraktifitas"
                    ),
                    "19-23"=>array(
                        "Ini udah malem kang ^@"
                    )
            ),false,9,90,null,false),


            /**
             *  Sapaan di sore hari.
             */
            "konbawa,konbanwa"=>array(
            1,array(
                    "0-9,24"=>array(
                        "ini masih pagi kang ^@"
                    ),
                    "10-23"=>array(
                        "konbanwa kang ^@"
                    )
            ),true,8,65,null,true),

            /**
             *  Tertawa.
             */
            "haha,hihi,wkwk,wkeke,hhh"=>array(
            1,array(
                    "Dilarang ketawa !"
            ),false,10,75,null,false),

            /**
             *  Move on.
             */
            "move+on"=>array(
            1,array(
                    "Move on adalah jalan terbaik kak ^@, kamu harus kuat :)",
                    "Jangan mudah percaya dengan orang lain, move on itu susah.",
                    "Selamat move on.\n\nUdah itu aja."
            ),true,8,75,null,false),

            "jomblo"=>array(
            1,array(
                    "Ciyaah... @ jomblo nih 😂😂",
                    "Ciye jomblo :v",
                    "Ciye ^@ jomblo 😏"
            ),false,9,90,null,false),


            "larang"=>array(
                1,array(
                    "Wah ngelarang larang nih kang ^@ 😏",
                    "Kang ^@, mau dilarang?"
                ),false,7,45,null,false),


                "laper,lapar,lavar"=>array(
                1,array(
                "0-3"=>array(
                    "Segera sahur kang ^@",
                    "Sahur dulu kang ^@ 😊"
                ),
                "4-15"=>array(
                    "Sabar kang ^@, belum waktunya berbuka 😇",
                    "Sabar ya kang ^@, kita tunggu sampai waktunya berbuka."
                ),
                "16-17"=>array(
                    "Sabar kang ^@, bentar lagi magrib kok 😏",
                    "Sabar aja ya kang ^@, sebentar lagi udah magrib 😋😏"
                ),
                "18-24"=>array(
                    "Kalau laper ya makan 😊",
                    "Makan gamping dong. *eeehhhh",
                    "Makan tanah dong. *eeeehhh",
                    "Makan aeeh :v"
                )
                ),false,10,75,null,true),


                "bot,"=>array(
                1,array(
                    "Ya, ada apa kang ^@?",
                ),true,2,5,null,true),




                /**
             * Check 2
             */
                "assalamualaikum,asalamualaikum"=>array(
                2,array(
                    "Waalaikumsalam"
                ),
                80,10,75,null,false),


        );
    }

    /**
     *  Chat action
     */
    private function chat()
    {
        $this->load_wordlist();
        $this->exms = explode(' ', $this->msg);
        $this->cword = count($this->exms);
        $this->mslg = strlen($this->msg);
        foreach ($this->wl as $key => $val) {
            if ($this->{"check{$val[0]}"}($key, $val[2], $val[3], $val[4], $val[5], $val[6])) {
                if ($this->timereply) {
                    if ($this->gettimereply($val[1])) {
                        $val[1] = $this->timereply;
                    } else {
                        return false;
                    }
                }
                $act = explode(" ", $this->actor);
                $this->reply = $this->fdate(str_replace("@", $this->actor, str_replace("^@", $act[0], $val[1][rand(0, count($val[1])-1)])));
                return true;
            }
        }
        if (count($this->similar_word_temporary)) {
            $max_key = array_search(max($this->similar_word_temporary), $this->similar_word_temporary);
            $this->reply = $this->wl[$max_key][1][rand(0, count($this->wl[$max_key][1])-1)];
        }
        return isset($this->reply);
    }
        
    /**
     * Check chat
     *
     * @param  string $key
     * @param  bool   $wordcheck
     * @param  int    $maxwords
     * @param  int    $maxlength
     * @param  array  $wordexception
     * @param  bool   $time
     * @return bool
     */
    private function check1(string $key, bool $wordcheck=false, int $maxwords=null, int $maxlength=null, array $wordexception=null, bool $time=false)
    {
        if (($maxlength!==null and ($this->mslg) > $maxlength) or ($maxwords!==null and ($this->cword) > $maxwords)) {
            return false;
        }
        if (is_array($wordexception)) {
            foreach ($wordexception as $qw) {
                if (in_array($qw, $this->exms)) {
                    return false;
                }
            }
        }

        $ex = explode(',', $key);

        if ($wordcheck) {
            $stop = false;
            foreach ($ex as $qw) {
                $a = explode('+', $qw);
                $notwr = true;
                foreach ($a as $qw2) {
                    if (!in_array($qw2, $this->exms)) {
                        $notwr = false;
                        break;
                    }
                }
                if ($notwr) {
                    if ($time) {
                        $this->timereply = true;
                    }
                    return true;
                }
            }
        } else {
            $stop = false;
            foreach ($ex as $qw) {
                $a = explode('+', $qw);
                $notwr = true;
                foreach ($a as $qw2) {
                    if (strpos($this->msg, $qw2)===false) {
                        $notwr = false;
                        break;
                    }
                }
                if ($notwr) {
                    if ($time) {
                        $this->timereply = true;
                    }
                    return true;
                }
            }
        }
        
        return false;
    }

    /**
     * Check chat 2
     *
     * @param  string $key
     * @param  int    $minimal
     * @param  int    $maxwords
     * @param  int    $maxlength
     * @param  array  $wordexception
     * @param  bool   $time
     * @return bool
     */
    private function check2(string $key, int $minimal=null, int $maxwords=null, int $maxlength=null, array $wordexception=null, bool $time=false)
    {
        if (($maxlength!==null and ($this->mslg) > $maxlength) or ($maxwords!==null and ($this->cword) > $maxwords)) {
            return false;
        }
        if (is_array($wordexception)) {
            foreach ($wordexception as $qw) {
                if (in_array($qw, $this->exms)) {
                    return false;
                }
            }
        }

        $ex = explode(',', $key);
        $_similar_1 = array();
        $_different_1 = array();
        foreach ($ex as $wlist) {
            $similar_0 = array();
            $_different_0 = array();
            foreach ($this->exms as $in) {
                similar_text($wlist, $in, $percent);
                $_different_0[] = levenshtein($wlist, $in);
                $similar_0[] = $percent;
            }
            $count = count($this->exms);
            $_different_1[] = array_sum($_different_0) / $count;
            $_similar_1[] = max($similar_0);
        }
        $average = (array_sum($_similar_1) / count($ex) * max($similar_0) - array_sum($_different_1))/100;
        if ($average >= $this->similarity_minimal and ($minimal===null or $average >= $minimal)) {
            $this->similar_word_temporary[$key] = $average;
        }
        return false;
    }


    /**
     * Get time reply
     *
     * @param  array $replylist
     * @return bool
     */
    private function gettimereply(array $replylist)
    {
        foreach ($replylist as $time => $replist) {
            $tr = array();
            $a = explode(",", $time);
            foreach ($a as $b) {
                $c = explode("-", $b);
                if (count($c)==1) {
                    $tr[] = $c[0];
                } else {
                    $tr = array_merge($tr, range($c[0], $c[1]));
                }
                if (in_array((int)date("H"), $tr)) {
                    $this->timereply = $replist;
                    return true;
                }
            }
        }
        return false;
    }
    /**
     * End Chat
     */
}
