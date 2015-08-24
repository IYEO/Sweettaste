<?php
/**
 * @package	HikaShop for Joomla!
 * @version	2.3.5
 * @author	hikashop.com
 * @copyright	(C) 2010-2015 HIKARI SOFTWARE. All rights reserved.
 * @license	GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 */
defined('_JEXEC') or die('Restricted access');
?><?php
function hikashop_button_render($map, $name, $ajax, $options, $url, $classname, $iconspan=''){
	$config =& hikashop_config();
	$button = $config->get('button_style','normal');    	
	switch($button){
		case 'rounded': //deprecated
			$params->set('main_div_name', 'hikashop_button_'.$i);
			$moduleHelper = hikashop_get('helper.module');
			$moduleHelper->setCSS($params);
			$url = 'href="'.$url.'" ';
			$html='
			<div id="'.$params->get('main_div_name').'">
			<div class="hikashop_container">
			<div class="hikashop_subcontainer">
			<a rel="nofollow" class="hikashop_cart_rounded_button'.$classname.'" '.$url.$ajax.$options.'>'.$name.'</a>
			</div>
			</div>
			</div>
			';
			break;
		case 'css':
			if(empty($url))
				$url = '#';
			$url = 'href="'.$url.'" ';
			$html= '<button type="button" class="hikashop_cart_button'.$classname.'" '.$options.' '.$url.$ajax.'>'.$iconspan.$name.'</button>';
			break;
		case 'normal':
		default:
			$type = 'submit';
			if(in_array($map,array('new','refresh','wishlist'))){
				$type = 'button';
			}
			$app = JFactory::getApplication();
			if($app->isAdmin()){
				$class = 'btn';
			}else{
				$class = HK_GRID_BTN;
			}
			$html= '<input type="'.$type.'" class="'.$class.' button hikashop_cart_input_button'.$classname.'" name="'.$map.'" value="'.$name.'" '.$ajax.$options.'/>';
			break;
	}

    return $html;
}