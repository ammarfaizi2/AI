<?php
namespace AI;

use App\SaferScript;

trait RootCommand
{
    
    /**
    *   @param string
    *   @return boolean
    */
    private function root_command($cmd)
    {
        $command_list = array(
                'shell_exec' => 2,
                'shexec'     => 2,
                'ps'         => 2,
                'eval'       => 2,
            );
        $superuser = array("Ammar Faizi");
        if ((in_array($this->actor, $superuser)||$superuser=="all") && isset($command_list[$cmd])) {
            $rt = false;
            $msg = explode(' ', $this->absmsg, 2);
            unset($msg[0]);
            switch ($cmd) {
                /**
                *   Shell Exec
                */
                case 'shexec': case 'shell_exec':
                        $sh = shell_exec($msg[1]);
                        $this->reply = empty($sh) ? "~" : $sh;
                    break;

                /**
                *   ps
                */
                case 'ps':
                        $sh = shell_exec('ps '.$msg[1]);
                        $this->reply = empty($sh) ? "~" : $sh;
                    break;

                case 'eval':
                        $sh = new SaferScript($msg[1]);
                        $sh->allowHarmlessCalls();
                        $sh->parse();
                        $ex = $sh->execute();
                        $this->reply = (empty($ex)) ? '~' : $ex;
                    break;

                /**
                *   Command not found !
                */
                default:
                        $this->reply = "Error System !";
                    break;
            }
            var_dump($this->reply);
            die;
            return isset($this->reply) ? true : false;
        }
    }
}
