<?php
namespace AI;

use App\SaferScript;

/**
 *   @author Ammar Faizi <ammarfaizi2@gmail.com>
 */

trait RootCommand
{
    /**
     * Super User 
     *
     * @var array|string
     */
    private $superuser;



    /**
    *   @param  string
    *   @return boolean
    */
    private function root_command($cmd)
    {
        $this->superuser === null and $this->superuser = "all";
        $command_list = array(
                'shell_exec' => 2,
                'shexec'     => 2,
                'ps'         => 2,
                'eval'       => 2,
            );
        if (((is_array($this->superuser) && in_array($this->actor, $this->superuser))||$this->superuser=="all") && isset($command_list[$cmd])) {
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
                 *   Command not found !
                 */
                default:
                        $this->reply = "Error System !";
                    break;
            }
            return isset($this->reply) ? true : false;
        }
    }

    /**
     * Set super user
     *
     * @param   string|array    $superuser
     */
    public function set_superuser($superuser)
    {
        $this->superuser = $superuser;
    }
}
