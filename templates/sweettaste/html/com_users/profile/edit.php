<?php
/**
 * @package     Joomla.Site
 * @subpackage  com_users
 *
 * @copyright   Copyright (C) 2005 - 2015 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

//$this->form->reset( true ); // reset the form xml loaded by the view
$this->form->loadFile( dirname(__FILE__) . DS . "profile.xml"); // override profile.xml

JHtml::_('behavior.keepalive');
JHtml::_('jquery.framework');
JHtml::_('behavior.formvalidator');
JHtml::_('formbehavior.chosen', 'select');

//Initialize Bootstrap tooltips for labels (ver.2):
//$doc = JFactory::getDocument();
////$doc->addScriptDeclaration('jQuery(function () {jQuery(\'.hasTooltip\').tooltip({html:true}); })');
//$doc->addScriptDeclaration('jQuery(function () {jQuery(\'[data-toggle="tooltip"]\').tooltip(); })');

// Load user_profile plugin language
$lang = JFactory::getLanguage();
$lang->load('plg_user_profile', JPATH_ADMINISTRATOR);

?>
<div class="profile-edit<?php echo $this->pageclass_sfx?> col-xs-12 col-sm-6 col-sm-offset-3 col-md-4 col-md-offset-4 col-lg-4 col-lg-offset-4">
	<?php if ($this->params->get('show_page_heading')) : ?>
		<div class="page-header">
			<h1><?php echo $this->escape($this->params->get('page_heading')); ?></h1>
		</div>
	<?php endif; ?>

	<script type="text/javascript">
		Joomla.twoFactorMethodChange = function(e)
		{
			var selectedPane = 'com_users_twofactor_' + jQuery('#jform_twofactor_method').val();

			jQuery.each(jQuery('#com_users_twofactor_forms_container>div'), function(i, el) {
				if (el.id != selectedPane)
				{
					jQuery('#' + el.id).hide(0);
				}
				else
				{
					jQuery('#' + el.id).show(0);
				}
			});
		}
	</script>

	<form id="member-profile" action="<?php echo JRoute::_('index.php?option=com_users&task=profile.save'); ?>" method="post" class="form-validate well" enctype="multipart/form-data">
	<?php // Iterate through the form fieldsets and display each one. ?>
	<?php foreach ($this->form->getFieldsets() as $group => $fieldset) : ?>
		<?php $fields = $this->form->getFieldset($group); ?>
		<?php if (count($fields)) : ?>
		<fieldset>
			<?php // If the fieldset has a label set, display it as the legend. ?>
			<?php if (isset($fieldset->label)) : ?>
			<legend>
                            <?php echo JText::_($fieldset->label); ?>
			</legend>
			<?php endif;?>                    
			<?php // Iterate through the fields in the set and display them. ?>
			<?php foreach ($fields as $field) : ?>
			<?php // If the field is hidden, just display the input. ?>
				<?php if ($field->hidden) : ?>
					<?php echo $field->input; ?>
				<?php else : ?>                                        
					<div class="form-group">
                                            <?php 
                                            $field->labelclass = $field->labelclass . ' control-label';
                                            echo $field->label;
                                            $field->class = $field->class.' form-control';
                                            ?>                                            
                                            <?php if ($field->fieldname == 'password1') : ?>
                                                    <?php // Disables autocomplete ?> <input type="text" style="display:none">
                                            <?php endif; ?>
                                            <?php echo $field->input; ?>
					</div>
				<?php endif;?>
			<?php endforeach;?>
		</fieldset>
		<?php endif;?>
	<?php endforeach;?>
        <div class="help-block">
            <span class="glyphicon glyphicon-asterisk"></span><?php echo JText::_('REQUIRED_FIELDS_HELP_TEXT')?>
        </div>
	<?php if (count($this->twofactormethods) > 1) : ?>
		<fieldset>
			<legend><?php echo JText::_('COM_USERS_PROFILE_TWO_FACTOR_AUTH'); ?></legend>

			<div class="form-group">
				<div class="control-label">
					<label id="jform_twofactor_method-lbl" for="jform_twofactor_method" class="hasTooltip"
						   title="<strong><?php echo JText::_('COM_USERS_PROFILE_TWOFACTOR_LABEL'); ?></strong><br /><?php echo JText::_('COM_USERS_PROFILE_TWOFACTOR_DESC'); ?>">
						<?php echo JText::_('COM_USERS_PROFILE_TWOFACTOR_LABEL'); ?>
					</label>
				</div>
				<div class="controls">
					<?php echo JHtml::_('select.genericlist', $this->twofactormethods, 'jform[twofactor][method]', array('onchange' => 'Joomla.twoFactorMethodChange()'), 'value', 'text', $this->otpConfig->method, 'jform_twofactor_method', false); ?>
				</div>
			</div>
			<div id="com_users_twofactor_forms_container">
				<?php foreach($this->twofactorform as $form) : ?>
				<?php $style = $form['method'] == $this->otpConfig->method ? 'display: block' : 'display: none'; ?>
				<div id="com_users_twofactor_<?php echo $form['method']; ?>" style="<?php echo $style; ?>">
					<?php echo $form['form']; ?>
				</div>
				<?php endforeach; ?>
			</div>
		</fieldset>

		<fieldset>
			<legend>
				<?php echo JText::_('COM_USERS_PROFILE_OTEPS'); ?>
			</legend>
			<div class="alert alert-info">
				<?php echo JText::_('COM_USERS_PROFILE_OTEPS_DESC'); ?>
			</div>
			<?php if (empty($this->otpConfig->otep)) : ?>
			<div class="alert alert-warning">
				<?php echo JText::_('COM_USERS_PROFILE_OTEPS_WAIT_DESC'); ?>
			</div>
			<?php else : ?>
			<?php foreach ($this->otpConfig->otep as $otep) : ?>
			<span class="span3">
				<?php echo substr($otep, 0, 4); ?>-<?php echo substr($otep, 4, 4); ?>-<?php echo substr($otep, 8, 4); ?>-<?php echo substr($otep, 12, 4); ?>
			</span>
			<?php endforeach; ?>
			<div class="clearfix"></div>
			<?php endif; ?>
		</fieldset>
	<?php endif; ?>

		<div class="form-group text-right">
                    <button type="submit" class="btn btn-lg btn-primary validate"><span><?php echo JText::_('JSUBMIT'); ?></span></button>
                    <a class="btn btn-lg btn-primary" href="<?php echo JRoute::_(Juri::base() . 'index.php/menu/profile/'); ?>" title="<?php echo JText::_('JCANCEL'); ?>"><?php echo JText::_('JCANCEL'); ?></a>
                    <input type="hidden" name="option" value="com_users" />
                    <input type="hidden" name="task" value="profile.save" />			
		</div>
		<?php echo JHtml::_('form.token'); ?>
	</form>
</div>
