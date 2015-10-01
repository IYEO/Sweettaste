<?php
include_once $originalFile;

class hikashopFieldClassOverride extends hikashopFieldClass {

	var $tables = array('field');
	var $pkeys = array('field_id');
	var $namekeys = array();
	var $errors = array();
	var $prefix = '';
	var $suffix = '';
	var $excludeValue = array();
	var $toggle = array('field_required'=>'field_id','field_published'=>'field_id','field_backend'=>'field_id','field_backend_listing'=>'field_id','field_frontcomp'=>'field_id','field_core'=>'field_id');
	var $where = array();
	var $skipAddressName=false;
	var $report = true;
	var $externalValues = null;
	var $regexs = array();

	function getFieldName($field,$requiredDisplay = false){
		$app = JFactory::getApplication();
		if($app->isAdmin())
			return $this->trans($field->field_realname);
		$required = '';
		if($requiredDisplay && !empty($field->field_required))
			$required = '<span class="hikashop_field_required_label">*</span>';
		return '<label for="'.$this->prefix.$field->field_namekey.$this->suffix.'" class="control-label">'.$this->trans($field->field_realname).$required.'</label>';
	}

	function display(&$field, $value, $map, $inside = false, $options = '', $test = false, $allFields = null, $allValues = null, $requiredDisplay = true) {
		$field_type = $field->field_type;
		if(substr($field->field_type,0,4) == 'plg.') {
			$field_type = substr($field->field_type,4);
			JPluginHelper::importPlugin('hikashop', $field_type);
		}
		$classType = 'hikashop'.ucfirst($field_type).'Override';
		if(!class_exists($classType))
			return 'Plugin '.$field_type.' missing or deactivated';

		$class = new $classType($this);
		if(is_string($value))
			$value = htmlspecialchars($value, ENT_COMPAT,'UTF-8');

		$html = $class->display($field,$value,$map,$inside,$options,$test,$allFields,$allValues);

//		if($requiredDisplay && !empty($field->field_required))
//			$html .=' <span class="hikashop_field_required">*</span>';
		return $html;
	}
}

class hikashopTextOverride extends hikashopText{
	var $type = 'text';
	var $class = 'form-control';
	function display($field, $value, $map, $inside, $options = '', $test = false, $allFields = null, $allValues = null){
		$size = empty($field->field_options['size']) ? '' : 'size="'.intval($field->field_options['size']).'"';
		$size .= empty($field->field_options['maxlength']) ? '' : ' maxlength="'.intval($field->field_options['maxlength']).'"';
		$size .= empty($field->field_options['readonly']) ? '' : ' readonly="readonly"';
		$size .= empty($field->field_options['placeholder']) ? '' : ' placeholder="'.JText::_($field->field_options['placeholder']).'"';
		$js = '';
		if($inside && strlen($value) < 1){
			$value = addslashes($this->trans($field->field_realname));
			$this->excludeValue[$field->field_namekey] = $value;
			$js = 'onfocus="if(this.value == \''.$value.'\') this.value = \'\';" onblur="if(this.value==\'\') this.value=\''.$value.'\';"';
		}
		$buffInput = '<input class="'.$this->class.'" id="'.$this->prefix.@$field->field_namekey.$this->suffix.'" '.$size.' '.$js.' '.$options.' type="'.$this->type.'" name="'.$map.'" value="'.$value.'"';
		if(!empty($field->field_required) && !empty($field->registration_page))
			$buffInput.=' aria-required="true" required="required" />';
		else
			$buffInput .= ' />';
		return $buffInput;
	}	
}

class hikashopTextareaOverride extends hikashopTextarea {
	function display($field, $value, $map, $inside, $options = '', $test = false, $allFields = null, $allValues = null){
		$js = '';
		$html = '';
		if($inside && strlen($value) < 1){
			$value = addslashes($this->trans($field->field_realname));
			$this->excludeValue[$field->field_namekey] = $value;
			$js = 'onfocus="if(this.value == \''.$value.'\') this.value = \'\';" onblur="if(this.value==\'\') this.value=\''.$value.'\';"';
		}
		if(!empty($field->field_options['maxlength'])){
			static $done = false;
			if(!$done){
				$jsFunc='
				function hikashopTextCounter(textarea, counterID, maxLen) {
					cnt = document.getElementById(counterID);
					if (textarea.value.length > maxLen){
						textarea.value = textarea.value.substring(0,maxLen);
					}
					cnt.innerHTML = maxLen - textarea.value.length;
				}';
				if(!HIKASHOP_PHP5) {
					$doc =& JFactory::getDocument();
				} else {
					$doc = JFactory::getDocument();
				}
				$doc->addScriptDeclaration( "<!--\n".$jsFunc."\n//-->\n" );
				$html.= '<span class="hikashop_remaining_characters">'.JText::sprintf('X_CHARACTERS_REMAINING',$this->prefix.@$field->field_namekey.$this->suffix.'_count',(int)$field->field_options['maxlength']).'</span>';
			}
			$js .= ' onKeyUp="hikashopTextCounter(this,\''.$this->prefix.@$field->field_namekey.$this->suffix.'_count'.'\','.(int)$field->field_options['maxlength'].');" onBlur="hikashopTextCounter(this,\''.$this->prefix.@$field->field_namekey.$this->suffix.'_count'.'\','.(int)$field->field_options['maxlength'].');" ';
		}

		$cols = empty($field->field_options['cols']) ? '' : 'cols="'.intval($field->field_options['cols']).'"';
		$rows = empty($field->field_options['rows']) ? '' : 'rows="'.intval($field->field_options['rows']).'"';
		$options .= empty($field->field_options['readonly']) ? '' : ' readonly="readonly"';
		$options .= empty($field->field_options['placeholder']) ? '' : ' placeholder="'.JText::_($field->field_options['placeholder']).'"';
		return '<textarea class="form-control" id="'.$this->prefix.@$field->field_namekey.$this->suffix.'" name="'.$map.'" '.$cols.' '.$rows.' '.$js.' '.$options.'>'.$value.'</textarea>'.$html;
	}
}

class hikashopDropdownOverride extends hikashopDropdown {
	var $type = '';
        
	function display($field, $value, $map, $inside, $options = '', $test = false, $allFields = null, $allValues = null){
		$string = '';
		if(!empty($field->field_value) && !is_array($field->field_value)){
			$field->field_value = $this->parent->explodeValues($field->field_value);
		}
		if(empty($field->field_value) || !count($field->field_value)){
			if(is_array($value))
				$value = reset($value);
			return '<input type="hidden" name="'.$map.'" value="'.$value.'" />';
		}
		if($this->type == "multiple"){
			$string.= '<input type="hidden" name="'.$map.'" value=" " />';
			$map.='[]';
			$arg = 'multiple="multiple"';
			if(!empty($field->field_options['size'])) $arg .= ' size="'.intval($field->field_options['size']).'"';
		}else{
			$arg = '';
			if(is_string($value)&& empty($value) && !empty($field->field_value)){
				$found = false;
				$first = false;
				foreach($field->field_value as $oneValue => $title){
					if($first===false){
						$first=$oneValue;
					}
					if($oneValue==$value){
						$found = true;
						break;
					}
				}
				if(!$found){
					$value = $first;
				}
			}
		}
		if(strpos($options, 'class="') === false) {
			$options .= ' class="form-control"';
		} else {
			$options = str_replace('class="', 'class="form-control ', $options);
		}
		$string .= '<select id="12'.$this->prefix.$field->field_namekey.$this->suffix.'" name="'.$map.'" '.$arg.$options.'>';
		if(empty($field->field_value))
			return $string.'</select>';

		$app = JFactory::getApplication();
		$admin = $app->isAdmin();

		$isValue = !empty($value) && !is_array($value) && isset($field->field_value[$value]);
		if(is_array($value)) {
			$keys = array_keys($field->field_value);
			$isValue = array_intersect($value, $keys);
			$isValue = !empty($isValue);
		}
		$selected = '';
		foreach($field->field_value as $oneValue => $title) {
			if(isset($field->field_default) && !$isValue) {
				if(array_key_exists($field->field_default, $field->field_value)){
					if($oneValue === $field->field_default){
						$selected = (is_string($field->field_default) && $oneValue === $field->field_default) || is_array($field->field_default) && in_array($oneValue,$field->field_default) ? 'selected="selected" ' : '';
					}else{
						$selected = ((int)$title->disabled && !$admin) ? 'disabled="disabled" ' : '';
					}
				}
			} else {
				$selected = ((int)$title->disabled && !$admin) ? 'disabled="disabled" ' : '';
				$selected .= ((is_numeric($value) && is_numeric($oneValue) && $oneValue == $value) || (is_string($value) && $oneValue === $value) || is_array($value) && in_array($oneValue,$value)) ? 'selected="selected" ' : '';
			}
			$id = $this->prefix.$field->field_namekey.$this->suffix.'_'.$oneValue;
			$string .= '<option value="'.$oneValue.'" id="'.$id.'" '.$selected.'>'.$this->trans($title->value).'</option>';
		}
		$string .= '</select>';

		return $string;
	}
}

class hikashopZoneOverride extends hikashopZone {
	function display($field, $value, $map, $inside, $options = '', $test = false, $allFields = null, $allValues = null){
		if($field->field_options['zone_type'] != 'country' || empty($field->field_options['pleaseselect'])) {
			$currentZoneId = hikashop_getZone() ? hikashop_getZone() : '';
			if(!empty($currentZoneId) && JFactory::getApplication()->isSite()) {
				$zoneClass = hikashop_get('class.zone');
				$currentZone = $zoneClass->getZoneParents($currentZoneId);
				foreach($currentZone as $currentZoneInfos){
					if(preg_match('/country/',$currentZoneInfos)){
						$defaultCountry = $currentZoneInfos;
					}
				}
			}
		}

		if($field->field_options['zone_type'] == 'country'){
			if(isset($defaultCountry)){
				$field->field_default = $defaultCountry;
			}

			if(!empty($field->field_options['pleaseselect'])){
				$PleaseSelect = new stdClass();
				$PleaseSelect->value = JText::_('PLEASE_SELECT_SOMETHING');
				$PleaseSelect->disabled = 0;
				$field->field_value = array_merge(array('' => $PleaseSelect), $field->field_value);
				$field->field_default = '';
			}
			$stateNamekey = str_replace('country','state',$field->field_namekey);
			if(!empty($allFields)) {
				foreach($allFields as &$f) {
					if($f->field_type == 'zone' && !empty($f->field_options['zone_type']) && $f->field_options['zone_type'] == 'state') {
						$stateNamekey = $f->field_namekey;
						break;
					}
				}
			}
			$stateId = str_replace(
				array('[',']',$field->field_namekey),
				array('_','',$stateNamekey),
				$map
			);
			$form_name = str_replace(array('data[',']['.$field->field_namekey.']'), '', $map);

			$changeJs = 'window.hikashop.changeState(this,\''.$stateId.'\',\''.$field->field_url.'field_type='.$form_name.'&field_id='.$stateId.'&field_namekey='.$stateNamekey.'&namekey=\'+this.value);';
			if(!empty($options) && stripos($options,'onchange="')!==false){
				$options = preg_replace('#onchange="#i','onchange="'.$changeJs,$options);
			}else{
				$options = ' onchange="'.$changeJs.'"';
			}
			if($allFields == null || $allValues == null) {
				$doc = JFactory::getDocument();
				$lang = JFactory::getLanguage();
				$locale = strtolower(substr($lang->get('tag'),0,2));
				$js = 'window.hikashop.ready( function() {
	var el = document.getElementById(\''.$this->prefix.$field->field_namekey.$this->suffix.'\');
	window.hikashop.changeState(el,\''.$stateId.'\',\''.$field->field_url.'lang='.$locale.'&field_type='.$form_name.'&field_id='.$stateId.'&field_namekey='.$stateNamekey.'&namekey=\'+el.value);
});';
				$doc->addScriptDeclaration($js);
			}
		} elseif($field->field_options['zone_type'] == 'state') {
			$stateId = str_replace(array('[',']'),array('_',''),$map);

			$dropdown = '';

			if($allFields != null) {
				$country = null;
				if(isset($defaultCountry)){
					$country = $defaultCountry;
				}
				foreach($allFields as $f) {
					if($f->field_type == 'zone' && !empty($f->field_options['zone_type']) && $f->field_options['zone_type'] == 'country') {
						$key = $f->field_namekey;
						if(!empty($allValues->$key)) {
							$country = $allValues->$key;
						} else {
							$country = $f->field_default;
						}
						break;
					}
				}
				if(empty($country)) {
					$address_country_field = $this->parent->get(14); //14 = id of country field
					if(!empty($address_country_field) && $address_country_field->field_type=='zone' && !empty($address_country_field->field_options['zone_type']) && $address_country_field->field_options['zone_type']=='country' && !empty($address_country_field->field_default)) {
						$country = $address_country_field->field_default;
					}
				}
				if(!empty($country)) {
					$countryType = hikashop_get('type.country');
					$countryType->field = $field;
					$dropdown = $countryType->displayStateDropDown($country, $stateId, $map, '', $value, $field->field_options);
				} else {
					$dropdown = '<span class="state_no_country">'.JText::_('PLEASE_SELECT_COUNTRY_FIRST').'</span>';
				}
			}

			return '<span id="'.$stateId.'_container">'.$dropdown.'</span>'.
				'<input type="hidden" id="'.$stateId.'_default_value" name="'.$stateId.'_default_value" value="'.$value.'"/>';
		}
                $classType = 'hikashopSingledropdownOverride';
		$class = new $classType($this);
		return $class->display($field,$value,$map,$inside,$options,$test,$allFields,$allValues);
	}	
}

class hikashopMultipledropdownOverride extends hikashopMultipledropdown {
	var $type = 'multiple';
	function display($field, $value, $map, $inside, $options = '', $test = false, $allFields = null, $allValues = null){
		$value = explode(',',$value);
		return parent::display($field,$value,$map,$inside,$options,$test,$allFields,$allValues);
	}	
}

class hikashopSingledropdownOverride extends hikashopSingledropdown{
	var $type = 'single';
	function display($field, $value, $map, $inside, $options = '', $test = false, $allFields = null, $allValues = null){
            $classType = 'hikashopDropdownOverride';
            $class = new $classType($this);
            return $class->display($field,$value,$map,$inside,$options,$test,$allFields,$allValues);
	}
}