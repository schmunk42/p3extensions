<?php
/**
 * P3PageSeoUrlRule.php class file.
 * @author Anne Datema <anne@uitzendsoftware.com>
 * @copyright Copyright &copy; Uitzend Software Diensten b.v. 2013
 * @license http://www.phundament.com/license/
 * @version 1.0.0
 */

/**
 * P3PageSeoUrlRule manages the parsing and creating of P3Page URL's.
 *
 * configuration in config/main.php
 * <pre>
 * ...
 * 'urlManager' => array(
 *     ...
 *     'rules' => array(
 *     ...
 *       array(
 *        'class'=>'vendor.phundament.p3extensions.components.P3PageSeoUrlRule',
 *       ),
 *       ...
 * </pre>
 */
class P3PageSeoUrlRule extends CBaseUrlRule
{
    /**
     * Creates a URL based on this rule.
     * @param CUrlManager $manager the manager
     * @param string $route the route
     * @param array $params list of parameters (name=>value) associated with the route
     * @param string $ampersand the token separating name-value pairs in the URL.
     * @return mixed the constructed URL. False if this rule does not apply.
     */
    public function createUrl($manager, $route, $params, $ampersand)
    {
        // This is a P3Page and not another route, because the P3Page->createUrl took care of that
        if ($route === 'p3pages/default/page'){
            $p3page = null;
            // search page with ID
            if (isset($params[P3Page::PAGE_ID_KEY]))
            {
                $p3page = P3Page::model()->findByPk($params[P3Page::PAGE_ID_KEY]);
            }
            // search page with NAME
            elseif (isset($params[P3Page::PAGE_NAME_KEY]))
            {
                $p3page = P3Page::model()->p3PageTranslations->findByAttributes(array('seoUrl'=>$params[P3Page::PAGE_NAME_KEY]));
            }

            if (!empty($p3page))
            {
                if ($seoUrl=$p3page->t('seoUrl')){
                    $url = array(
                        $params['lang'],
                        $seoUrl,
                    );
                    return implode('/',array_filter($url)); //this also filters out empty array items
                }
            }

        }
        // nothing applied, perhaps there is another rule more suitable. Skip this one after all.
        return false;
    }

    /**
     * Parses a URL based on this rule.
     * @param CUrlManager $manager the URL manager
     * @param CHttpRequest $request the request object
     * @param string $pathInfo path info part of the URL (URL suffix is already removed based on {@link CUrlManager::urlSuffix})
     * @param string $rawPathInfo path info that contains the potential URL suffix
     * @return mixed the route that consists of the controller ID and action ID. False if this rule does not apply.
     */
    public function parseUrl($manager, $request, $pathInfo, $rawPathInfo)
    {
        if (preg_match('%([a-z0-9_-]+)$%',$pathInfo,$matches)){

            if ($p3pageTranslation = P3PageTranslation::model()->findByAttributes(array(
                'language'=>Yii::app()->language,
                'seoUrl'=>$matches[1],
            ))){
                $_GET[P3Page::PAGE_ID_KEY]=$p3pageTranslation->p3_page_id;
                $_GET['lang']=Yii::app()->language;
                return 'p3pages/default/page';
            }
        }

        return false;
    }
}
