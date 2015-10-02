<?php
/**
 * @package	HikaShop for Joomla!
 * @version	2.3.4
 * @author	hikashop.com
 * @copyright	(C) 2010-2014 HIKARI SOFTWARE. All rights reserved.
 * @license	GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 */
defined('_JEXEC') or die('Restricted access');
?><div id="hikashop_address_listing" class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
<?php if($this->user_id){ ?>
<fieldset>
	<div class="header hikashop_header_title"><h1><?php echo JText::_('ADDRESSES');?></h1></div>
	<div class="toolbar hikashop_header_buttons" id="toolbar" >
		<table>
			<tr>
				<td>
                                    <a href="<?php echo hikashop_completeLink('address&task=add'); ?>">
                                        <span class="icon-32-new" title="<?php echo JText::_('HIKA_NEW'); ?>"></span>
                                        <?php echo JText::_('HIKA_NEW'); ?>
                                    </a>
                                </td>
				<td>
                                    <a href="<?php echo hikashop_completeLink('');?>" >
                                        <span class="icon-32-back" title="<?php echo JText::_('HIKA_BACK'); ?>"></span> <?php echo JText::_('HIKA_BACK'); ?>
                                    </a>
				</td>
			</tr>
		</table>
	</div>
</fieldset>
<?php
	if(!empty($this->addresses)) {
		$ctrl = JRequest::getCmd('ctrl');
?>
<div class="row">
    <div class="col-xs-12 col-sm-8 col-sm-offset-2 col-md-8 col-md-offset-2 col-lg-6 col-lg-offset-3">
        <form action="<?php echo hikashop_completeLink($ctrl); ?>" name="hikashop_user_address" method="post">
        <?php
        if(false) {
                $this->setLayout('select');
                echo $this->loadTemplate();
        } else {
        ?>

        <?php
            global $Itemid;
            $addressClass = hikashop_get('class.address');
            $token = hikashop_getFormToken();
            foreach($this->addresses as $address){
                $this->address =& $address; ?>
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <input type="radio" name="address_default" value="<?php echo $this->address->address_id;?>"<?php
                        if($this->address->address_default == 1) {
                            echo ' checked="checked"';
                        } ?> onclick="this.form.submit();"/>

                        <div class="pull-right" style="font-size: 18px">
                            <a href="<?php echo hikashop_completeLink('address&task=delete&address_id='.$address->address_id.'&'.$token.'=1&Itemid='.$Itemid);?>" title="<?php echo JText::_('HIKA_DELETE'); ?>">
                                <span class="glyphicon glyphicon-trash" title="<?php echo JText::_('HIKA_DELETE'); ?>"></span>
                            </a>
                            <a href="<?php echo hikashop_completeLink('address&task=edit&address_id='.$address->address_id.'&Itemid='.$Itemid); ?>">
                                <span class="glyphicon glyphicon-pencil" title="<?php echo JText::_('HIKA_EDIT'); ?>"></span>
                            </a>                            
                        </div>
                    </div>
                    <div class="panel-body">
                        <address><?php
                            echo $addressClass->displayAddress($this->fields, $address, 'address'); ?>
                        </address>
                    </div>
                </div> <?php
            }
        } ?>
                <input type="hidden" name="option" value="<?php echo HIKASHOP_COMPONENT; ?>" />
                <input type="hidden" name="ctrl" value="<?php echo $ctrl ?>" />
                <input type="hidden" name="task" value="setdefault" />
                <?php echo JHTML::_('form.token'); ?>
        </form>
    </div>
</div>
<?php
	}
}
?>
</div>