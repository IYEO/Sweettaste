<?php
/**
 * @package	HikaShop for Joomla!
 * @version	2.3.4
 * @author	hikashop.com
 * @copyright	(C) 2010-2014 HIKARI SOFTWARE. All rights reserved.
 * @license	GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 */
defined('_JEXEC') or die('Restricted access');

/*$document = JFactory::getDocument();
$document->addStyleSheet(JUri::base() . 'templates/' . $template . '/css/template.css');*/
JHtml::stylesheet(JUri::base() . 'templates/' . $template . '/css/template.css');
JHtml::_('jquery.framework');
JHtml::script('bootstrap.min.js', false, true);?>

<div id="hikashop_address_form_span_iframe" class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
    <form action="<?php echo hikashop_completeLink('address&task=save'); ?>" method="post" name="hikashop_address_form" enctype="multipart/form-data" class="form-validate">
		
<?php
    foreach($this->extraFields['address'] as $fieldName => $oneExtraField) { ?>
        <div class="form-group hikashop_address_<?php echo $fieldName;?>_line" id="hikashop_address_<?php echo $oneExtraField->field_namekey; ?>">
            <?php echo $this->fieldsClass->getFieldName($oneExtraField);
                $onWhat='onchange'; if($oneExtraField->field_type=='radio') $onWhat='onclick';
                echo $this->fieldsClass->display(
                        $oneExtraField,
                        @$this->address->$fieldName,
                        'data[address]['.$fieldName.']',
                        false,
                        ' '.$onWhat.'="hikashopToggleFields(this.value,\''.$fieldName.'\',\'address\',0);"',
                        false,
                        $this->extraFields['address'],
                        @$this->address
                );
            ?>
        </div> <?php
    } ?>
		
		<input type="hidden" name="Itemid" value="<?php global $Itemid; echo $Itemid; ?>"/>
		<input type="hidden" name="ctrl" value="address"/>
		<input type="hidden" name="tmpl" value="component"/>
		<input type="hidden" name="task" value="save"/>
		<input type="hidden" name="type" value="<?php echo JRequest::getVar('type',''); ?>"/>
		<input type="hidden" name="action" value="<?php echo JRequest::getVar('task',''); ?>"/>
		<input type="hidden" name="makenew" value="<?php echo JRequest::getInt('makenew'); ?>"/>
		<input type="hidden" name="redirect" value="<?php echo JRequest::getWord('redirect','');?>"/>
		<input type="hidden" name="step" value="<?php echo JRequest::getInt('step',-1);?>"/>
<?php
	if(!empty($address->address_user_id)){
		$id = $address->address_user_id;
	}else{
		$id = $this->user_id;
	}
?>
		<input type="hidden" name="data[address][address_user_id]" value="<?php echo $id;?>"/>
<?php
	if(!JRequest::getInt('makenew')){
?>
		<input type="hidden" name="data[address][address_id]" value="<?php echo (int)@$this->address->address_id;?>"/>
		<input type="hidden" name="address_id" value="<?php echo (int)@$this->address->address_id;?>"/>
<?php
	}
	echo JHTML::_( 'form.token' );
	echo $this->cart->displayButton(JText::_('OK'),'ok',$this->params,hikashop_completeLink('address&task=save'),'if(hikashopCheckChangeForm(\'address\',\'hikashop_address_form\')) document.forms[\'hikashop_address_form\'].submit(); return false;');
?>
	</form>
</div>
<div class="clear_both"></div>
