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
$this->form->loadFile( dirname(__FILE__) . DS . "reset_request.xml"); // override reset_request.xml

JHtml::_('behavior.keepalive');
JHtml::_('behavior.formvalidator');

//Initialize Bootstrap tooltips for labels (ver.2):
//$doc = JFactory::getDocument();
//$doc->addScriptDeclaration('jQuery(function () {jQuery(\'[data-toggle="tooltip"]\').tooltip(); })');
//$doc->addScriptDeclaration('jQuery(function () {jQuery(\'.hasTooltip\').tooltip({html:true}); })');
?>
<div class="reset<?php echo $this->pageclass_sfx?> col-xs-12 col-sm-6 col-sm-offset-3 col-md-4 col-md-offset-4 col-lg-4 col-lg-offset-4 well auth">
    <?php if ($this->params->get('show_page_heading')) : ?>
    <div class="page-header">
        <h1>
            <?php echo $this->escape($this->params->get('page_heading')); ?>
        </h1>
    </div>
    <?php endif; ?>

    <form id="user-registration" action="<?php echo JRoute::_('index.php?option=com_users&task=reset.request'); ?>" method="post" class="form-validate">
        <?php foreach ($this->form->getFieldsets() as $fieldset) : ?>
            <fieldset>
                <p class="text-justify"><?php echo JText::_($fieldset->label); ?></p>
                <?php foreach ($this->form->getFieldset($fieldset->name) as $name => $field) : ?>
                    <div class="form-group">
                        <?php
                        if ($field->fieldname == "email") : 
                            $addon = '<span class="input-group-addon"><span class="glyphicon glyphicon-envelope"></span></span>';
                            $recaptcha_area = '';
                            $field->labelclass = $field->labelclass . ' control-label';
                            $field->class = $field->class.' form-control';
                        else :
                            $addon = '';
                            $recaptcha_area = 'class="recaptcha-area"';
                            $field->labelclass = $field->labelclass . ' hidden';
                        endif;
                        echo $field->label; ?>

                        <div class="input-group">
                            <?php
                            echo $addon; ?>
                            <div <?php echo $recaptcha_area?>>
                            <?php echo $field->input; ?>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </fieldset>
        <?php endforeach; ?>
        <div class="help-block">
            <span class="glyphicon glyphicon-asterisk"></span><?php echo JText::_('REQUIRED_FIELDS_HELP_TEXT')?>
        </div>
        <button type="submit" class="btn btn-primary btn-lg pull-right validate"><?php echo JText::_('JSUBMIT'); ?></button>
        <?php echo JHtml::_('form.token'); ?>
    </form>
</div>
