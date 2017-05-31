<?php
namespace AI\Hub;


interface AIFace
{


	public function set_timezone(string $timezone);

	public function prepare(string $text, $actor=null);

	public function execute();

	public function fetch_reply();


}