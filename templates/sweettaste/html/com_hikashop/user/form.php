<?php
/**
 * @package	HikaShop for Joomla!
 * @version	2.3.4
 * @author	hikashop.com
 * @copyright	(C) 2010-2014 HIKARI SOFTWARE. All rights reserved.
 * @license	GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 */
defined('_JEXEC') or die('Restricted access');
?><?php
global $Itemid;
$url_itemid='';
if(!empty($Itemid)){
	$url_itemid='&Itemid='.$Itemid;
}
?>
<form action="<?php echo hikashop_completeLink('user&task=register'.$url_itemid); ?>" method="post" name="hikashop_registration_form" enctype="multipart/form-data" onsubmit="hikashopSubmitForm('hikashop_registration_form'); return false;">
	<div class="auth hikashop_user_registration_page col-xs-12 col-sm-6 col-sm-offset-3 col-md-4 col-md-offset-4 col-lg-4 col-lg-offset-4 well">	    
		<fieldset class="input">    			
			<?php
			$this->setLayout('registration');
			$this->registration_page=true;
			$this->form_name = 'hikashop_registration_form';
			$usersConfig = JComponentHelper::getParams('com_users');
			$allowRegistration = $usersConfig->get('allowUserRegistration');
			if ($allowRegistration || $this->simplified_registration == 2){
				echo $this->loadTemplate();
			}else{
				echo JText::_('REGISTRATION_NOT_ALLOWED');
			}
			 ?>
		</fieldset>		
	</div>
</form>
