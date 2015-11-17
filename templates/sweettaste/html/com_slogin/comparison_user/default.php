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
JHtml::_('jquery.framework');
JHtml::_('behavior.formvalidator');

?>
<div class="col-xs-12 col-sm-6 col-sm-offset-3 col-md-4 col-md-offset-4 col-lg-4 col-lg-offset-4 well auth">
    <form action="<?php echo JRoute::_('index.php?option=com_slogin&task=join_mail'); ?>" method="post" class="form-validate">
        <fieldset>
            <legend><?php echo JText::_('COM_SLOGIN_COMPARISON'); ?></legend>
            <p><?php echo JText::sprintf('COM_SLOGIN_COMPARISON_DESC', $this->email); ?></p>
            <div class="form-group">
                <label id="username-lbl" for="username" class=" control-label required">
                    <?php echo JText::_('COM_SLOGIN_USERNAME_LABEL'); ?>
                    <span class="star">&nbsp;*</span>
                </label>
                <div class="input-group">
                    <span class="input-group-addon">
                        <span class="glyphicon glyphicon-user"></span>
                    </span>
                    <input type="text" name="username" id="username" value="" class="form-control validate-username required">
                </div>                
            </div>
            <div class="form-group">
                <label id="password-lbl" for="password" class=" control-label required">
                    <?php echo JText::_('COM_SLOGIN_PASS'); ?>
                    <span class="star">&nbsp;*</span>
                </label>
                <div class="input-group">
                    <span class="input-group-addon">
                        <span class="glyphicon glyphicon-lock"></span>
                    </span>
                    <input type="password" name="password" id="password" value=""
                       class="form-control validate-password required">
                </div>                
            </div>
            <button type="submit" class="btn btn-primary btn-lg pull-right validate"><?php echo JText::_('COM_SLOGIN_JOIN'); ?></button>
            <input type="hidden" name="return"
                   value="<?php echo base64_encode($this->params->get('login_redirect_url', $this->form->getValue('return'))); ?>"/>
            <input type="hidden" name="user_id" value="<?php echo $this->id; ?>"/>
            <input type="hidden" name="provider" value="<?php echo $this->provider; ?>"/>
            <input type="hidden" name="slogin_id" value="<?php echo $this->slogin_id; ?>"/>
            <?php echo JHtml::_('form.token'); ?>
        </fieldset>
    </form>

    <?php echo JText::_('COM_SLOGIN_LOST_PASS'); ?>

    <form id="user-registration" action="<?php echo JRoute::_('/index.php?option=com_users&task=reset.request') ?>" method="post"
          class="form-validate">
        <p><?php echo JText::_('COM_SLOGIN_LOST_PASS_DESC'); ?></p>
        <fieldset>
            <div class="form-group">
                <div class="input-group">
                    <span class="input-group-addon">
                        <span class="glyphicon glyphicon-envelope"></span>
                    </span>
                    <input type="text" name="jform[email]" id="jform_email" value="<?php echo $this->email ?>" readonly class="form-control validate-email required" aria-required="true" required="required" />
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
        </fieldset>
        <button type="submit" class="btn btn-primary btn-lg pull-right validate"><?php echo JText::_('COM_SLOGIN_SUBMIT'); ?></button>
        <?php echo JHtml::_('form.token'); ?>
    </form>
</div>