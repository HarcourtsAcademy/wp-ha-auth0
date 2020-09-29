<?php
/**
 * @package           PluginPackage
 * @author            Tim Butler
 * @copyright         2020 Harcourts International Ltd
 * @license           GPL-2.0-or-later
 *
 * @wordpress-plugin
 * Plugin Name:       Harcourts Academy Auth0 Extension
 * Plugin URI:        https://github.com/HarcourtsAcademy/wp-ha-auth0
 * Description:       Matches user accounts by H1 usernames
 * Version:           1.0.0
 * Requires at least: 5.2
 * Requires PHP:      7.2
 * Author:            Tim Butler
 * Author URI:        https://github.com/timb28
 * Text Domain:       ha-auth0
 * License:           GPL v2 or later
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 */
/**
 * Filter the WordPress user found during login.
 *
 * @see WP_Auth0_LoginManager::login_user()
 *
 * @param WP_User|null $user     - found WordPress user, null if no user was found.
 * @param stdClass     $userinfo - user information from Auth0.
 *
 * @return WP_User|null
 */
function ha_auth0_hook_auth0_get_wp_user( ?WP_User $user, stdClass $a0userinfo ) {
    $found_wpuser = get_user_by( 'login', $a0userinfo->username );
    if (! empty($found_wpuser) ) {
        if (strpos($a0userinfo->email, '@temp.harcourts.net') !== false) {
            /**
             * Auth0 email address is a temporary email address so
             * temporarily set the WP user's email to match the Auth0 email
             * to avoid their correct email address being overwritten with
             * a temporary email used in Auth0.
             */
            $found_wpuser->data->user_email = $a0userinfo->email;
        } else {
            /**
             * Auth0 email address is not a temporary email address so
             * update the WP user's email to match the Auth0 email.
             */

        }
    }
    $user = $found_wpuser instanceof WP_User ? $found_wpuser : null;
    return $user;
}
add_filter( 'auth0_get_wp_user', 'ha_auth0_hook_auth0_get_wp_user', 1, 2 );