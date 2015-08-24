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
JHtml::_('behavior.keepalive');
//JHTML::_('behavior.formvalidator');
JHtml::script('system/validate.js', false, true);
/*$doc = JFactory::getDocument();
$doc->addStyleSheet(JURI::root().'media/com_slogin/comslogin.css')*/
?>
<div class="col-xs-12 col-sm-8 col-sm-offset-2 col-md-6 col-md-offset-3 col-lg-6 col-lg-offset-3 well auth">
    <p>
        <?php echo JText::_('COM_SLOGIN_PROVIDER_DATA'); ?>
    </p>

    <div class="login-description" id="slogin_error_mesages"></div>    
    <form action="<?php echo $this->action; ?>" method="post" class="form-validate form-horizontal" id="sloginUserForm">
        <fieldset>
            <div class="form-group">
                <label id="name-lbl" for="name" class="control-label required col-xs-12 col-sm-6 col-md-6 col-lg-5">
                    <?php echo JText::_('COM_SLOGIN_NAME')?>
                </label>
                <div class="col-xs-12 col-sm-6 col-md-6 col-lg-7">
                    <input type="text" name="name" id="name" class="form-control validate-name required"
                       value="<?php echo $this->name; ?>" />
                </div>                
            </div>
            <div class="form-group">
                <label id="username-lbl" for="username" class="control-label required col-xs-12 col-sm-6 col-md-6 col-lg-5">
                    <?php echo JText::_('COM_SLOGIN_USERNAME_LABEL')?>
                </label>
                <div class="col-xs-12 col-sm-6 col-md-6 col-lg-7">
                    <input type="text" name="username" id="username" class="form-control validate-username required"
                       value="<?php echo $this->username; ?>" />
                </div>                
            </div>
            <div class="form-group">
                <label id="email-lbl" for="email" class="control-label required col-xs-12 col-sm-6 col-md-6 col-lg-5">
                    <?php echo JText::_('COM_SLOGIN_MAIL')?>
                </label>
                <div class="col-xs-12 col-sm-6 col-md-6 col-lg-7">
                    <input type="text" name="email" id="email"
                       value="<?php echo $this->email; ?>" class="form-control validate-email required" />
                </div>                
            </div>

            <input type="submit" class="btn btn-primary btn-lg pull-right validate" value="<?php echo JText::_('COM_SLOGIN_SUBMIT'); ?>"/>

            <?php echo JHtml::_('form.token'); ?>
        </fieldset>
    </form>    
</div>