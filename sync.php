<?php

/**
 * Plugin Name: Discourse MemberPress Sync
 * Description: Sync MemberPress Memberships with discourse groups
 * Version: 1.0.0
 * Author: fzngagan@gmail.com
 * Author URI: https://github.com/fzngagan
 * Plugin URI: https://github.com/paviliondev/wp-discourse-mepr-sync
 * GitHub Plugin URI: https://github.com/paviliondev/wp-discourse-mepr-sync
 */


use WPDiscourse\Utilities\Utilities as Utilities;
define('PV_MEMBERPRESS_PRODUCT_IDS', array(12, 47));
define('PV_DISCOURSE_ENROLLED_GROUP', 'Students');
define('PV_DISCOURSE_UNENROLLED_GROUP', 'KaizenAlumni');
define('PV_MEPR_ACTIVE_STATUSES', array('active', 'cancelled'));

add_action("mepr_subscription_transition_status", function ($old, $new, $subscription) {
	if(!in_array($subscription->product_id, PV_MEMBERPRESS_PRODUCT_IDS)) return;

	if(in_array($new, PV_MEPR_ACTIVE_STATUSES)) {
		pv_enroll_to_group($subscription);
	} else {
		pv_remove_from_group($subscription);
	}
}, 10, 3);

function pv_enroll_to_group($subscription) {
	Utilities::add_user_to_discourse_group($subscription->user_id, PV_DISCOURSE_ENROLLED_GROUP);
	Utilities::remove_user_from_discourse_group($subscription->user_id, PV_DISCOURSE_UNENROLLED_GROUP);
}

function pv_remove_from_group($subscription) {
	Utilities::add_user_to_discourse_group($subscription->user_id, PV_DISCOURSE_UNENROLLED_GROUP);
	Utilities::remove_user_from_discourse_group($subscription->user_id, PV_DISCOURSE_ENROLLED_GROUP);
}
