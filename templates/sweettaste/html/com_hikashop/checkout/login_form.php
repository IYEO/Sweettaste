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
if(JPluginHelper::isEnabled('authentication', 'openid')) {
	$lang = &JFactory::getLanguage();
	$lang->load( 'plg_authentication_openid', JPATH_ADMINISTRATOR );
	$langScript = 	'var JLanguage = {};'.
		' JLanguage.WHAT_IS_OPENID = \''.JText::_( 'WHAT_IS_OPENID' ).'\';'.
		' JLanguage.LOGIN_WITH_OPENID = \''.JText::_( 'LOGIN_WITH_OPENID' ).'\';'.
		' JLanguage.NORMAL_LOGIN = \''.JText::_( 'NORMAL_LOGIN' ).'\';'.
		' var comlogin = 1;';
	$document = &JFactory::getDocument();
	$document->addScriptDeclaration( $langScript );
	JHTML::_('script', 'openid.js');
}
if(!HIKASHOP_RESPONSIVE) {
    if(!HIKASHOP_J16){
        $reset = 'index.php?option=com_user&view=reset';
        $remind = 'index.php?option=com_user&view=remind';
    }else{
        $reset = 'index.php?option=com_users&view=reset';
        $remind = 'index.php?option=com_users&view=remind';
    }
?>
    <div class="form-group">
        <label class="control-label" for="username"><?php echo JText::_('HIKA_USERNAME') ?></label>
        <span class="star">*</span>
        <div id="com-form-login-username" class="input-group">
            <span class="input-group-addon"><span class="glyphicon glyphicon-user"></span></span>
            <input name="username" id="username" type="text" class="form-control required" size="18" placeholder="<?php echo JText::_('HIKA_USERNAME')?>"/>
            <span class="input-group-addon">
                <a title="<?php echo JText::_('HIKA_FORGOT_YOUR_USERNAME'); ?>" href="<?php echo JRoute::_( $remind ); ?>"><span class="glyphicon glyphicon-question-sign"></span></a>
            </span>        
        </div>
    </div>
    <div class="form-group">
        <label class="control-label" for="passwd"><?php echo JText::_('HIKA_PASSWORD') ?></label>
        <span class="star">*</span>
        <div id="com-form-login-password" class="input-group">        
            <span class="input-group-addon"><span class="glyphicon glyphicon-lock"></span></span>        
            <input type="password" id="passwd" name="passwd" class="form-control required" size="18" placeholder="<?php echo JText::_('HIKA_PASSWORD') ?>"/>
            <span class="input-group-addon">
                <a title="<?php echo JText::_('HIKA_FORGOT_YOUR_PASSWORD'); ?>" href="<?php echo JRoute::_( $reset ); ?>"><span class="glyphicon glyphicon-question-sign"></span></a>                
            </span>        
        </div>
    </div>
	<?php if(JPluginHelper::isEnabled('system', 'remember')) : ?>
	    <div class="checkbox">
                <label>
                    <input type="checkbox" id="remember" name="remember" value="yes"> <?php echo JText::_('HIKA_REMEMBER_ME') ?>
                </label>
            </div>	
	<?php endif; ?>	
    <div class="help-block">
        <span class="glyphicon glyphicon-asterisk"></span><?php echo JText::_('REQUIRED_FIELDS_HELP_TEXT')?>
    </div>
	<div class="row">
        <div class="form-group">
            <div class="col-md-6 col-lg-9">
                <?php
                    $pos = "slogin";
                    $modules =& JModuleHelper::getModules($pos);
                    foreach ($modules as $module){
                       echo JModuleHelper::renderModule($module);
                    }
                ?>
            </div>
            <div class="col-md-6 col-lg-3">
                <?php                    
                    echo $this->cart->displayButton(JText::_('JLOGIN'),'login',@$this->params,'',' hikashopSubmitForm(\'hikashop_checkout_form\'); return false;', NULL, NULL, NULL, 'btn btn-primary btn-lg pull-right');
                    //echo $this->cart->displayButton(JText::_('JLOGIN'),'login',@$this->params, '',' hikashopSubmitForm(\'hikashop_checkout_form\'); return false;', 'type="submit"', NULL, NULL, 'btn btn-primary btn-lg pull-right validate');
                    $button = $this->config->get('button_style','normal');
                    if ($button=='css')
                        echo '<input type="submit" style="position: absolute; left: -9999px; width: 1px; height: 1px;"/></input>';
                ?>
            </div>
        </div>
    </div>
	
<?php } else { ?>
<div class="userdata form-inline">
	<div id="form-login-username" class="control-group">
		<div class="controls">
			<div class="input-prepend input-append">
				<span class="add-on">
					<i class="icon-user tip" title="<?php echo JText::_('HIKA_USERNAME'); ?>"></i>
					<label for="modlgn-username" class="element-invisible"><?php echo JText::_('HIKA_USERNAME'); ?></label>
				</span>
				<input id="modlgn-username" type="text" name="username" class="input-small" tabindex="1" size="18" placeholder="<?php echo JText::_('HIKA_USERNAME'); ?>" />
				<a href="<?php echo JRoute::_('index.php?option=com_users&view=remind');?>" class="btn hasTooltip" title="<?php echo JText::_('HIKA_FORGOT_YOUR_USERNAME'); ?>"><i class="icon-question-sign"></i></a>
			</div>
		</div>
	</div>
	<div id="form-login-password" class="control-group">
		<div class="controls">
			<div class="input-prepend input-append">
				<span class="add-on">
					<i class="icon-lock tip" title="<?php echo JText::_('HIKA_PASSWORD') ?>"></i>
					<label for="modlgn-passwd" class="element-invisible"><?php echo JText::_('HIKA_PASSWORD') ?></label>
				</span>
				<input id="modlgn-passwd" type="password" name="passwd" class="input-small" tabindex="2" size="18" placeholder="<?php echo JText::_('HIKA_PASSWORD') ?>" />
				<a href="<?php echo JRoute::_('index.php?option=com_users&view=reset');?>" class="btn hasTooltip" title="<?php echo JText::_('HIKA_FORGOT_YOUR_PASSWORD'); ?>"><i class="icon-question-sign"></i></a>
			</div>
		</div>
	</div>
<?php if(JPluginHelper::isEnabled('system', 'remember')) { ?>
	<div id="form-login-remember" class="control-group checkbox">
		<label for="modlgn-remember" class="control-label"><?php echo JText::_('HIKA_REMEMBER_ME') ?></label>
		<input id="modlgn-remember" type="checkbox" name="remember" value="yes"/>
	</div>
<?php } ?>
	<div id="form-login-submit" class="control-group">
		<div class="controls">
			<?php echo $this->cart->displayButton(JText::_('HIKA_LOGIN'), 'login', @$this->params, '',' document.hikashop_checkout_form.submit(); return false;','', 0, 1, 'btn btn-lg btn-primary'); ?>
		</div>
	</div>
</div>
<?php } ?>
