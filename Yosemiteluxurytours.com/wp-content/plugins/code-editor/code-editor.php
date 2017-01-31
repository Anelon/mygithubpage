<?php
/*
Plugin Name: Wordpress Code Editor
Plugin URI: http://www.naden.de/blog/wordpress-code-editor
Description: Brings Syntax-Highlighting to the Theme- and Plugin-Editor of Wordpress. Supports php, css, html and js. Visit the <a href="http://www.naden.de/blog/wordpress-code-editor" target="_blank">Plugin Homepage</a> for more details.
Author: Naden Badalgogtapeh
Version: 1.2
Author URI: http://www.naden.de/blog

/*
 * History:
 *
 * v1.2 21.01.2009  just refactoring
 * v1.1 30.05.2007  small fix now unsing wpurl
 * v1.0 xx.xx.2007  initial release
 *
 */

if( !class_exists( 'CodeEditor' ) ):

class CodeEditor
{
  var $version;
  var $id;
  var $name;
  var $url;

  function CodeEditor()
  {
    $this->version  = '1.2';
    $this->id       = 'codeeditor';
    $this->name     = 'Code Editor v' . $this->version;
    $this->url      = 'http://www.naden.de/blog/wordpress-code-editor';
      
    if( is_admin() )
    { 
      $this->LoadOptions();
          
      if( strpos( strtolower( $_SERVER[ 'REQUEST_URI' ] ), 'plugin-editor.php' ) !== false || strpos( strtolower( $_SERVER[ 'REQUEST_URI' ] ), 'theme-editor.php' ) !== false )
      {
        add_filter( 'admin_footer', array( &$this, 'AddAdminFooter' ) );
      }
    }
    else
    {
		  add_action( 'wp_head', array( &$this, 'BlogHeader' ) );
    }
  }
  
  function LoadOptions()
  {
    $this->options = get_option( $this->id );

    if( !$this->options )
    {
      $this->options = array(
        'installed' => time()
			);

      add_option( $this->id, $this->options, $this->name, 'yes' );
      
      printf( '<img src="http://www.naden.de/gateway/?q=%s" width="1" height="1" />', urlencode( sprintf( 'action=install&plugin=%s&version=%s&platform=%s&url=%s', $this->id, $this->version, 'wordpress', get_bloginfo( 'wpurl' ) ) ) );

    }
  }
  
  function BlogHeader()
  {
    printf( '<meta name="%s" content="%s/%s" />' . "\n", $this->id, $this->id, $this->version );
  }
  
	function AddAdminFooter()
	{
    $url = get_bloginfo( 'wpurl' );

echo <<<DATA
<!-- Code Editor Plugin v{$this->version} {$this->url} -->
<script type="text/javascript">
/* <![CDATA[ */
( function() {
/// get form and textarea
var ce_form = document.getElementById( 'template' );
var ce_textarea = document.getElementById( 'newcontent' );
if( ce_form && ce_textarea ) {
  /// hooke ce_form.onsubmit
	ce_form.onsubmit = function() {
	  newcontent.toggleEditor();
    ce_form.submit();
  }
	/// detect filetype
	var ce_file = document.getElementsByName( 'file' );
	var ce_file_type = 'php';
	if( ce_file && ce_file.length > 0 ) {
		if( ce_file[ 0 ].value.match( '\.css' ) ) {
			ce_file_type = 'css';
		}
		else if( ce_file[ 0 ].value.match( '\.js' ) ) {
			ce_file_type = 'js';
		}
		else if( ce_file[ 0 ].value.match( '\.html' ) ) {
			ce_file_type = 'html';
		}
	}
	/// set classname for codepress
	ce_textarea.className = 'codepress ' + ce_file_type;
	/// load editor
	document.write( '<' + 'script type="text/javascript" src="{$url}/wp-content/plugins/code-editor/codepress/codepress.js"' + '>' + '<' + '/script' + '>' );
}
} )();
/* ]]> */
</script>
<!-- // Code Editor Plugin -->
DATA;
	}
}

endif;

add_action( 'plugins_loaded', create_function( '$CodeEditor_nx228j', 'global $CodeEditor; $CodeEditor = new CodeEditor();' ) ); 

?>