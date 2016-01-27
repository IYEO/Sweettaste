<?php
/**
 * @package	HikaShop for Joomla!
 * @version	2.6.1
 * @author	hikashop.com
 * @copyright	(C) 2010-2016 HIKARI SOFTWARE. All rights reserved.
 * @license	GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 */
defined('_JEXEC') or die('Restricted access');
?><?php
class hikashopVoteType{

	var $values = array();

	function load() {
		$this->values['product'] =  JHTML::_('select.option', 'product', JText::_('PRODUCT'));
	}

	function display($map, $value, $extra = '') {
		if(empty($this->values))
			$this->load();
		$values = $this->values;
		return JHTML::_('select.genericlist', $values, $map, $extra, 'value', 'text', $value);
	}
}
