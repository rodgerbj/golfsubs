<?php
/**
 * @version    1.0.0
 * @package    contentphoneformat (plugin)
 * @author     Kevin Olson - kevinsguides.com
 * @copyright  Copyright (c) 2022 Kevin Olson
 * @license    GNU/GPL license: https://www.gnu.org/copyleft/gpl.html
 */
//kill direct access
defined('_JEXEC') || die;

use Joomla\CMS\Plugin\CMSPlugin;
use Joomla\CMS\Factory;


class PlgContentContentphoneformat extends CMSPlugin
{

    public function onContentBeforeSave($context, $data)
    {
        
        if ($context !== "com_golfsubs.member") {
            return true;
        }

        //getters

            return true;
        
        
        
    }

}