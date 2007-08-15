<?php
/*
Plugin Name: Shutter Reloaded
Plugin URI: http://www.laptoptips.ca/projects/wp-shutter-reloaded/
Description: Darkens the current page and displays an image on top like Lightbox, Thickbox, etc. However this script is a lot smaller and faster.
Version: 1.0
Author: Andrew Ozz
Author URI: http://www.laptoptips.ca/

Acknowledgement: some code and/or ideas are from: Shutter by Andrew Sutherland - http://code.jalenack.com, WordPress - http://wordpress.org, Lightbox by Lokesh Dhakar - http://www.huddletogether.com, IE6 css position:fixed fix ideas from gunlaug.no and quirksmode.org

Released under the GPL, http://www.gnu.org/copyleft/gpl.html

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.
*/
if ('tinymce-advanced.php' == basename($_SERVER['SCRIPT_FILENAME']))
    exit;

if( ! class_exists(sh_reloadedClass) ) {
class sh_reloadedClass {
    var $srel_options, $srel_main;

function sh_reloadedClass() {
    $this->srel_options = get_option('srel_options');
    $this->srel_main = get_option('srel_main');
}

function srel_head() {
    global $post;

    switch( $this->srel_main ) {
    case 'srel_pages' :
        $included = $this->srel_options['srel_included'];
        if ( is_array($included) && in_array( $post->ID, $included ) ) {
            add_action( 'wp_head', array(&$this, 'makeshutter') );
        }
        break;
    
    case 'auto_set' :
        add_action( 'wp_head', array(&$this, 'makeshutter') );
        add_filter('the_content', array(&$this, 'srel_auto_set'), 65 );
        break;
    
    case 'srel_class' :
        add_action( 'wp_head', array(&$this, 'makeshutter') );
        break;
    
    default :
        $excluded = $this->srel_options['srel_excluded'];
        if ( !is_array($excluded) ) {
            add_action( 'wp_head', array(&$this, 'makeshutter') );
        } elseif ( !in_array( $post->ID, $excluded ) ) {
            add_action( 'wp_head', array(&$this, 'makeshutter') );
        }
    }
}

function makeshutter() { 
?>
<script type="text/javascript">
//<![CDATA[
function shutterOnload(func){var oldonload=window.onload;if(typeof window.onload!='function'){window.onload = func;}
else{window.onload=function(){oldonload();func();}}}
var shutterLinks = [], shutterSets = [];
shutterOnload( function() {
  for ( i = 0; i < document.links.length; i++ ) {
<?php 
    if ( $this->srel_main == 'srel_class' || $this->srel_main == 'auto_set' ) { ?>
    if ( document.links[i].className.toLowerCase().indexOf('<?php echo $this->srel_options['srel_classname']; ?>') != -1 ) {
<?php 
} ?>
      var shtype = document.links[i].href.slice(-4).toLowerCase();
      if ( shtype == '.jpg' || shtype == '.png' || shtype == '.gif' || shtype == 'jpeg' ) {
        shutterLinks[i] = document.links[i].href;
        if ( document.links[i].className.toLowerCase().indexOf('shutterset') != -1 ) {
          var set = ( document.links[i].className.indexOf(' ') != -1 ) ? document.links[i].className.slice(0,document.links[i].className.indexOf(' ')) :  document.links[i].className;
          var setid = ( set.indexOf('_') != -1 ) ? parseInt(set.slice(set.indexOf('_') + 1)) : 0;
          setid = isNaN(setid) ? 0 : setid;
          if ( shutterSets[setid] == null ) shutterSets[setid] = []; 
          inset = shutterSets[setid].push(i);
        } else { inset = '-1'; setid = 0; }
        document.links[i].href = 'javascript:mkShutter('+i+','+setid+','+inset+')'; 
      }
<?php 
    if ( $this->srel_main == 'srel_class' || $this->srel_main == 'auto_set' ) echo "    }\n"; ?>
  }
});
//]]>
</script>
<link rel="stylesheet" href="<?php bloginfo('wpurl'); ?>/wp-content/plugins/shutter-reloaded/shutter-reloaded.css?ver=1.0" type="text/css" media="screen" />
<style type="text/css">
#shNewShutter{background-color:#<?php echo $this->srel_options['shcolor']; ?>;opacity:<?php echo ($this->srel_options['opacity']/100); ?>;filter:alpha(opacity=<?php echo $this->srel_options['opacity']; ?>);}
#shNewDisplay #shTextWrap a {color:#<?php echo $this->srel_options['btncolor']; ?>;}
#shNewDisplay #shTextWrap #shTitle {color:#<?php echo $this->srel_options['capcolor']; ?>;}
#shNewDisplay #shWaitBar {color:#<?php echo $this->srel_options['waitcolor']; ?>;}
</style>
<script src="<?php bloginfo('wpurl'); ?>/wp-content/plugins/shutter-reloaded/shutter-reloaded.js?ver=1.0" type="text/javascript"></script>
<?php
}

function srel_auto_set($content) {
	global $post;
  	
    $pattern = array( '/<a([^>]*)href=[\'"]([^"]+).(gif|jpeg|jpg|png)[\'"]([^>]*>)[\s\n\t\r]*<img/i', '/<a class="shutterset_%SRELID%" href="([^"]+)"([^>]*)class=[\'"]([^"]+)[\'"]([^>]*>)/i' );
    $replacement = array( '<a class="shutterset_%SRELID%" href="$2.$3"$1$4<img', '<a class="shutterset_%SRELID% $3" href="$1"$2$4' );
    $content = preg_replace($pattern, $replacement, $content);

    return str_replace('%SRELID%', $post->ID, $content);
}

function srel_activ() {
    $arr = array( 'shcolor' => '000000', 'opacity' => '80', 'capcolor' => 'ffffff', 'btncolor' => '999999', 'waitcolor' => 'ae0a0a', 'srel_classname' => 'shutter' );
    add_option( 'srel_options', $arr, 'Shutter Reloaded' );
}

function srel_deact() {
    delete_option('srel_options');
    delete_option('srel_main');
}
} } // end sh_reloadedClass 

function srel_optpage() {
    $srel_options = get_option('srel_options');

    if ( ! current_user_can('edit_posts') )
        die( 'Permission denied' );
    
    if ( isset($_POST['srel_main']) ) {
        check_admin_referer('srel-save-options');
        $opt = $_POST['srel_all'] ? 'srel_all' : '';
        $opt = $_POST['srel_class'] ? 'srel_class' : $opt;
        $opt = $_POST['auto_set'] ? 'auto_set' : $opt;
        $opt = $_POST['srel_pages'] ? 'srel_pages' : $opt;
        update_option("srel_main", $opt);
    } else {
        $opt = get_option('srel_main') ? get_option('srel_main') : 'srel_all';
    }

    if ( isset($_POST['srel_shopt']) ) {
        check_admin_referer('srel-save-options');
        $srel_options['shcolor'] = preg_match("/[0-9A-Fa-f]{6}/", $_POST['shcolor']) ? $_POST['shcolor'] : '000000';
        $srel_options['capcolor'] = preg_match("/[0-9A-Fa-f]{6}/", $_POST['capcolor']) ? $_POST['capcolor'] : 'ffffff';
        $srel_options['btncolor'] = preg_match("/[0-9A-Fa-f]{6}/", $_POST['btncolor']) ? $_POST['btncolor'] : '999999';
        $srel_options['waitcolor'] = preg_match("/[0-9A-Fa-f]{6}/", $_POST['waitcolor']) ? $_POST['waitcolor'] : 'ae0a0a';
        $srel_options['opacity'] = preg_match("/^[0-9][0-9]?$/", $_POST['opacity']) ? $_POST['opacity'] : '80';
        update_option('srel_options', $srel_options);
    }

    if ( isset($_POST['srel_chclassname']) ) {
        check_admin_referer('srel-save-options');
        $srel_options['srel_classname'] = $_POST['srel_classname'] ? strip_tags(strtolower(trim($_POST['srel_classname']))) : 'shutter';
        update_option('srel_options', $srel_options);
    }

    $excluded = $srel_options['srel_excluded'] ? $srel_options['srel_excluded'] : array();
    $included = $srel_options['srel_included'] ? $srel_options['srel_included'] : array();
    
    if ( isset($_POST['srel_add_excluded']) ) {
        check_admin_referer('srel-save-options');
        $exclude = (int) $_POST['srel_exclude'];
        if ( $exclude <= 0 ) {
            die('Please enter valid post ID.');
        }
        $excluded[] = $exclude;
        $excluded = array_values(array_unique($excluded));
        sort($excluded);
        $srel_options['srel_excluded'] = $excluded;
        update_option('srel_options', $srel_options);
    }
  
    if ( isset($_POST['srel_rem_excluded']) ) {
        check_admin_referer('srel-save-options');
        $rem_exclude = (int) $_POST['srel_exclude'];
        if ( is_array($excluded) && !in_array($rem_exclude, $excluded) ) { ?>
            <div style="background-color: rgb(230, 230, 255);" id="error" class="updated fade-5555cc"><p><strong>This post ID is not currently excluded.</strong></p></div>
<?php   } else {
            
            $excluded = array_diff($excluded, (array) $rem_exclude );
            sort($excluded);
            $srel_options['srel_excluded'] = $excluded;
            update_option('srel_options', $srel_options);
        }
    }

    if ( isset($_POST['srel_add_included']) ) {
        check_admin_referer('srel-save-options');
        $include = (int) $_POST['srel_include'];
        if ( $include <= 0 ) {
            die('Please enter valid post ID.');
        }
        $included[] = $include;
        $included = array_values(array_unique($included));
        sort($included);
        $srel_options['srel_included'] = $included;
        update_option('srel_options', $srel_options);
    }
  
    if ( isset($_POST['srel_rem_included']) ) {
        check_admin_referer('srel-save-options');
        $rem_include = (int) $_POST['srel_include'];
        if ( is_array($included) && !in_array($rem_include, $included) ) { ?>
            <div style="background-color: rgb(230, 230, 255);" id="error" class="updated fade-5555cc"><p><strong>This post ID is not currently included.</strong></p></div>
<?php   } else {
            $included = array_diff($included, (array) $rem_include);
            sort($included);
            $srel_options['srel_included'] = $included;
            update_option('srel_options', $srel_options);
        }
    }

    if ( isset($_POST['srel_shopt']) ) { ?>
    <div style="background-color: rgb(230, 230, 255);" id="message" class="updated fade-5555cc"><p><strong>Options saved!</strong></p></div>
<?php } ?>

    <div class="wrap">
    <h2>Shutter Reloaded Options</h2>
    <form method="post" name="srel_mainform" id="srel_mainform" action="">
    <fieldset>
    
    <p class="submit" style="margin-top:0;"><input type="button" id="srelhide" onclick="if(document.getElementById('srelhelp').style.display == 'none'){document.getElementById('srelhelp').style.display = 'block';document.getElementById('srelhide').value = 'Hide Help';
} else {document.getElementById('srelhelp').style.display = 'none';document.getElementById('srelhide').value = 'Show Help';}" 
value="Show Help" style="color:blue;" /></p>
    
    <div id="srelhelp" style="display:none;border: 1px solid blue;padding:0 12px;margin:10px 0 0">
    <p>&bull; To add caption to the images, set the &quot;title&quot; attribute of the link pointing to them.</p>
    <p>&bull; If you want to use image sets, you will need to add class=&quot;shutterset&quot; to all links that point to the images for that set. If you want to apply css style to the links, you can add second class, like this: class=&quot;shutterset myClass&quot;, but &quot;shutterset&quot; should be first.</p>
    <p>&bull; Adding class=&quot;shutterset&quot; will also trigger activation (for the third activation option). There is no need to add both &quot;shutter&quot; and &quot;shutterset&quot;.</p>
    <p>&bull; To make more than one set per page, use class=&quot;shutterset_123&quot;. The underscore is required and 123 can be any 1 - 3 digits number (different for each set).</p>
    </div>
    
    <p>You can add Shutter Reloaded to your site in four different ways: </p>
    <?php wp_nonce_field( 'srel-save-options' ); ?>
    <input type="hidden" name="srel_main" value="srel_main" />
    
    
    <div style="margin-bottom:10px;<?php if ( $opt == 'srel_all' ) { ?>color:#999;<?php } ?>">
    <input class="button" type="submit" style="color:blue;cursor:pointer;" name="srel_all" value="Add" /> shutter to all image links.</div>

    <div style="margin-bottom:10px;<?php if ( $opt == 'auto_set' ) { ?>color:#999;<?php } ?>">
    <input class="button" type="submit" style="color:blue;cursor:pointer;" name="auto_set" value="Add" /> shutter to all image links and automatically make image sets for each Post/Page.</div>

    <div style="margin-bottom:10px;<?php if ( $opt == 'srel_class' ) { ?>color:#999;<?php } ?>">
    <input class="button" type="submit" style="color:blue;cursor:pointer;" name="srel_class" value="Add" /> shutter to all image links with class = shutter.</div>

    <div style="margin-bottom:10px;<?php if ( $opt == 'srel_pages' ) { ?>color:#999;<?php } ?>">
    <input class="button" type="submit" style="color:blue;cursor:pointer;" name="srel_pages" value="Add" /> shutter to all image links on specific page(s).</div>

    </fieldset>
    </form>
    
<?php
if ( $opt == 'srel_all' || $opt == 'auto_set' ) { ?>

    <table class="optiontable">
    <tr><td style="background-color:#eee;border:1px solid #ddd;">
    
    <form method="post" name="srel_excluded" id="srel_excluded" action="">
    <fieldset>
<?php
    if( $opt == 'srel_all' ) { ?><p><strong>Shutter is activated for all links pointing to an image.</strong></p><?php }
    else { ?><p><strong>Shutter is activated for all links pointing to an image and will create different image set for each Post/Page.</strong><br />This option is most suitable if you display several Posts on your home page and want to have different image set for each Post. It adds shutter's activation class at runtime and doesn't modify the html.</p><?php }
?>
    <p>Excluded Posts or Pages (by ID): <?php
    if ( is_array($excluded) && !empty($excluded) ) { 
        foreach( $excluded as $excl ) { ?>
          <span style="border: 1px solid #ccc;padding:2px 4px;cursor:pointer;" onclick="document.forms.srel_excluded.srel_exclude.value = '<?php echo $excl; ?>'"><?php echo $excl; ?></span>
<?php   }
    } else { ?>
        [none]
<?php
    } ?>
    </p>

    <input type="text" name="srel_exclude" size="4" maxlength="4" tabindex="4" value="" />
    <input class="button" type="submit" style="color:blue;cursor:pointer;" name="srel_add_excluded" value="Add Excluded ID" 
        onclick="if (form.srel_exclude.value == ''){alert('Please enter the ID of a Page or a Post where you do not want to use shutter.');return false;}" />
    
    <input class="button" type="submit" style="color:blue;cursor:pointer;" name="srel_rem_excluded" value="Remove Excluded ID" 
    onclick="if (form.srel_exclude.value == ''){alert('Please enter the ID that you want removed from the excluded list.');return false;}" />
    
    <div style="color:#888;">Please enter the ID for the post/page you want to exclude. You can see it at the <a href="edit-pages.php">Manage Pages</a> or the <a href="edit.php">Manage Posts</a> page.</div>
    <?php wp_nonce_field( 'srel-save-options' ); ?>
    </fieldset>
    </form>
    </td></tr>
    </table>
<?php
    }

if ( $opt == 'srel_class' ) { ?>

    <table class="optiontable">
    <tr><td style="background-color:#eee;border:1px solid #ddd;">
    <form method="post" name="srel_classnameform" id="srel_classnameform" action="">
    <fieldset>
    
    <p><strong>Shutter is activated for all links pointing to an image, that have class = &quot;<?php echo $srel_options['srel_classname']; ?>&quot;.</strong></p>

    <strong>Advanced:</strong> Change Shutter's activation class. If you already have image links set with a class, you can change Shutter's activation to use that class instead of changing all of the links classes (default is shutter): &nbsp;
    <input type="text" name="srel_classname" size="8" maxlength="12" tabindex="" value="<?php echo $srel_options['srel_classname']; ?>" />&nbsp;
    <input class="button" type="submit" style="color:blue;cursor:pointer;" name="srel_chclassname" value="Change Shutter's Class" />

    <?php wp_nonce_field( 'srel-save-options' ); ?>
    </fieldset>
    </form>
    </td></tr>
    </table>
<?php
    }

if ( $opt == 'srel_pages' ) { ?>

    <table class="optiontable">
    <tr><td style="background-color:#eee;border:1px solid #ddd;">
    <form method="post" name="srel_included" id="srel_included" action="">
    <fieldset>
    <p><strong>Shutter is activated for the following Posts and Pages (by ID): 
<?php
    if ( is_array($included) && !empty($included) ) { 
        foreach( $included as $incl ) { ?>
            <span style="border: 1px solid #ccc;padding:2px 4px;cursor:pointer;" onclick="document.forms.srel_included.srel_include.value = '<?php echo $incl; ?>'"><?php echo $incl; ?></span>
<?php   }
    } else { ?>
        [none]
<?php
    } ?>
    </strong></p>

    <input type="text" name="srel_include" size="4" maxlength="4" tabindex="4" value="" />
    <input class="button" type="submit" style="color:blue;cursor:pointer;" name="srel_add_included" value="Add ID" 
        onclick="if (form.srel_include.value == ''){alert('Please enter the ID of a page or a post where you want to use shutter.');return false;}" />
    <input class="button" type="submit" style="color:blue;cursor:pointer;" name="srel_rem_included" value="Remove ID" 
    onclick="if (form.srel_include.value == ''){alert('Please enter the ID that you want removed from this list.');return false;}" />
    
    <div style="color:#888;">Please enter the ID for the post/page for use with shutter. You can see it at the <a href="edit-pages.php">Manage Pages</a> or the <a href="edit.php">Manage Posts</a> page.</div>
    <?php wp_nonce_field( 'srel-save-options' ); ?>
    </fieldset>
    </form>
    </td></tr>
    </table>
<?php 
    } ?>
    
    <form method="post" name="srel_shoptform" id="srel_shoptform" action="">
    <table class="optiontable" style="padding:5px 12px;">
    <tr><th colspan="2" style="text-align:center;">
    Customisation
    </th></tr>
    
    <tr><td style="width:50%;text-align:right;">
    Shutter color (default 000000): <br />Please enter valid HTML color codes, from 000000 to FFFFFF. 
    </td><td>
    <input type="text" name="shcolor" size="6" maxlength="6" tabindex="" value="<?php echo $srel_options['shcolor']; ?>" />
    <input type="text" name="shcolor2" size="6" disabled style="background-color:#<?php echo $srel_options['shcolor']; ?>;" />
    </td></tr>
    
    <tr><td style="text-align:right;">
    Shutter opacity (default 80): <br />Enter a number between 1 (see-through) and 99 (solid color).
    </td><td>
    <input type="text" name="opacity" size="6" maxlength="3" tabindex="" value="<?php echo $srel_options['opacity']; ?>" />
    <input type="text" name="opacity2" size="6" disabled style="background-color:#<?php echo $srel_options['shcolor']; ?>;opacity:<?php echo ($srel_options['opacity']/100); ?>;filter:alpha(opacity=<?php echo $srel_options['opacity']; ?>);" />
    </td></tr>
    
    <tr><td style="text-align:right;">
    Caption text color (default FFFFFF):
    </td><td>
    <input type="text" name="capcolor" size="6" maxlength="6" tabindex="" value="<?php echo $srel_options['capcolor']; ?>" />
    <input type="text" name="capcolor2" size="6"  disabled style="background-color:#<?php echo $srel_options['capcolor']; ?>;" />
    </td></tr>
    
    <tr><td style="text-align:right;">
    Arrows color (default 999999):
    </td><td>
    <input type="text" name="btncolor" size="6" maxlength="6" tabindex="" value="<?php echo $srel_options['btncolor']; ?>" />
    <input type="text" name="btncolor2" size="6" disabled style="background-color:#<?php echo $srel_options['btncolor']; ?>;" />
    </td></tr>
    
    <tr><td style="text-align:right;">
    &quot;Loading&quot; sign color (default AE0A0A):
    </td><td>
    <input type="text" name="waitcolor" size="6" maxlength="6" tabindex="" value="<?php echo $srel_options['waitcolor']; ?>" />
    <input type="text" name="waitcolor2" size="6" disabled style="background-color:#<?php echo $srel_options['waitcolor']; ?>;" />
    </td></tr>
    
    <tr><td colspan="2">
    <p>&lowast; To restore the defaults, delete the current value(s) and submit the form.</p>
    <p class="submit"><input type="submit" name="srel_shopt" value="Save Options" /></p>
    
    <?php wp_nonce_field( 'srel-save-options' ); ?>
    </td></tr>
    </table>
    </form>
    </div>
<?php
} // end srel_optpage

function srel_addmenu() {
    if ( function_exists('add_options_page') ) {
	   add_options_page('Shutter Reloaded', 'Shutter Reloaded', 10,  __FILE__, 'srel_optpage');
    }
}

add_action('admin_menu', 'srel_addmenu');

if ( class_exists("sh_reloadedClass") ) {
	$sh_reloaded = new sh_reloadedClass();
    
    add_action('get_header', array(&$sh_reloaded, 'srel_head') );
    add_action('activate_shutter-reloaded/shutter-reloaded.php', array(&$sh_reloaded, 'srel_activ') );
    add_action('deactivate_shutter-reloaded/shutter-reloaded.php', array(&$sh_reloaded, 'srel_deact') );
}
?>
