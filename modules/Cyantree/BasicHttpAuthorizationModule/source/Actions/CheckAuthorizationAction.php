<?php
namespace Grout\Cyantree\BasicHttpAuthorizationModule\Actions;

class CheckAuthorizationAction
{
    public $task;
    public $module;

    public $username;
    public $password;

    public function execute()
    {
        if($this->username === null || $this->password === null){
            return false;
        }else{
            if($this->task->request->server->has('PHP_AUTH_PW')){
                return $this->username === $this->task->request->server->get('PHP_AUTH_USER') &&
                $this->password === $this->task->request->server->get('PHP_AUTH_PW');
            }else{
                $authorization = $this->task->request->get->get('Grout_Authorization');

                if (preg_match('@^Basic (.+)$@', $authorization, $authorization)) {
                    $access = base64_decode($authorization[1]);
                    return $access == ($this->username . ':' . $this->password);
                }
            }
        }

        return false;
    }
}