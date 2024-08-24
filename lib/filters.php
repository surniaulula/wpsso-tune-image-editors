<?php
/*
 * License: GPLv3
 * License URI: https://www.gnu.org/licenses/gpl.txt
 * Copyright 2018-2024 Jean-Sebastien Morisset (https://wpsso.com/)
 */

if ( ! defined( 'ABSPATH' ) ) {

	die( 'These aren\'t the droids you\'re looking for.' );
}

if ( ! class_exists( 'WpssoTieFilters' ) ) {

	class WpssoTieFilters {

		private $p;	// Wpsso class object.
		private $a;	// WpssoTie class object.
		private $msgs;	// WpssoTieFiltersMessages class object.
		private $opts;	// WpssoTieFiltersOptions class object.
		private $upg;	// WpssoTieFiltersUpgrade class object.
		private $wp;	// WpssoTieFiltersWp class object.

		/*
		 * Instantiated by WpssoTie->init_objects().
		 */
		public function __construct( &$plugin, &$addon ) {

			static $do_once = null;

			if ( $do_once ) return;	// Stop here.

			$do_once = true;

			$this->p =& $plugin;
			$this->a =& $addon;

			require_once WPSSOTIE_PLUGINDIR . 'lib/filters-options.php';

			$this->opts = new WpssoTieFiltersOptions( $plugin, $addon );

			if ( is_admin() ) {

				require_once WPSSOTIE_PLUGINDIR . 'lib/filters-messages.php';

				$this->msgs = new WpssoTieFiltersMessages( $plugin, $addon );
			}

			require_once WPSSOTIE_PLUGINDIR . 'lib/filters-upgrade.php';

			$this->upg = new WpssoTieFiltersUpgrade( $plugin, $addon );

			require_once WPSSOTIE_PLUGINDIR . 'lib/filters-wp.php';

			$this->opts = new WpssoTieFiltersWp( $plugin, $addon );
		}
	}
}
