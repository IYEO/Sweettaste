<?php
/**
 * @package     Joomla.Site
 * @subpackage  com_users
 *
 * @copyright   Copyright (C) 2005 - 2014 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

JHtml::_('behavior.keepalive');
JHtml::_('behavior.formvalidator');
?>
<div class="reset-complete<?php echo $this->pageclass_sfx?> col-xs-12 col-sm-8 col-sm-offset-2 col-md-6 col-md-offset-3 col-lg-6 col-lg-offset-3 well auth">
	<?php if ($this->params->get('show_page_heading')) : ?>
		<div class="page-header">
			<h1>
				<?php echo $this->escape($this->params->get('page_heading')); ?>
			</h1>
		</div>
	<?php endif; ?>

	<form action="<?php echo JRoute::_('index.php?option=com_users&task=reset.complete'); ?>" method="post" class="form-validate form-horizontal">
		<?php foreach ($this->form->getFieldsets() as $fieldset) : ?>
			<fieldset>
				<?php foreach ($this->form->getFieldset($fieldset->name) as $name => $field) : ?>
					<p class="text-justify"><?php echo JText::_($fieldset->label); ?></p>
					<div class="form-group">						
                                            <?php 
                                            $field->labelclass = $field->labelclass.' col-xs-12 col-sm-6 col-md-6 col-lg-5 control-label';
                                            echo $field->label; ?>						
                                            <div class="col-xs-12 col-sm-6 col-md-6 col-lg-7">
                                                <?php 
                                                $field->class = $field->class.' form-control';
                                                echo $field->input; ?>
                                            </div>
					</div>
				<?php endforeach; ?>
			</fieldset>
		<?php endforeach; ?>
                <button type="submit" class="btn btn-primary btn-lg pull-right validate"><?php echo JText::_('JSUBMIT'); ?></button>
		<?php echo JHtml::_('form.token'); ?>
	</form>
</div>
