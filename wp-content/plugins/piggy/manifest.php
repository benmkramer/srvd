<?php 
header('Content-Type: text/cache-manifest');
echo "CACHE MANIFEST\n";
global $piggy;

global $cache;
$cache = array();

$hash = piggy_get_last_purchase_hash();

function piggy_update_dir( $dir_name ) {
	global $cache;
	
	$dir = opendir( $dir_name );
	
	while ( ( $file = readdir( $dir ) ) !== false ) {
		if ( $file == '.DS_Store' || $file == '.svn' || $file == '.' || $file == '..' ) {
			continue;
		}
		
		$path = $dir_name . '/' . $file;
		
		if ( 
			strpos( $path, '.js' ) !== false || 
			strpos( $path, '.css' ) !== false || 
			strpos( $path, '.jpg' ) !== false || 
			strpos( $path, '.png' ) !== false || 
			strpos( $path, '.gif' ) !== false 
		) {
			$cache[] = $path;	
		
		}		
		
		if ( is_dir( $path ) && strpos( $path, '/admin' ) === false ) {
			piggy_update_dir( $path );	
		}

	}
	
	closedir( $dir );
}

echo "\nNETWORK:\n";
//echo $piggy->get_absolute_piggy_url() . "\n";
//echo $piggy->get_absolute_piggy_url() . "#web-app-notice\n";
echo $piggy->get_absolute_piggy_ajax_url() . "\n";
echo get_bloginfo( 'wpurl' ) . "/?piggy_purchase_hash=1 \n";
echo get_bloginfo( 'wpurl' ) . "/?piggy_dynamic=1&ver=" . md5( PIGGY_VERSION ) . "\n";
$hash = md5( $hash . $piggy->get_absolute_piggy_url() . $piggy->get_absolute_piggy_ajax_url() . PIGGY_VERSION . 'piggy_dynamic' );

//if ( piggy_is_user_logged_in() ) {
	echo "\nCACHE:\n";
//}

piggy_update_dir( PIGGY_DIR );

foreach( $cache as $cache_file ) {
	echo str_replace( PIGGY_DIR, PIGGY_URL, $cache_file ). "\n";	
	$hash = md5( $hash . md5_file( $cache_file ) );
}

/*
$hashes = "";
$lastFileWasDynamic = FALSE;

$dir = new RecursiveDirectoryIterator(".");
foreach(new RecursiveIteratorIterator($dir) as $file) {
	if ($file->IsFile() && $file != "./manifest.php" &&
		substr($file->getFilename(), 0, 1) != ".") {
		if(preg_match('/.php$/', $file)) {
			if(!$lastFileWasDynamic) {
				echo "\n\nNETWORK:\n";
			}
			$lastFileWasDynamic = TRUE;
		} else {
			if($lastFileWasDynamic) {
				echo "\n\nCACHE:\n";
				$lastFileWasDynamic = FALSE;
			}
		}
		echo $file . "\n";
		$hashes .= md5_file($file);
	}
}
*/

echo "\n# HASH: " . $hash . "\n";
