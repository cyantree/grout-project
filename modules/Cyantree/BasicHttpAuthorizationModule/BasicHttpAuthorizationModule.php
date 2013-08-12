<?php
namespace Grout\Cyantree\BasicHttpAuthorizationModule;

use Cyantree\Grout\App\Module;
use Cyantree\Grout\App\Route;
use Cyantree\Grout\App\Task;
use Grout\Cyantree\BasicHttpAuthorizationModule\Actions\CheckAuthorizationAction;
use Grout\Cyantree\BasicHttpAuthorizationModule\Types\BasicHttpAuthorizationConfig;

class BasicHttpAuthorizationModule extends Module
{
    /** @var BasicHttpAuthorizationConfig */
    public $moduleConfig;

    public function init()
    {
        /** @var BasicHttpAuthorizationConfig moduleConfig */
        $this->moduleConfig = $this->app->config->get($this->type, $this->id, new BasicHttpAuthorizationConfig());

        foreach($this->moduleConfig->urls as $url){
            $this->secureUrl($url);
        }
    }

    /**
     * @param Task $task
     * @param Route $page
     */
    public function routeRetrieved($task, $page)
    {
        $secured = $page->data->get('secured');
        $whitelisted = $task->data->get('whitelistedByBasicHttpAuthorization');

        if($secured){
            if($whitelisted){
                return false;
            }else{
                $a = new CheckAuthorizationAction();
                if($page->data->get('username')){
                    $a->username = $page->data->get('username');
                    $a->password = $page->data->get('password');
                }else{
                    $a->username = $this->moduleConfig->username;
                    $a->password = $this->moduleConfig->password;
                }

                $a->task = $task;
                $a->module = $this;
                return !$a->execute();
            }
        }else if($secured === false){
            $task->data->set('whitelistedByBasicHttpAuthorization', true);

            return false;
        }

        return true;
    }


    public function secureUrl($url, $username = null, $password = null, $name = null)
    {
        $this->addRoute($url, 'SecuredPage', array('secured' => true, 'username' => $username, 'password' => $password, 'name' => $name));
    }

    public function whitelistUrl($url)
    {
        $this->addRoute($url, 'SecuredPage', array('secured' => false), 10);
    }
}