<?php
/**
 * @package     Joomla.Site
 * @subpackage  com_users
 *
 * @copyright   Copyright (C) 2005 - 2014 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

//Initialize Bootstrap tooltips for labels (ver.2):
//$doc = JFactory::getDocument();
//$doc->addScriptDeclaration('jQuery(function () {jQuery(\'[data-toggle="tooltip"]\').tooltip(); })');
//$doc->addScriptDeclaration('jQuery(function () {jQuery(\'.hasTooltip\').tooltip({html:true}); })');

JHtml::_('behavior.keepalive');
JHtml::_('behavior.formvalidator');
?>
<div class="reset-confirm<?php echo $this->pageclass_sfx?> col-xs-12 col-sm-6 col-sm-offset-3 col-md-6 col-md-offset-3 col-lg-4 col-lg-offset-4 well auth">
	<?php if ($this->params->get('show_page_heading')) : ?>
		<div class="page-header">
			<h1>
				<?php echo $this->escape($this->params->get('page_heading')); ?>
			</h1>
		</div>
	<?php endif; ?>

	<form action="<?php echo JRoute::_('index.php?option=com_users&task=reset.confirm'); ?>" method="post" class="form-validate">
		<?php foreach ($this->form->getFieldsets() as $fieldset) : ?>
			<fieldset>
				<p class="text-justify"><?php echo JText::_($fieldset->label); ?></p>
				<?php foreach ($this->form->getFieldset($fieldset->name) as $name => $field) : ?>
					<div class="form-group">
                                            <?php 
                                            $field->labelclass = $field->labelclass.' control-label';
                                            echo $field->label; ?>
                                            <div class="input-group">
                                                <?php
                                                $glyphicon = '';
                                                ($field->fieldname === 'username') ? $glyphicon = 'glyphicon glyphicon-user' : $glyphicon = 'glyphicon glyphicon-lock';
                                                $field->class = $field->class.' form-control'; ?>
                                                <span class="input-group-addon">
                                                    <span class="<?php echo $glyphicon ?>"></span>
                                                </span>
                                                <?php echo $field->input; ?>
                                            </div>
                                        </div>
				<?php endforeach; ?>
			</fieldset>
		<?php endforeach; ?>
                <button type="submit" class="btn btn-primary btn-lg pull-right validate"><?php echo JText::_('JSUBMIT'); ?></button>
		<?php echo JHtml::_('form.token'); ?>
	</form>
</div>
