<?php
/**
 * @copyright	Copyright (C) 2009-2010 HIKARI SOFTWARE SARL - All rights reserved.
 * @license		http://www.gnu.org/licenses/gpl-3.0.html GNU/GPL
 */
defined('_JEXEC') or die('Restricted access');
?>
<?php
class plgSystemRecaptcha2 extends JPlugin
{
	function __construct(&$subject, $config){
		parent::__construct($subject, $config);
		$this->_init();
	}

	function _init() {
		if(isset($this->params))
			return;

		$plugin = JPluginHelper::getPlugin('system', 'recaptcha2');
		if(version_compare(JVERSION,'2.5','<')) {
			jimport('joomla.html.parameter');
			$this->params = new JParameter($plugin->params);
		} else {
			$this->params = new JRegistry($plugin->params);
		}
	}

	function onAfterRender(){
		$option = JRequest::getString('option');

		if(empty($option)) return;

		$user = JFactory::getUser();
		if (!$user->guest) {
			return true;
		}

		$components = array();
		$components['com_user'] = array('view' => array('register'),'lengthafter' => 200);
		$components['com_users'] = array('view' => array('registration'),'lengthafter' => 200, 'email' => 'jform\[email2\]', 'password' => 'jform\[password2\]');
		$components['com_alpharegistration'] = array('view' => array('register'),'lengthafter' => 250);
		$components['com_ccusers'] = array('view' => array('register'),'lengthafter' => 500);
		$components['com_virtuemart'] = array('view' => array('shop.registration'),'viewvar' => 'page','lengthafter' => 500);
		$components['com_hikashop'] = array('view' => array('checkout','user'),'viewvar' => 'ctrl', 'lengthafter' => 500 , 'tdclass' => 'key', 'email' => 'data\[register\]\[email_confirm\]','password' => 'data\[register\]\[password2\]');
		$components['com_hikamarket'] = array('view' => array('vendor'),'viewvar' => 'ctrl', 'lengthafter' => 500 , 'tdclass' => 'key', 'email' => 'data\[register\]\[email_confirm\]', 'password' => 'data\[register\]\[password2\]');
		$components['com_akeebasubs'] = array('view' => array('level'),'lengthafter' => 200, 'email' => 'email2', 'password' => 'password2');
		if($this->params->get('contact',0)){
			$components['com_hikashop']['view'][]='product';
			$components['com_hikashop']['contact']=1;
			$components['com_contact'] = array('view' => array('contact'),'lengthafter' => 200,'contact'=>1);
			if(version_compare(JVERSION,'1.6','>=')){
				$components['com_contact']['contact_text'] = 'jform_contact_message';
				$components['com_contact']['contact_email'] = 'jform_contact_email';
				$components['com_contact']['contact_email_copy'] = 'jform_contact_email_copy';
			}
			$components['com_gcontact'] = array('view' => array('registration'),'lengthafter' => 200,'contact'=>1);
			$components['com_qcontacts'] = array('view' => array('contact'),'lengthafter' => 400,'contact'=>1);
			$components['com_contact_enhanced'] = array('view' => array('contact'),'lengthafter' => 200,'contact'=>1, 'contact_text' => 'cf_4', 'contact_email' => 'email');
		}
		if($this->params->get('comment',0)){
			$components['com_hikashop']['view'][] = 'product';
			$components['com_hikashop']['comment']=1;
		}

		if(!isset($components[$option])) return;
		$viewVar = (isset($components[$option]['viewvar']) ? $components[$option]['viewvar'] : 'view');
		if(!in_array(JRequest::getString($viewVar,JRequest::getString('task')),$components[$option]['view'])) return;


		if(!defined('DS'))
			define('DS', DIRECTORY_SEPARATOR);
		if(!include_once(rtrim(JPATH_ADMINISTRATOR,DS).DS.'components'.DS.'com_hikashop'.DS.'helpers'.DS.'helper.php')) return true;

		$subText = $this->params->get('captchatext');
		if(empty($subText)){
			$subText = JText::_('Captcha').':';
		}
		$body = JResponse::getBody();
		$alternate_body = false;
		if(empty($body)){
			$app = JFactory::getApplication();
			$body = $app->getBody();
			$alternate_body = true;
		}

		if(!empty($components[$option]['comment'])){
			$id = $this->params->get('commentpos','hikashop_vote_comment');
			if($option=='com_hikashop'){
				switch($id){
					case 'pseudo_comment':
						$after = 'id=\'pseudo_comment\' ?v?a?l?u?e?=?\'?0?\'?/>';
						break;
					case 'email_comment':
						$after = 'id=\'email_comment\' value=\'0?\'/>';
						break;
					case 'hikashop_vote_comment':
					default:
						$after = 'id="hikashop_vote_comment".{0,'.$components[$option]['lengthafter'].'}</textarea>';
						break;
				}
				$c = new hikaShopRecaptcha2();
				$c->params = $this->params;
				$return = '<table class="hikashop_comment_form"><tr class="hikashop_captcha"><td valign="top">'.$subText.'</td><td>'.$c->recaptcha_get_html($this->params->get('public_key'),$this->params->get('theme')).'</td></tr></table>';
				$replace= $after;
			}
			if(preg_match('#'.$replace.'#Uis',$body)){
				$body = preg_replace('#('.$replace.')#Uis','$1'.$return,$body,1);
				if($alternate_body){
					$app->setBody($body);
				}else{
					JResponse::setBody($body);
				}
				return;
			}
			if($option!='com_hikashop'){
				return true;
			}
		}

		if(!empty($components[$option]['contact'])){
			$id = $this->params->get('checkpos','contact_text');
			if($option=='com_hikashop'){
				switch($id){
					case 'contact_email_copy':
					case 'contact_email':
						$after = 'data\[contact\]\[email\]';
						break;
					case 'contact_text':
					default:
						$after = 'data\[contact\]\[altbody\]';
						break;
				}
				$c = new hikaShopRecaptcha2();
				$c->params = $this->params;
				$return = '<dt>'.$subText.'</dt><dd class="hikashop_captcha">'.$c->recaptcha_get_html($this->params->get('public_key'),$this->params->get('theme')).'</dd>';
				$replace= 'name="'.$after.'".{0,'.$components[$option]['lengthafter'].'}</dd>';
				if(preg_match('#'.$replace.'#Uis',$body)){
					$body = preg_replace('#('.$replace.')#Uis','$1'.$return,$body,1);
					if($alternate_body){
						$app->setBody($body);
					}else{
						JResponse::setBody($body);
					}
					return;
				}
				$return = '<tr class="hikashop_captcha"><td valign="top">'.$subText.'</td><td>'.$c->recaptcha_get_html($this->params->get('public_key'),$this->params->get('theme')).'</td></tr>';
				$replace= 'name="'.$after.'".{0,'.$components[$option]['lengthafter'].'}</tr>';
			}else{
				$c = new hikaShopRecaptcha2();
				$c->params = $this->params;
				if(isset($components[$option][$id])) $id = $components[$option][$id];
				$replace = 'id="'.$id.'".{0,'.$components[$option]['lengthafter'].'}(<br */>|</p>|</div>|</dd>)';

				if($option=='com_contact' && version_compare(JVERSION,'3.0','>=')){
					$return = '<div class="control-group"><div class="control-label"><label id="recaptcha_response_field-lbl" for="recaptcha_response_field" class="required">'.$subText.'<span class="star">&nbsp;*</span></label></div>';
					$return .= '<div class="controls">'.$c->recaptcha_get_html($this->params->get('public_key'),$this->params->get('theme')).'</div></div>';
					$replace = 'id="'.$id.'".{0,'.$components[$option]['lengthafter'].'}(<\/div>\r?\n?\t*<\/div>)';
				}elseif($option=='com_contact' && version_compare(JVERSION,'1.6','>=')){
					$return = '<dt>'.$subText.'</dt>';
					$return .= '<dd class="hikashop_captcha">'.$c->recaptcha_get_html($this->params->get('public_key'),$this->params->get('theme')).'</dd>';
				}else{
					$return = '<label>'.$subText.'</label><br/>';
					$return .= '<span class="hikashop_captcha">'.$c->recaptcha_get_html($this->params->get('public_key'),$this->params->get('theme')).'</span><br />';
				}

			}

			if(preg_match('#'.$replace.'#Uis',$body)){
				$body = preg_replace('#('.$replace.')#Uis','$1'.$return,$body,1);
				if($alternate_body){
					$app->setBody($body);
				}else{
					JResponse::setBody($body);
				}
				return;
			}
			if($option!='com_hikashop'){
				return true;
			}
		}

		if($this->params->get('registration',1)){
			if(!empty($components[$option][$this->params->get('fieldafter','password')])){
				$after = $components[$option][$this->params->get('fieldafter','password')];
			}else{
				if($this->params->get('fieldafter','password') == 'custom'){ $after =  $this->params->get('fieldaftercustom'); }
				else{ $after = ($this->params->get('fieldafter','password') == 'email') ? 'email' : 'password2'; }
			}
			$c = new hikaShopRecaptcha2();
			$return = $c->recaptcha_get_html($this->params->get('public_key'),$this->params->get('theme'));

			if(!$this->parseBody($body,$after,$components[$option]['lengthafter'],$return,$subText)){
				if($option=='com_hikashop' || $option=='com_hikamarket'){
					if($after=='data\[register\]\[password2\]'){
						$after='data\[register\]\[email_confirm\]';
						if($this->parseBody($body,$after,$components[$option]['lengthafter'],$return,$subText)){
							return true;
						}
					}
					if($after=='data\[register\]\[email_confirm\]'){
						$after='data\[register\]\[email\]';
						$this->parseBody($body,$after,$components[$option]['lengthafter'],$return,$subText);
					}
				}
			}
		}
	}

	function parseBody(&$body,$after,$lengthafter,$return,$subText){
		$body = JResponse::getBody();
		$alternate_body = false;
		if(empty($body)){
			$alternate_body = true;
		}
		if(preg_match('#(name="'.$after.'".{0,'.$lengthafter.'}</tr>)#Uis',$body)){
			$return = '<tr class="recaptcha"><td valign="top">'.$subText.'</td><td>'.$return.'</td></tr>';
			$body = preg_replace('#(name="'.$after.'".{0,'.$lengthafter.'}</tr>)#Uis','$1'.$return,$body,1);
			if($alternate_body){
				$app->setBody($body);
			}else{
				JResponse::setBody($body);
			}
			return true;
		}

		if(preg_match('#(name="'.$after.'".{0,'.$lengthafter.'}</div>)#Uis',$body)){
			if(preg_match('#id="(akeebasubs|member-registration)"#Uis', $body)){
				$return = '</div><div class="control-group"><label class="control-label required" for="recaptcha_response_field">'.$subText.'<span class="star">&nbsp;*</span></label><div class="recaptcha controls">'.$return.'</div>';
			}else{
				$return = '<div class="recaptcha"><label>'.$subText.'</label>'.$return.'</div>';
			}
			$body = preg_replace('#(name="'.$after.'".{0,'.$lengthafter.'}</div>)#Uis','$1'.$return,$body,1);
			if($alternate_body){
				$app->setBody($body);
			}else{
				JResponse::setBody($body);
			}
			return true;
		}
		if(preg_match('#(name="'.$after.'".{0,'.$lengthafter.'}</p>)#Uis',$body)){
			$return = '<div class="recaptcha"><label>'.$subText.'</label>'.$return.'</div>';
			$body = preg_replace('#(name="'.$after.'".{0,'.$lengthafter.'}</p>)#Uis','$1'.$return,$body,1);
			if($alternate_body){
				$app->setBody($body);
			}else{
				JResponse::setBody($body);
			}
			return true;
		}
		if(preg_match('#(name="'.$after.'".{0,'.$lengthafter.'}</dd>)#Uis',$body)){
			$return = '<dt class="recaptcha"><label>'.$subText.'</label></dt><dd>'.$return.'</dd>';
			$body = preg_replace('#(name="'.$after.'".{0,'.$lengthafter.'}</dd>)#Uis','$1'.$return,$body,1);
			if($alternate_body){
				$app->setBody($body);
			}else{
				JResponse::setBody($body);
			}
			return true;
		}
		return false;
	}

	function onUserBeforeSave($user, $isnew, $new){
		return $this->onBeforeStoreUser($user, $isnew);
	}

	function onSubmitContact($contact,$post){
		$app = JFactory::getApplication();
		if ($app->isAdmin()) return true;
		$this->_init();
		if(!$this->params->get('contact',0)){
			return true;
		}
		return $this->_checkCaptcha();
	}

	function onBeforeVoteCreate(&$element,&$do){
		$app = JFactory::getApplication();
		if ($app->isAdmin()) return true;
		$this->_init();
		if(!$this->params->get('comment',0)){
			return true;
		}
		if(empty($element->vote_comment)){
			return true;
		}
		$do = $this->_checkCaptcha();
		return true;
	}

	function onBeforeSendContactRequest(&$element,&$send){
		$app = JFactory::getApplication();
		if ($app->isAdmin()) return true;
		$this->_init();
		if(!$this->params->get('contact',0)){
			return true;
		}
		$send = $this->_checkCaptcha();
		return true;
	}

	function onBeforeStoreUser($user, $isnew, $new=null){
		if(!$isnew) return true;

		$app = JFactory::getApplication();
		if ($app->isAdmin() || (@$_REQUEST['option']=='com_updateme' && @$_REQUEST['ctrl']=='subscription' && @$_REQUEST['task']=='api')) return true;

		$this->_init();
		if(!$this->params->get('registration',1)){
			return true;
		}
		return $this->_checkCaptcha();
	}

	function _checkCaptcha(){
		$user = JFactory::getUser();
		if (!$user->guest) {
			return true;
		}

		$c = new hikaShopRecaptcha2();
		$resp = $c->recaptcha_check_answer($this->params->get('private_key'), @$_SERVER["REMOTE_ADDR"], @$_POST["g-recaptcha-response"]);

		if (empty($resp->success)) {
			$lang = JFactory::getLanguage();
			$lang->load('plg_system_recaptcha2', JPATH_ADMINISTRATOR);
			if(!empty($resp->errorCodes)){
				if(!is_array($resp->errorCodes)){
					$resp->errorCodes = array($resp->errorCodes);
				}
				foreach($resp->errorCodes as $k => $code){
					switch($code){
						default:
							$resp->errorCodes[$k] = JText::_('CAPTCHA_ERROR_UNKNOWN');
							break;
						case 'invalid-site-public-key':
							$resp->errorCodes[$k] = JText::_('CAPTCHA_ERROR_INVALIDPUBLICKEY');
							break;
						case 'missing-input-secret':
						case 'invalid-input-secret':
							$resp->errorCodes[$k] = JText::_('CAPTCHA_ERROR_INVALIDPRIVATEKEY');
							break;
						case 'invalid-request-cookie':
							$resp->errorCodes[$k] = JText::_('CAPTCHA_ERROR_INVALIDREQUESTCOOKIE');
							break;
						case 'missing-input':
						case 'missing-input-response':
						case 'invalid-input-response':
							$resp->errorCodes[$k] = JText::_('CAPTCHA_ERROR_INCORRECTSOLUTION');
							break;
						case 'verify-params-incorrect':
							$resp->errorCodes[$k] = JText::_('CAPTCHA_ERROR_INCORRECTPARAMETERS');
							break;
						case 'invalid-referrer':
							$resp->errorCodes[$k] = JText::_('CAPTCHA_ERROR_INVALIDREFERRER');
							break;
						case 'recaptcha-not-reachable':
							$resp->errorCodes[$k] = JText::_('CAPTCHA_ERROR_UNREACHABLERECAPTCHA');
							break;
					}
				}
			}else{
				$resp->errorCodes = JText::_('CAPTCHA_ERROR_UNREACHABLERECAPTCHA');
			}

			if(is_array($resp->errorCodes)){
				$resp->errorCodes = implode('<br/>',$resp->errorCodes);
			}
			$app = JFactory::getApplication();
			$app->redirect(@$_SERVER['HTTP_REFERER'],$resp->errorCodes,'error');
			return false;
		}
		return true;
	}

}//endclass


class hikaShopRecaptcha2 {

	/**
	 * Gets the challenge HTML (javascript and non-javascript version).
	 * This is called from the browser, and the resulting reCAPTCHA HTML widget
	 * is embedded within the HTML form it was called from.
	 * @param string $pubkey A public key for reCAPTCHA
	 * @param string $theme The theme for the captcha
	 * @return string - The HTML to be embedded in the user's form.
	 */
	function recaptcha_get_html ($pubkey, $theme) {
		if($pubkey == null || $pubkey == '') {
			die ("To use reCAPTCHA you must get an API key from <a href='https://www.google.com/recaptcha/admin'>https://www.google.com/recaptcha/admin</a>");
		}

		$lang = JFactory::getLanguage();
		$tag = $lang->get('tag');
		$locale=strtolower(substr($lang->get('tag'),0,2));
		if(in_array($tag,array('zh-CN','zh-TW','en-GB','fr-CA','de-AT','de-CH','pt-BR','pt-PT'))){
			$locale = $tag;
		}elseif(!in_array($locale,array('ar','bg','ca','hr','cs','da','nl','en','fil','fi','fr','de','el','iw','hi','hu','id','it','ja','ko','lv','lt','no','fa','pl','pt','ro','ru','sr','sk','sl','es','sv','th','tr','uk','vi'))){
			$locale = 'en';
		}

		return '
<script type="text/javascript" src="https://www.google.com/recaptcha/api.js?hl='.$locale.'" async defer></script>
<div id="g-recaptcha" class="g-recaptcha" data-sitekey="'.$pubkey.'" data-theme="'.$theme.'"></div>';
	}

	/**
	* Calls an HTTP POST function to verify if the user's guess was correct
	* @param string $privkey
	* @param string $remoteip
	* @param string $response
	* @param array $extra_params an array of extra variables to post to the server
	* @return ReCaptchaResponse
	*/
	function recaptcha_check_answer ($privkey, $remoteip, $response, $extra_params = array())
	{
		if ($privkey == null || $privkey == '') {
			die ("To use reCAPTCHA you must get an API key from <a href='https://www.google.com/recaptcha/admin'>https://www.google.com/recaptcha/admin</a>");
		}

		if ($remoteip == null || $remoteip == '') {
			die ("For security reasons, you must pass the remote ip to reCAPTCHA");
		}

		// Discard empty solution submissions
		if ($response == null || strlen($response) == 0) {
			$recaptchaResponse = new HikaShopReCaptchaResponse2();
			$recaptchaResponse->success = false;
			$recaptchaResponse->errorCodes = 'missing-input';
			return $recaptchaResponse;
		}
		$getResponse = $this->_submitHTTPGet(
			"https://www.google.com/recaptcha/api/siteverify?",
			array (
				'secret' => $privkey,
				'remoteip' => $remoteip,
				'v' => "php_1.0",
				'response' => $response
			)
		);
		$answers = json_decode($getResponse, true);
		$recaptchaResponse = new HikaShopReCaptchaResponse2();
		if (trim($answers ['success']) == true) {
			$recaptchaResponse->success = true;
		} else {
			$recaptchaResponse->success = false;
			$recaptchaResponse->errorCodes = $answers['error-codes'];
		}
		return $recaptchaResponse;

	}

	/**
	 * Encodes the given data into a query string format.
	 *
	 * @param array $data array of string elements to be encoded.
	 *
	 * @return string - encoded request.
	 */
	function _encodeQS($data)
	{
		$req = "";
		foreach ($data as $key => $value) {
			$req .= $key . '=' . urlencode(stripslashes($value)) . '&';
		}
		// Cut the last '&'
		$req=substr($req, 0, strlen($req)-1);
		return $req;
	}

	/**
	 * Submits an HTTP GET to a reCAPTCHA server.
	 *
	 * @param string $path url path to recaptcha server.
	 * @param array  $data array of parameters to be sent.
	 *
	 * @return array response
	 */
	function _submitHTTPGet($path, $data)
	{
		$req = $this->_encodeQS($data);
		$response = file_get_contents($path . $req);
		return $response;
	}

}

/**
 * A ReCaptchaResponse is returned from recaptcha_check_answer()
 */
class HikaShopReCaptchaResponse2 {
	var $success;
	var $errorCodes;
}