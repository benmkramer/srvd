<?php
/*
	Plugin Name: WPEC Piggy Pro
	Plugin URI: http://bravenewcode.com/piggy
	Description: Piggy Pro adds a web-app to your website for monitoring WP E-Commerce, Cart66 and Shopp sales and statistics in real-time.
	Author: Dale Mugford & Duane Storey (BraveNewCode)
	Version: 1.3.1
	Author URI: http://www.bravenewcode.com
	Text Domain: piggy
	Domain Path: /lang
	#
	# 'Piggy' is an unregistered trademarks of BraveNewCode Inc., 
	# and cannot be re-used in conjuction with the GPL v2 usage of this software 
	# under the license terms of the GPL v2 without permission.
	# 
	# This program is free software; you can redistribute it and/or
	# modify it under the terms of the GNU General Public License
	# as published by the Free Software Foundation; either version 2
	# of the License, or (at your option) any later version.
	# 
	# This program is distributed in the hope that it will be useful,
	# but WITHOUT ANY WARRANTY; without even the implied warranty of
	# MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
	# GNU General Public License for more details.
	# 
	# You should have received a copy of the GNU General Public License
	# along with this program; if not, write to the Free Software
	# Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.
*/

global $piggy;

// Should not have spaces in it, same as above
define( 'PIGGY_VERSION', '1.3.1' );
define( 'PIGGY_ROOT_PATH', dirname( __FILE__ ) );

// Configuration
require_once( 'include/config.php' );

// Settings
require_once( 'include/settings.php' );

// Main Piggy Class
require_once( 'include/classes/piggy.php' );

// Global
require_once( 'include/globals.php' );

// Helpers
require_once( 'include/array-iterator.php' );

function piggy_create_object() {
	global $piggy;
	
	$piggy = new Piggy;
	$piggy->initialize();			
}

add_action( 'plugins_loaded', 'piggy_create_object' );
