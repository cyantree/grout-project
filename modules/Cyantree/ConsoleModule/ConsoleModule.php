<?php
namespace Grout\Cyantree\ConsoleModule;

use Cyantree\Grout\App\Module;
use Cyantree\Grout\Database\SqliteConnection;

class ConsoleModule extends Module
{
    /** @var SqliteConnection */
    public static $db;

    public function init()
    {
        $this->addRoute('%%command,.*%%/', 'ConsoleCommandPage');

        self::$db = new SqliteConnection();
        self::$db->connect('db.db');
    }
}