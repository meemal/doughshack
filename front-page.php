<?php
/**
 * Front page template.
 *
 * WordPress uses this file for the static front page before page.php.
 * Ensures the full homepage layout (calendar, map, find-us) loads even when
 * the page is set to the default template in the admin.
 *
 * @package The Dough Shack
 */

require get_template_directory() . '/page-home.php';
