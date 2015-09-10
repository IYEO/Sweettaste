<?php
/**
 * @package	HikaShop for Joomla!
 * @version	2.3.4
 * @author	hikashop.com
 * @copyright	(C) 2010-2014 HIKARI SOFTWARE. All rights reserved.
 * @license	GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 */
defined('_JEXEC') or die('Restricted access');
?>
<div class="hikashop_cpanel_main col-xs-12 col-sm-12 col-md-12 col-lg-12" id="hikashop_cpanel_main">
    <div class="hikashop_cpanel_title" id="hikashop_cpanel_title">
        <fieldset>
            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 text-center">
                <h1><?php echo JText::_('CUSTOMER_ACCOUNT');?></h1>
            </div>
        </fieldset>
    </div>
</div>

<?php
foreach($this->buttons as $oneButton){
    $url = hikashop_level($oneButton['level']) ? 'onclick="document.location.href=\''.$oneButton['link'].'\';"' : ''; ?>
        <div <?php echo $url; ?> class="hikashopcpanel col-xs-12 col-sm-8 col-sm-offset-2 col-md-6 col-md-offset-3 col-lg-6 col-lg-offset-3 text-center">
                <a href="<?php echo hikashop_level($oneButton['level']) ? $oneButton['link'] : '#'; ?>">
                    <div class="hikashop_cpanel_button_description">
                        <?php echo $oneButton['description']; ?>
                    </div>					
                </a>
        </div>
<?php }	?>