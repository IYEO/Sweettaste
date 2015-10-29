<?php
/**
 * @package     Joomla.Site
 * @subpackage  com_users
 *
 * @copyright   Copyright (C) 2005 - 2014 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

$this->form->reset(true); // reset the form xml loaded by the view
$this->form->loadFile( dirname(__FILE__) . DS . "login.xml"); // override login.xml

JHtml::_('behavior.keepalive');
JHtml::_('jquery.framework');
JHtml::_('behavior.formvalidator');

//Initialize Bootstrap tooltips for labels (ver.2):
$doc = JFactory::getDocument();
$doc->addScriptDeclaration('jQuery(function () {jQuery(\'[data-toggle="tooltip"]\').tooltip(); })');
//$doc->addScriptDeclaration('jQuery(function () {jQuery(\'.hasTooltip\').tooltip({html:true}); })');
?>

<div class="login<?php echo $this->pageclass_sfx?> col-xs-12 col-sm-6 col-sm-offset-3 col-md-4 col-md-offset-4 col-lg-4 col-lg-offset-4 auth well">
	<?php if ($this->params->get('show_page_heading')) : ?>
	<div class="page-header">
		<h1>
			<?php echo $this->escape($this->params->get('page_heading')); ?>
		</h1>
	</div>
	<?php endif; ?>

	<?php if (($this->params->get('logindescription_show') == 1 && str_replace(' ', '', $this->params->get('login_description')) != '') || $this->params->get('login_image') != '') : ?>
	<div class="login-description">
	<?php endif; ?>

		<?php if ($this->params->get('logindescription_show') == 1) : ?>
			<?php echo $this->params->get('login_description'); ?>
		<?php endif; ?>

		<?php if (($this->params->get('login_image') != '')) :?>
			<img src="<?php echo $this->escape($this->params->get('login_image')); ?>" class="login-image" alt="<?php echo JTEXT::_('COM_USER_LOGIN_IMAGE_ALT')?>"/>
		<?php endif; ?>

	<?php if (($this->params->get('logindescription_show') == 1 && str_replace(' ', '', $this->params->get('login_description')) != '') || $this->params->get('login_image') != '') : ?>
	</div>
	<?php endif; ?>

	<form action="<?php echo JRoute::_('index.php?option=com_users&task=user.login'); ?>" method="post" class="form-validate">
		<fieldset>
			<?php foreach ($this->form->getFieldset('credentials') as $field) : ?>
				<?php if (!$field->hidden) : ?>				    
					<div class="form-group">				
						<?php
						  $field->labelclass = 'control-label';
						  echo $field->label; ?>
						<div class="input-group">
							<?php if ($field->name === 'username') {
							    $field->class = $field->class.' form-control';
							    $field->autofocus = true; ?>
							    <span class="input-group-addon"><span class="glyphicon glyphicon-user"></span></span>
							<?php } elseif ($field->name === 'password') { 
							    $field->class = $field->class.' form-control'; ?>
                                                            <span class="input-group-addon"><span class="glyphicon glyphicon-lock"></span></span>
                                                        <?php }
                            
							echo $field->input; ?>
							
							<?php if ($field->name === 'username') { ?>
							    <span class="input-group-addon">
                                                                <a title="<?php echo JText::_('COM_USERS_LOGIN_REMIND'); ?>" href="<?php echo JRoute::_('index.php?option=com_users&view=remind'); ?>" data-toggle="tooltip"><span class="glyphicon glyphicon-question-sign"></span></a>
                                                            </span> <?php
                                                        } elseif ($field->name === 'password') { ?>
                                                            <span class="input-group-addon">
                                                                <a title="<?php echo JText::_('COM_USERS_LOGIN_RESET'); ?>" href="<?php echo JRoute::_('index.php?option=com_users&view=reset'); ?>" data-toggle="tooltip"><span class="glyphicon glyphicon-question-sign"></span></a>
                                                            </span>                                
                                                    <?php } ?>							
						</div>
					</div>
				<?php endif; ?>
			<?php endforeach; ?>                        
			<?php if ($this->tfa): ?>
				<div class="form-group">					
					<?php echo $this->form->getField('secretkey')->label; ?>					
					<div class="input-group">
						<?php echo $this->form->getField('secretkey')->input; ?>
					</div>
				</div>
			<?php endif; ?>

			<?php if (JPluginHelper::isEnabled('system', 'remember')) : ?>
			<div class="checkbox">
				<label>
				    <input id="remember" type="checkbox" name="remember" class="inputbox" value="yes" /></input>
				    <?php echo JText::_('COM_USERS_LOGIN_REMEMBER_ME') ?>
				</label>
			</div>
			<?php endif; ?>
            <div class="help-block">
                <span class="glyphicon glyphicon-asterisk"></span><?php echo JText::_('REQUIRED_FIELDS_HELP_TEXT')?>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <?php
                    $usersConfig = JComponentHelper::getParams('com_users');
                    if ($usersConfig->get('allowUserRegistration')) : ?>
                
                        <a href="<?php echo JRoute::_('index.php?option=com_users&view=registration'); ?>" class="btn btn-default btn-block btn-register">
                            <?php echo JText::_('COM_USERS_LOGIN_REGISTER'); ?></a>
                
                    <?php endif; ?>
                </div>
            </div>     

            <div class="row">
    			<div class="form-group">
    			    <div class="col-md-6">
                        <?php
                            $pos = "slogin";
                            $modules =& JModuleHelper::getModules($pos);
                            foreach ($modules as $module){
                               echo JModuleHelper::renderModule($module);
                            }
                        ?>
                    </div>
    				<div class="col-md-6">
    					<button type="submit" class="btn btn-primary btn-lg pull-right validate">
    						<?php echo JText::_('JLOGIN'); ?>
    					</button>
    				</div>
    			</div>
            </div>
			<input type="hidden" name="return" value="<?php echo base64_encode($this->params->get('login_redirect_url', $this->form->getValue('return'))); ?>" />
			<?php echo JHtml::_('form.token'); ?>
		</fieldset>
	</form>
</div>