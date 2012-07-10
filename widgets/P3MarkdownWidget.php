<?php
/**
 * Class File
 *
 * @author Tobias Munk <schmunk@usrbin.de>
 * @link http://www.phundament.com/
 * @copyright Copyright &copy; 2005-2011 diemeisterei GmbH
 * @license http://www.phundament.com/license/
 */

/**
 * Description ...
 *
 * Detailed info
 * <pre>
 * $var = code_example();
 * </pre>
 * {@link DefaultController}
 *
 * @author Tobias Munk <schmunk@usrbin.de>
 * @package p3
 * @since 3.1
 */
class P3MarkdownWidget extends CMarkdown {

    public $filePath;

    function run(){
        parent::run();
        echo $this->transform(file_get_contents($this->filePath));
    }
}
?>
