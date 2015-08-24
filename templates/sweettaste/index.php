<?php
defined('_JEXEC') or die;

$app = JFactory::getApplication();
$menu = $app->getMenu()->getActive();
$pageclass = '';
if (is_object($menu)) {
    $pageclass = $menu->params->get('pageclass_sfx');
}
?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="<?php echo $this->language; ?>" lang="<?php echo $this->language; ?>" >
	<head>
            <meta http-equiv="X-UA-Compatible" content="IE=edge">
            <meta name="viewport" content="width=device-width, initial-scale=1">		
            <jdoc:include type="head" />
            <?php
            JHtml::stylesheet($this->baseurl . 'templates/' . $this->template . '/css/template.css');
            JHtml::_('jquery.framework');
            JHtml::script('bootstrap.min.js', false, true);
            JHtml::script('com_hikashop/hikashop.js', false, true);            
            //JFactory::getDocument()->addScriptVersion($this->baseurl . 'templates/' . $this->template . '/js/com_hikashop/hikashop.js','v=241');
            ?>            
	</head>
	<body <?php echo $pageclass ? 'class='.htmlspecialchars($pageclass) : ''; ?>>
            <nav class="navbar navbar-default navbar-fixed-top" role="navigation">
                    <div class="container">
                        <div class="navbar-header visible-xs">
                <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#top-menu-collapse">
                    <span class="sr-only">Навигация</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
                <a class="navbar-brand" href="/">                            
                    <img class="img-responsive i-social-size" alt="На главную" src="images/logo.png" />
                </a>                                            
            </div>
            <div class="collapse navbar-collapse" id="top-menu-collapse">
                        <div class="row">
                    <div class="col-sm-4 col-md-4 col-lg-4">                            
                        <jdoc:include type="modules" name="navbar" style="xhtml" />
                        <jdoc:include type="modules" name="about" style="xhtml" />
                    </div>
                    <jdoc:include type="modules" name="logo" style="xhtml" />
                    <jdoc:include type="modules" name="cart" style="xhtml" />                        
                        </div>                
            </div>                                
                    </div>
            </nav>
            <div class="container-fluid">
                    <!-- Content -->
                    <div class="row">
                        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                            <jdoc:include type="message" />
                        </div>			    
                    </div>
                    <div class="row">
                        <jdoc:include type="modules" name="content" style="xhtml" />
                            <jdoc:include type="component" />
                    </div>
                    <!-- Footer -->
                    <div class="row">
                            <jdoc:include type="modules" name="footer" style="xhtml" />
                    </div>			
            </div>            
	</body>
</html>