<?php
/**
 * Class file.
 *
 * @author Tobias Munk <schmunk@usrbin.de>
 * @link http://www.phundament.com/
 * @copyright Copyright &copy; 2005-2011 diemeisterei GmbH
 * @license http://www.phundament.com/license/
 */

/**
 * Command to sync project files by alias between a server and your local machine
 *
 *
 * @author Tobias Munk <schmunk@usrbin.de>
 * @package p3extensions.commands
 * @since 3.0.1
 */
class P3RsyncCommand extends CConsoleCommand
{
    /**
	 * Array of available locations
	 * @var type array
	 */
	public $servers;
	/**
	 * Yii aliases (directories) within the application which can be synced
	 * @var type array
	 */
    public $aliases;
	/**
	 * Additional rsync command line params
	 * @var type string
	 */
	public $params;

    public function getHelp() {
        echo <<<EOS
Usage: yiic rsync <server:src> <server:dest> <alias>

Specify the shorthands in config/console.php, make sure the
URLs point to the yii webapp directory (usually 'protected').

'commandMap' => array(
    'rsync'=>array(
        'class' => 'ext.phundament.p3extensions.commands.P3RsyncCommand',
        'servers' => array(
            'dev' => realpath(dirname(__FILE__).'/..'),             // local development path
            'prod' => 'user@example.com:/path/to/webapp/protected', // remote url
        ),
        'aliases' => array(
            'data' => 'application.data'                            // alias to be synced
        ),
    ),
),

Note: One server location has to be a local file path!

EOS;
    }

    /**
	 * Syncs from 'server1' to 'server2' the 'alias'
	 * @param type $args
	 */
	public function run($args) {
        if (!isset($this->servers)) {
            echo "No server specified in config!";
            exit;
        }

        if (!isset($this->aliases)) {
            echo "No alias defined in config!";
            exit;
        }

        if (!isset($args[2])) {
            $this->getHelp();
            exit;
        }
        $src = $args[0];
        $dest = $args[1];
        $alias = $args[2];

        $path = Yii::getPathOfAlias($this->aliases[$alias]);
        $relativePath = str_replace(Yii::app()->basePath,"",$path);

        $srcUrl = $this->servers[$src].$relativePath."/";
        $destUrl = $this->servers[$dest].$relativePath."/";

        echo "Start rsync of '".$alias."' (".$relativePath.") from '".$src."' to '".$dest."'? [Yes|No] ";
        if(!strncasecmp(trim(fgets(STDIN)),'y',1)) {
            $cmd = "rsync {$this->params} -av ".$srcUrl." ".$destUrl;
            echo "\n".$cmd."\n";
            system($cmd, $output);
        } else {
            echo "Skipped.\n";
        }
    }

}

?>