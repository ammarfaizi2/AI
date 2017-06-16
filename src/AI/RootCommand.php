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
     * Set super user
     *
     * @param string|array $superuser
     */
    public function set_superuser($superuser)
    {
        $this->superuser = $superuser;
    }
}
