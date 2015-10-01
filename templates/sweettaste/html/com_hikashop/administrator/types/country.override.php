<?php
include_once $originalFile;

class hikashopCountryTypeOverride extends hikashopCountryType {
    function displayStateDropDown($namekey, $field_id, $field_namekey, $field_type, $value = '', $field_options = array()) {
            $this->type = 'state';
            $this->published = true;
            $this->country_name = $namekey;
            $states = $this->load();

            $obj = new stdClass();
            $obj->suffix = '';
            $obj->prefix = '';
            $obj->excludeValue = array();

            $fieldClass = hikashop_get('class.field');
            $dropdown = new hikashopSingledropdownOverride($obj);
            $field = new stdClass();
            $field->field_namekey = $field_id;
            $statesArray = array();
            if(!empty($states)) {
                    if(!empty($field_options) && is_string($field_options)){
                            $field_options = unserialize($field_options);
                    }

                    $pleaseSelect = !empty($field_options['pleaseselect']);

                    if($pleaseSelect){
                            $pleaseSelect = 0;
                            $obj = new stdClass();
                            $obj->disabled = '0';
                            $obj->value = JText::_('PLEASE_SELECT_SOMETHING');
                            $statesArray[''] = $obj;
                    }
                    foreach($states as $state) {
                            if(is_numeric($state->zone_name_english)){
                                    $title = $state->zone_name;
                            }else{
                                    $title = $state->zone_name_english;
                                    if($state->zone_name_english != $state->zone_name){
                                            $title .= ' ('.$state->zone_name.')';
                                    }
                            }
                            $obj = new stdClass();
                            $obj->disabled = '0';
                            $obj->value = $title;
                            $statesArray[$state->zone_namekey] = $obj;
                    }
            } else {
                    $value = 'no_state_found';
            }

            $field->field_value = $statesArray;
            if(!empty($field_type)) {
                    $name = 'data['.$field_type.']['.$field_namekey.']';
            } else {
                    $name = $field_namekey;
            }
            return $dropdown->display($field, $value, $name, '', '');
    }
}
?>