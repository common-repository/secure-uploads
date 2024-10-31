<?php
/*
Plugin Name: Secure Uploads
Plugin Uri:
Description:This is a simple plugin to protect your wp-content/uploads folder from being browsed and people stealing your content.
Version: 1.1.4
Author: Joel Kuiper
Author URI:
License: GPL2

Copyright 2014-2016  Joel Kuiper  (email : support@qualitywebhostanddesign.com)

This program is free software; you can redistribute it and/or modify
it under the terms of the GNU General Public License, version 3, as 
published by the Free Software Foundation.

This program is free software: you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation, version 3 of the License.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program.  If not, see <http://www.gnu.org/licenses/>.


*/

$PLUGIN_NAME = 'Secure Uploads';
$PLUGIN_VERSION = '1.1.4';
$PLUGIN_PATH = WP_PLUGIN_URL.'/secure-uploads';

$empty_file = realpath( dirname( __FILE__ ) ) . '/index.php';
$start_dir = wp_upload_dir(); 

add_action( 'admin_menu', 'addPluginToSubmenu');

/* =====================================================================
 * =====================================================================
 * ==================== PLUGIN FUNCTION ===========================
 */

function addPluginToSubmenu()
{
    add_submenu_page('options-general.php', 'Secure Uploads', 'Secure Uploads', 10, __FILE__, 'initPluginMenu');
}

function initPluginMenu()
{
	global $start_dir;
	
	if( $_POST['secure'] == 'Y' ) {

		secure_folder_uploads();
?>
    <div class="updated">
        <p><strong><?php _e('You have secured your uploads! ', 'rl_process_done' ); ?></strong></p>
    </div>
    <?php

	}
	echo '<div class="wrap">';
	echo "<h2>" . __( 'Secure Uploads Options', 'rl_secure_option' ) . "</h2>";
	?>
        <form name="form1" method="post" action="<?php echo str_replace( '%7E', '~', $_SERVER['REQUEST_URI']); ?>">
            <input type="hidden" name="secure" value="Y" />

            <p>
                <label>
                    <?php 
		echo "
		This is a simple plugin to protect your <strong>wp-content/uploads folder</strong> from being browsed and people stealing your content.
		<br/>
		This plugin works by putting a empty <strong>index.php</strong> file in every sub-directory of your wp-content/uploads
		<br/>
		You need to run this this manually every time you want it to put the .php file in the wp-contents/uploads subdirectories (it's optimal that you do it once a month)
		<br/><br/>
		<a href='".$start_dir['baseurl']."'>Click here to check if your wp-content/uploads is unsecured</a>
		<br/>
		If you can browse this folder using your browser you need to secure your uploads!
		";
		?>
                </label>
            </p>

            <p class="submit">
                <input class="button-primary" type="submit" name="Submit" value="<?php _e('Secure your uploads', 'rl_secure_folder' ) ?>" />
            </p>

        </form>

        <p>If you like this plugin Please come give it a good rating on wordpress.org, or tell people how much you like it on your site!</p>

        </div>
        <?php
}//eof func initPlugin


function secure_folder_uploads(){

	global $start_dir;
	
	search_and_copy_to($start_dir['basedir']);
	
}//eof secure_folder_uploads
function search_and_copy_to($dir){
	
	global $empty_file;
		
	// copy index.php to root dir
	copy($empty_file, $dir . '/index.php');		

	//cek for loop recursive
	if ($dh = opendir($dir)) { 
		
		// loop for dir
		while (($file = readdir($dh)) !== false) { 
			
			// Open a known directory, and proceed to read its contents
			if ( is_dir($dir . '/' . $file) && $file!='.' && $file!='..' ) {
				search_and_copy_to( $dir . '/' . $file );
			}
			
		}
		closedir($dh);
		
	}

}//eof search_and_copy_to
