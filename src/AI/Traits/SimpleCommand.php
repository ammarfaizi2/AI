<?php

namespace AI\Traits;

trait SimpleCommand
{	
	/**
	 * @var array
	 */
	private $simple_command = [
								"ask" => 1
							];

	/**
	 * Simple command.
	 */
	public function simple_command()
	{
		if (isset($this->simple_command[$this->first_word])) {
			switch ($this->first_word) {
				case 'ask':
						if ($this->param) {
							$st = new \AI\AppConector\Brainly($this->param);
						} else {
							$this->output = [
								"text" => [
									"Ketik `ask` [spasi] pertanyaan untuk bertanya!"
								]
							];
						}
					break;
				
				default:
					# code...
					break;
			}
		}
		return (bool)count($this->output);
	}
}