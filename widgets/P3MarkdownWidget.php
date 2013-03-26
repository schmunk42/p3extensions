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
 * @package p3extensions.widgets
 * @category web.widgets
 */
class P3MarkdownWidget extends CMarkdown {

    public $filePath;
    public $css = ".p3-markdown-widget hr {margin-bottom: 100%} .p3-markdown-widget h3 {margin-top: 1em} blockquote p {margin: 2em 0 2em !important; padding: 0px 0 0 10px; border-left: 5px solid orange}";

    function run() {
        parent::run();
        Yii::app()->clientScript->registerCss('p3-markdown-widget', $this->css);
        echo "<div class='p3-markdown-widget'>";
        echo $this->transform(file_get_contents($this->filePath));
        echo "</div>";

        $js = '
$.each($(".p3-markdown-widget h2"), function(i){
        var $this = $(this);
        h2 = $this.html();
        $this.html("<a id=item href=#item"+i+">"+h2+"</a>");
        $this.attr("id","item"+i);
        $this.attr("href","#item"+(i+1));
$this.before("<br/><br/><a class=\"btn btn-mini next\" href=#item"+(i-1)+"><i class=icon-arrow-up></a>")
$this.before("<a class=\"btn btn-mini next\" href=#item"+i+">"+h2+" <i class=icon-arrow-right></a><hr/>")
$this.after("<a class=\"btn btn-mini\" href=#item"+(i+1)+"><i class=icon-arrow-right></i></a><br/><br/>")
$this.after("<a class=\"btn btn-mini\" href=#item"+(i-1)+"><i class=icon-arrow-left></i></a>")
})
';
        Yii::app()->clientScript->registerScript('markdown-widget', $js);
    }

}

?>
