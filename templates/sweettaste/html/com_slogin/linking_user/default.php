<?php
/**
 * Social Login
 *
 * @version     1.0
 * @author        SmokerMan, Arkadiy, Joomline
 * @copyright    © 2012. All rights reserved.
 * @license     GNU/GPL v.3 or later.
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
            <legend><?php echo JText::_('COM_SLOGIN_LINKING'); ?></legend>
            <p><?php echo JText::sprintf('COM_SLOGIN_LINKING_DESC', $this->email); ?></p>
            <div class="form-group">
                <label id="username-lbl" for="username" class="control-label">
                    <?php echo JText::_('COM_SLOGIN_USERNAME_LABEL'); ?>
                    <span class="star">&nbsp;*</span>
                </label>
                <div class="input-group">
                    <span class="input-group-addon">
                        <span class="glyphicon glyphicon-user"></span>
                    </span>
                    <input type="text" name="username" id="username" value="" class="validate-username required form-control">
                </div>                
            </div>
            <div class="form-group">
                <label id="password-lbl" for="password" class="control-label">
                    <?php echo JText::_('COM_SLOGIN_PASS'); ?>
                    <span class="star">&nbsp;*</span>
                </label>
                <div class="input-group">
                    <span class="input-group-addon">
                        <span class="glyphicon glyphicon-lock"></span>
                    </span>
                    <input type="password" name="password" id="password" value="" class="validate-password required form-control">
                </div>                
            </div>
            
                <button type="submit" class="btn btn-primary btn-lg btn-block validate"><?php echo JText::_('COM_SLOGIN_JOIN'); ?></button>
                
                <button type="button" class="btn btn-primary btn-lg btn-block" onclick="document.location.href='<?php echo JRoute::_('index.php?option=com_slogin&task=recallpass'); ?>'">
                    <?php echo JText::_('COM_SLOGIN_LOST_PASS_LOGIN'); ?>
                </button>
                
                <?php
                    $usersConfig = JComponentHelper::getParams('com_users');
                    if ($usersConfig->get('allowUserRegistration')) : ?>                
                        <button type="button" class="btn btn-primary btn-lg btn-block" onclick="document.location.href='<?php echo JRoute::_('index.php?option=com_users&view=registration'); ?>'">
                            <?php echo JText::_('COM_SLOGIN_CREATE_NEW_USER'); ?>
                        </button>
                <?php endif; ?>
                
                <button type="button" class="btn btn-primary btn-lg btn-block" onclick="document.slogin_logout_form.submit();">
                    <?php echo JText::_('COM_SLOGIN_NO_LOGIN'); ?>
                </button>
            
            <input type="hidden" name="return"
                   value="<?php echo $this->after_reg_redirect; ?>"/>
            <input type="hidden" name="user_id" value="<?php echo $this->id; ?>"/>
            <input type="hidden" name="provider" value="<?php echo $this->provider; ?>"/>
            <input type="hidden" name="slogin_id" value="<?php echo $this->slogin_id; ?>"/>
            <?php echo JHtml::_('form.token'); ?>
        </fieldset>
    </form>
</div>

<form action="<?php echo JRoute::_('index.php'); ?>"
      method="post" id="slogin_logout_form" name="slogin_logout_form">
    <input type="hidden" name="option" value="com_users">
    <input type="hidden" name="task" value="user.logout">
    <input type="hidden" name="return" value="<?php echo $this->failure_redirect; ?>">
    <?php echo JHtml::_('form.token'); ?>
</form>