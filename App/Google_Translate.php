<?php
namespace App;

defined('data') or die('Error : data not defined !');
use System\CM_Curl;

/**
* @author Ammar Faizi <ammarfaizi2@gmail.com> https://www.facebook.com/ammarfaizi2
* @license RedAngel PHP Concept
*/

class Google_Translate
{
    public $list_lang;
    private $err;
    private $from;
    private $to;
    private $text;
    private $result;
    public function __construct()
    {
        is_dir(data.'/google/') or mkdir(data.'/google/');
        is_dir(data.'/google/cookies/') or mkdir(data.'/google/cookies/');
        $this->list_lang = array(
            'jw' => 'Jawa',
            'en' => 'Inggris',
            'auto' => 'Auto',
            'af' => 'Afrikans',
            'sq' => 'Albania',
            'am' => 'Amhara',
            'ar' => 'Arab',
            'hy' => 'Armenia',
            'az' => 'Azerbaijan',
            'eu' => 'Basque',
            'nl' => 'Belanda',
            'be' => 'Belarussia',
            'bn' => 'Bengali',
            'bs' => 'Bosnia',
            'bg' => 'Bulgaria',
            'my' => 'Burma',
            'ceb' => 'Cebuano',
            'cs' => 'Cek',
            'ny' => 'Chichewa',
            'zh-CN' => 'China',
            'da' => 'Dansk',
            'eo' => 'Esperanto',
            'et' => 'Esti',
            'fa' => 'Farsi',
            'tl' => 'Filipino',
            'fy' => 'Frisia',
            'ga' => 'Gaelig',
            'gd' => 'Gaelik Skotlandia',
            'gl' => 'Galisia',
            'ka' => 'Georgia',
            'gu' => 'Gujarati',
            'ha' => 'Hausa',
            'haw' => 'Hawaii',
            'hi' => 'Hindi',
            'hmn' => 'Hmong',
            'iw' => 'Ibrani',
            'ig' => 'Igbo',
            'id' => 'Indonesia',
            'is' => 'Islan',
            'it' => 'Italia',
            'ja' => 'Jepang',
            'de' => 'Jerman',
            'kn' => 'Kannada',
            'ca' => 'Katala',
            'kk' => 'Kazak',
            'km' => 'Khmer',
            'ky' => 'Kirghiz',
            'ko' => 'Korea',
            'co' => 'Korsika',
            'ht' => 'Kreol Haiti',
            'hr' => 'Kroat',
            'ku' => 'Kurdi',
            'lo' => 'Laos',
            'la' => 'Latin',
            'lv' => 'Latvi',
            'lt' => 'Lituavi',
            'lb' => 'Luksemburg',
            'hu' => 'Magyar',
            'mk' => 'Makedonia',
            'mg' => 'Malagasi',
            'ml' => 'Malayalam',
            'mt' => 'Malta',
            'mi' => 'Maori',
            'mr' => 'Marathi',
            'ms' => 'Melayu',
            'mn' => 'Mongol',
            'ne' => 'Nepal',
            'no' => 'Norsk',
            'ps' => 'Pashto',
            'pl' => 'Polski',
            'pt' => 'Portugis',
            'fr' => 'Prancis',
            'pa' => 'Punjabi',
            'ro' => 'Rumania',
            'ru' => 'Rusia',
            'sm' => 'Samoa',
            'sr' => 'Serb',
            'st' => 'Sesotho',
            'sn' => 'Shona',
            'sd' => 'Sindhi',
            'si' => 'Sinhala',
            'sk' => 'Slovak',
            'sl' => 'Sloven',
            'so' => 'Somali',
            'es' => 'Spanyol',
            'su' => 'Sunda',
            'fi' => 'Suomi',
            'sw' => 'Swahili',
            'sv' => 'Swensk',
            'tg' => 'Tajik',
            'ta' => 'Tamil',
            'te' => 'Telugu',
            'th' => 'Thai',
            'tr' => 'Turki',
            'uk' => 'Ukraina',
            'ur' => 'Urdu',
            'uz' => 'Uzbek',
            'vi' => 'Vietnam',
            'cy' => 'Wales',
            'xh' => 'Xhosa',
            'yi' => 'Yiddi',
            'yo' => 'Yoruba',
            'el' => 'Yunani',
            'zu' => 'Zulu',
        );
    }

    /**
    *	@param string
    *	@return boolean
    */
    private function check_lang($lang)
    {
        return isset($this->list_lang[$lang]);
    }

    /**
    *	@param string,string
    *	@return instance
    */
    public function prepare($text, $lang='auto_id')
    {
        $lang = $lang===null ? 'auto_id' : strtolower($lang);
        $lang = explode("_", $lang);
        $check1 = $this->check_lang($lang[0]);
        $check2 = $this->check_lang($lang[1]);
        if (!$check1 and $check2) {
            $this->err = 'err_lang_1';
        } elseif ($check1 and !$check2) {
            $this->err = 'err_lang_2';
        } elseif (!$check1 and !$check2) {
            $this->err = 'err_lang_1-2';
        } else {
            $this->from = $lang[0];
            $this->to = $lang[1];
            $this->text = urlencode($text);
        }
        return $this;
    }

    /**
    *	@return boolean
    */
    public function execute()
    {
        if (!isset($this->text)) {
            return false;
        }
        $ch = new CM_Curl("https://translate.google.com/m?hl=id&sl=".$this->from."&tl=".$this->to."&ie=UTF-8&q=".$this->text);
        $ch->set_cookie(data.'/google/cookies/google.txt');
        $a = explode('<div dir="ltr" class="t0">', $ch->execute(), 2);
        if (!isset($a[1])) {
            $this->err = "Error data !";
            return false;
        }
        $a = explode('<', $a[1], 2);
        $this->result = html_entity_decode($a[0], ENT_QUOTES, 'UTF-8');
        return true;
    }

    /**
    *	@return string
    */
    public function error()
    {
        return (isset($this->err)) ? $this->err : false;
    }

    /**
    *	@return mixed
    */
    public function fetch_result()
    {
        return $this->result;
    }
}
