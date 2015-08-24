<?php
/**
 * @package     Joomla.Site
 * @subpackage  mod_menu
 *
 * @copyright   Copyright (C) 2005 - 2014 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

// Note. It is important to remove spaces between elements.
$IconClass = "glyphicon";
$IconSpan = '';
$class = $item->anchor_css ? 'class="' . $item->anchor_css . '" ' : '';
//Проверка на наличие иконок (класс иконок должен быть введён последним в поле "CSS-класс ссылки"):
if (substr_count(trim($item->anchor_css), $IconClass) > 0) {
    $class = 'class="' . substr(trim($item->anchor_css), 0, stripos(trim($item->anchor_css), $IconClass)) . '"';
    $IconSpan = '<span class="'. substr(trim($item->anchor_css), stripos(trim($item->anchor_css), $IconClass), strlen(trim($item->anchor_css)) - stripos(trim($item->anchor_css), $IconClass)) . '"></span> ';
}
//$class = $item->anchor_css ? 'class="' . $item->anchor_css . '" ' : '';
$title = $item->anchor_title ? 'title="' . $item->anchor_title . '" ' : '';

if ($item->menu_image)
	{
		$item->params->get('menu_text', 1) ?
		$linktype = '<img src="' . $item->menu_image . '" alt="' . $item->title . '" /><span class="image-title">' . $item->title . '</span> ' :
		$linktype = '<img src="' . $item->menu_image . '" alt="' . $item->title . '" />';
}
else
{
    if (substr_count(trim($item->anchor_css), $IconClass) > 0) {
        $linktype = "";
    } else {
        $linktype = $item->title;
    }
}

$flink = $item->flink;
$flink = JFilterOutput::ampReplace(htmlspecialchars($flink));

switch ($item->browserNav) :
	default:
	case 0:
        if (substr_count(trim($item->anchor_css), 'dropdown-toggle') > 0) { 	//если родительский пункт вложенного меню	?>
<a <?php echo $class; ?>href="<?php echo $flink; ?>" <?php echo $title; ?> data-toggle="dropdown" role="button" aria-expanded="false"><?php echo $IconSpan; ?><?php echo $linktype; ?><span class="caret"></span></a><?php } 
		elseif ((substr_count(trim($item->anchor_css), 'logout') > 0)) { 	//если ссылка на выход из системы	
			$user = JFactory::getUser();	//получаем пользователя
			if (!$user->guest) {	//если пользователь зарегистрирован в системе	?>
				<a title="Выйти" <?php echo $class; ?> href="/index.php?option=com_users&task=user.logout&<?php echo JSession::getFormToken(); ?>=1">
					<?php echo $IconSpan; 
					echo $user->username.' &times;'; ?>						
				</a> <?php 
            }			
		}   
		else { ?>
			<a <?php echo $class; ?>href="<?php echo $flink; ?>" <?php echo $title; ?>><?php echo $IconSpan; ?><?php echo $linktype; ?></a><?php }		
		break;
	case 1:
		// _blank
?><a <?php echo $class; ?>href="<?php echo $flink; ?>" target="_blank" <?php echo $title; ?>><?php echo $IconSpan; ?><?php echo $linktype; ?></a><?php
		break;
	case 2:
		// Use JavaScript "window.open"
		$options = 'toolbar=no,location=no,status=no,menubar=no,scrollbars=yes,resizable=yes,' . $params->get('window_open');
			?><a <?php echo $class; ?>href="<?php echo $flink; ?>" onclick="window.open(this.href,'targetWindow','<?php echo $options;?>');return false;" <?php echo $title; ?>><?php echo $linktype; ?></a><?php
		break;
endswitch;
