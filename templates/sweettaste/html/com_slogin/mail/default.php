<?php
/**
 * Social Login
 *
 * @version 	1.0
 * @author		SmokerMan, Arkadiy, Joomline
 * @copyright	© 2012. All rights reserved.
 * @license 	GNU/GPL v.3 or later.
 */

// No direct access to this file
defined('_JEXEC') or die;
JHtml::_('jquery.framework');
JHtml::_('behavior.keepalive');
JHTML::_('behavior.formvalidator');
?>
<div class="col-xs-12 col-sm-6 col-sm-offset-3 col-md-4 col-md-offset-4 col-lg-4 col-lg-offset-4 well auth">
    <p>
        <?php echo JText::_('COM_SLOGIN_PROVIDER_DATA'); ?>
    </p>

    <div class="login-description" id="slogin_error_mesages"></div>    
    <form action="<?php echo $this->action; ?>" method="post" class="form-validate" id="sloginUserForm">
        <fieldset>
            <div class="form-group">
                <label id="name-lbl" for="name" class="control-label required">
                    <?php echo JText::_('COM_SLOGIN_NAME')?>
                    <span class="star">*</span>
                </label>                
                <div>                    
                    <input type="text" name="name" id="name" class="form-control validate-name required"
                       value="<?php echo $this->name; ?>" />
                </div>                
            </div>
            <div class="form-group">
                <label id="username-lbl" for="username" class="control-label required">
                    <?php echo JText::_('COM_SLOGIN_USERNAME_LABEL')?>
                    <span class="star">*</span>
                </label>                
                <div class="input-group">
                    <span class="input-group-addon">
                        <span class="glyphicon glyphicon-user"></span>
                    </span>
                    <input type="text" name="username" id="username" class="form-control validate-username required"
                       value="<?php echo $this->username; ?>" />
                </div>
            </div>
            <div class="form-group">
                <label id="email-lbl" for="email" class="control-label required">
                    <?php echo JText::_('COM_SLOGIN_MAIL')?>
                    <span class="star">*</span>
                </label>                
                <div class="input-group">
                    <span class="input-group-addon">
                        <span class="glyphicon glyphicon-envelope"></span>
                    </span>
                    <input type="email" name="email" id="email"
                       value="<?php echo $this->email; ?>" class="form-control validate-email required" />
                </div>                
            </div>
            
            <?php
                //Import Recaptcha
                JPluginHelper::importPlugin('captcha');
                $dispatcher = JDispatcher::getInstance();
                $dispatcher->trigger('onInit','dynamic_recaptcha');
            ?>

            <div class="form-group">
                <div id="dynamic_recaptcha"></div>
            </div>
            <div class="help-block">
                <span class="glyphicon glyphicon-asterisk"></span><?php echo JText::_('REQUIRED_FIELDS_HELP_TEXT')?>
            </div>
            <input type="submit" class="btn btn-primary btn-lg pull-right validate" value="<?php echo JText::_('COM_SLOGIN_SUBMIT'); ?>"/>

            <?php echo JHtml::_('form.token'); ?>
        </fieldset>
    </form>    
</div>