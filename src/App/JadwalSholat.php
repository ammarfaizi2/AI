<?php
namespace App;

defined('data') or die('Error : data not defined !');

use Curl\CMCurl;

/**
* @author Ammar Faizi <ammarfaizi2@gmail.com>
*/

class JadwalSholat
{
	/**
	* @var	string	Bulan sekarang
	*/
	private $bulan;

	public function __construct()
	{	
		$this->bulan = strtolower(date('M'));
		is_dir(data.'/jadwal_sholat') or mkdir(data.'/jadwal_sholat');
	}

	/**
	* @param	string		$kota	Pilih kota
	* @return	array|bool
	*/
	public function get_jadwal($kota)
	{

	}
}