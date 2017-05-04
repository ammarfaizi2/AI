<?php
namespace App;
use System\CM_Curl;
/**
* 
*/
class Google_Translate
{
	public $list_lang;
	private $text;
	public function __construct()
	{
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
	private function check_lang($lang)
	{
		return isset($this->list_lang[$lang]);
	}
	public function prepare($text,$lang='auto_id')
	{
		$lang = $lang===null ? 'auto_id' : $lang;
		$lang = explode("_", $lang);
		if ($this->lang) {
			# code...
		}
	}
	public function execute()
	{
		$ch = new CM_Curl("https://translate.google.com/m?hl=id&sl=auto&tl=id&ie=UTF-8&q=".$this->text);
	}
}