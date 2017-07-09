<?php

namespace AI\Traits;

/**
 * @author Ammar Faizi <ammarfaizi2@gmail.com>
 */

use AI\AppConnector\Brainly;
use App\SaferScript\SaferScript;
use App\GoogleTranslate\GoogleTranslate;

trait SimpleCommand
{
    /**
     * @var array
     */
    private $simple_command = [
                                "ask" => 1,
                                "hitung" => 1,
                                "ctranslate" => 1
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
                    $st = new Brainly($this->param);
                    $st = $st->get_result();
                    if (count($st)) {
                        $this->output = [
                        "text" => [
                            "Hasil pertanyaan yang mirip dari ".$this->actor." : \n".$st[0]."\n\nJawaban : \n".$st[1]
                        ]
                        ];
                    } else {
                        $this->output = [
                        "text" => [
                            "Mohon maaf, saya tidak bisa menjawab pertanyaan \"{$this->param}\""
                        ]
                        ];
                    }
                } else {
                    $this->output = [
                        "text" => [
                            "Ketik `ask` [spasi] pertanyaan untuk bertanya!"
                        ]
                    ];
                }
                break;
            case 'hitung':
                $st = new SaferScript("\$q = ".$this->param.";");
                $st->allowHarmlessCalls();
                $st->parse();
                 $this->output = [
                        "text" => [
                            $st->execute()
                        ]
                    ];
                break;
            case 'ctranslate':
                $a = explode(" ", $this->param, 3);
                if (isset($a[2])) {
                    $st = new GoogleTranslate($a[2], $a[0], $a[1]);
                    $out = $st->getResult();
                    if ($out === false) {
                        $out = "Terjadi masalah pada server!\n\nHubungi admin!";
                    }
                } else {
                    $out = "Penulisan perintah ctranslate salah\n\nBerikut ini adalah penulisan yang benar.\nctranslate [from] [to] [string]\n\nContoh :\nctranslate id en halo, apa kabar?";
                }
                $this->output = [
                        "text" => [
                            $out
                        ]
                    ];
                break;
            default:
                    $this->output = [
                        "text" => [
                            $this->sysstr("error_simplecmd_not_found")
                        ]
                    ];
                    $this->error = $this->sysstr("error_simplecmd_not_found");
                break;
            }
        }
        return (bool) count($this->output);
    }
}
