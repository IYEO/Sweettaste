<?php
/**
 * @package	HikaShop for Joomla!
 * @version	2.3.4
 * @author	hikashop.com
 * @copyright	(C) 2010-2014 HIKARI SOFTWARE. All rights reserved.
 * @license	GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 */
defined('_JEXEC') or die('Restricted access');
?><?php
$variant_name = '';
$variant_main = '_main';
$display_mode = '';
if(!empty($this->variant_name)) {
	$variant_name = $this->variant_name;
	if(substr($variant_name, 0, 1) != '_')
		$variant_name = '_' . $variant_name;
	$variant_main = $variant_name;
	$display_mode = 'display:none;';
}
?>
<div id="hikashop_product_image<?php echo $variant_main;?>" class="col-xs-12 col-sm-12 col-md-12 col-lg-12" style="<?php echo $display_mode;?>">    
    <?php
        if (!empty ($this->element->images) && count($this->element->images) > 1) { ?>
            <div class="row">
                <div id="hikashop_small_image_div<?php echo $variant_name;?>" class="hikashop_small_image_div col-xs-12 col-sm-12 col-md-12 col-lg-12">                    
            <?php $firstThunb = true;
            foreach ($this->element->images as $image) {
                if($this->image->override) {
                    echo $this->image->display($image->file_path, 'hikashop_main_image'.$variant_name, $image->file_name, 'class="hikashop_child_image img-responsive center-block"','', $width,  $height);
                } else {
                    if(empty($this->popup))
                        $this->popup = hikashop_get('helper.popup');
                    $img = $this->image->getThumbnail(@$image->file_path, array('width' => $width, 'height' => $height), $image_options);
                    if($img->success) {
                        $id = null;
                        if($firstThunb) {
                            $id = 'hikashop_first_thumbnail';
                            $firstThunb = false;
                        }
                        $attr = 'title="'.$this->escape(@$image->file_description).'" onmouseover="return window.localPage.changeImage(this, \'hikashop_main_image'.$variant_name.'\', \''.$img->url.'\', '.$img->width.', '.$img->height.', \''.str_replace("'","\'",@$image->file_description).'\', \''.str_replace("'","\'",@$image->file_name).'\');"';
                        $html = '<img class="hikashop_child_image" title="'.$this->escape(@$image->file_description).'" alt="'.$this->escape(@$image->file_name).'" src="'.$img->url.'"/>';
                        if(empty($variant_name)) {
                            echo $this->popup->image($html, $img->origin_url, $id, $attr, array('gallery' => 'hikashop_main_image'));
                        } else {
                            echo $this->popup->image($html, $img->origin_url, $id, $attr, array('gallery' => 'hikashop_main_image_VARIANT_NAME'));
                        }
                    }
                }
            } ?>
                </div>
            </div>
        <?php }
    ?>
	<div class="row">
		<div id="hikashop_main_image_div<?php echo $variant_name;?>" class="hikashop_main_image_div col-xs-12 col-sm-12 col-md-12 col-lg-12">
			<?php
				if(!empty ($this->element->images)){
					$image = reset($this->element->images);
				}
				$height = (int)$this->config->get('product_image_y');
				$width = (int)$this->config->get('product_image_x');
				if(empty($height)) $height = (int)$this->config->get('thumbnail_y');
				if(empty($width)) $width = (int)$this->config->get('thumbnail_x');
				$divWidth = $width;
				$divHeight = $height;
				$this->image->checkSize($divWidth,$divHeight,$image);
	
				if (!$this->config->get('thumbnail')) {
					if(!empty ($this->element->images)){
						echo '<img src="' . $this->image->uploadFolder_url . $image->file_path . '" alt="' . $image->file_name . '" id="hikashop_main_image" style="margin-top:0px;margin-bottom:10px;display:inline-block;vertical-align:middle" class="img-responsive center-block" />';
					}
				} else {
					$style = '';
					if (!empty ($this->element->images) && count($this->element->images) > 1) {
						if (!empty($height)) {
							$style = ' style="height:' . ($height + 20) . 'px;"';
						}
					}
					$variant_name='';
					if(isset($this->variant_name)){
						$variant_name=$this->variant_name;
					}
	
					if($this->image->override) {
						echo $this->image->display(@$image->file_path,true,@$image->file_name,'id="hikashop_main_image'.$variant_name.'" style="margin-top:0px;margin-bottom:10px;display:inline-block;vertical-align:middle"','id="hikashop_main_image_link"', $width,  $height);
					} else {
						if(empty($this->popup))
							$this->popup = hikashop_get('helper.popup');
						$image_options = array('default' => true,'forcesize'=>$this->config->get('image_force_size',true),'scale'=>$this->config->get('image_scale_mode','inside'));
						$img = $this->image->getThumbnail(@$image->file_path, array('width' => $width, 'height' => $height), $image_options);
						if($img->success) {
							$attr = 'title="'.$this->escape(@$image->file_description).'"';
							if (!empty ($this->element->images) && count($this->element->images) > 1) {
								$attr .= 'onclick="return window.localPage.openImage(\'hikashop_main_image'.$variant_name.'\');"';
							}
							$html = '<img id="hikashop_main_image'.$variant_name.'" class="img-responsive center-block" title="'.$this->escape(@$image->file_description).'" alt="'.$this->escape(@$image->file_name).'" src="'.$img->url.'"/>';
							if(!empty($this->element->badges))
								$html .= $this->classbadge->placeBadges($this->image, $this->element->badges, '0', '0',false);
	
							//Lightbox output
							echo '<a href="' . $img->origin_url . '" '. ' data-lightbox="' . $this->escape(@$image->file_name) . '" data-title="' . $this->escape(@$image->file_description) . '">' . $html . '</a>';
						}
					}						
				}
				?>
		</div>
	</div>	
</div>
<script type="text/javascript">
if(!window.localPage)
	window.localPage = {};
if(!window.localPage.images)
	window.localPage.images = {};
window.localPage.changeImage = function(el, id, url, width, height, title, alt) {
	var d = document, target = d.getElementById(id);
	if(!target) return false;
	target.src = url;
	target.width = width;
	target.height = height;
	target.title = title;
	target.alt = alt;
	window.localPage.images[id] = el;
	return false;
};
window.localPage.openImage = function(id) {
	if(!window.localPage.images[id])
		window.localPage.images[id] = document.getElementById('hikashop_first_thumbnail');
	window.localPage.images[id].click();
	return false;
};
</script>
