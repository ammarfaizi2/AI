<?php

namespace AI;

use AI\AI;
use Curl\CMCurl;

class PHPVirtual
{
	/**
	 * @var string
	 */
	private $phpCode;

	/**
	 * @var string
	 */
	private $fileName;

	/**
	 * @var string
	 */
	private $storage;

	/**
	 *
	 * Constructor.
	 *
	 * @param	string	$phpCode
	 */
	public function __construct(string $phpCode)
	{
		$this->phpCode = $phpCode;
		$this->storage = data.AI::DATA."php_virtual";
		is_dir($this->storage) or mkdir($this->storage);
		is_dir($this->storage."/tmp") or mkdir($this->storage."/tmp");
		$this->openData();
		$this->makeFile();
	}

	public function execute()
	{
		if (!isset($_SERVER['HTTP_HOST'])) {
			throw new \Exception("PHPVirtual cannot execute without HTTP_HOST", 1);
		} else {
			$url = "http".(isset($_SERVER['HTTPS']) ? "s" : "")."://".$_SERVER['HTTP_HOST']."/".substr($this->target, strlen($_SERVER['DOCUMENT_ROOT'])+1);
			$ch  = new CMCurl($url);
			$ch->set_useragent();
			$this->result = str_replace($this->target, "/root/ai/php_virtual.php", $ch->execute());
		}
	}

	public function show_result()
	{
		return $this->result;
	}

	private function makeFile()
	{
		$hash = sha1($this->phpCode);
		if (!isset($this->data[$hash])) {
			file_put_contents($this->storage."/tmp/".$hash.".php", $this->phpCode);
			$this->data[$hash] = date("Y-m-d H:i:s");
		}
		$this->target = $this->storage."/tmp/".$hash.".php";
		file_put_contents($this->storage."/lg.json", json_encode($this->data, 128));
	}

	private function openData()
	{
		file_exists($this->storage."/lg.json") or file_put_contents($this->storage."/lg.json", "");
		$this->data = json_decode(file_get_contents($this->storage."/lg.json"), true);
		$this->data = is_array($this->data) ? $this->data : array();
	}

	public function __debugInfo()
	{
	}
}