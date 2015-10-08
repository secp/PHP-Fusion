<?php
/*-------------------------------------------------------+
| PHP-Fusion Content Management System
| Copyright (C) PHP-Fusion Inc
| https://www.php-fusion.co.uk/
+--------------------------------------------------------+
| Filename: profile.php
| Author: Frederick MC Chan (Hien)
+--------------------------------------------------------+
| This program is released as free software under the
| Affero GPL license. You can redistribute it and/or
| modify it under the terms of this license which you
| can read by viewing the included agpl.txt or online
| at www.gnu.org/licenses/agpl.html. Removal of this
| copyright header is strictly prohibited without
| written permission from the original author(s).
+--------------------------------------------------------*/
if (!defined("IN_FUSION")) { die("Access Denied"); }
/**
 * Profile edit form
 */
if (!function_exists('render_userform')) {
	add_to_head("<link href='".THEMES."templates/global/css/profile.css' rel='stylesheet'/>\n");
	function render_userform($info) {
		// page navigation
		$open = ""; $close = "";
		if (isset($info['section']) && count($info['section'])>1) {
			foreach ($info['section'] as $page_section) {
				$tab_title['title'][$page_section['id']] = $page_section['name'];
				$tab_title['id'][$page_section['id']] = $page_section['id'];
				$tab_title['icon'][$page_section['id']] = '';
			}
			$open = opentab($tab_title, $_GET['section'], 'user-profile-form', 1);
			$close = closetab();
		}
		echo $open;
		if (empty($info['user_name']) && empty($info['user_field'])) {
			global $locale;
			echo "<div class='well text-center'>\n";
			echo $locale['uf_108'];
			echo "</div>\n";
		} else {
			echo "<!--editprofile_pre_idx-->";
			echo "<div id='register_form' class='row m-t-20'>\n";
			echo "<div class='col-xs-12 col-sm-12'>\n";
			if (!empty($info['openform'])) echo $info['openform'];
			if (!empty($info['user_name'])) echo $info['user_name'];
			if (!empty($info['user_email'])) echo $info['user_email'];
			if (!empty($info['user_hide_email']))echo $info['user_hide_email'];
			if (!empty($info['user_avatar'])) echo $info['user_avatar'];
			if (!empty($info['user_password'])) echo $info['user_password'];
			if (!empty($info['user_admin_password']) && iADMIN) echo $info['user_admin_password'];
			if (!empty($info['user_field'])) echo $info['user_field'];
			if (!empty($info['validate'])) echo $info['validate'];
			if (!empty($info['terms'])) echo $info['terms'];
			if (!empty($info['button'])) echo $info['button'];
			if (!empty($info['closeform'])) echo $info['closeform'];
			echo "</div>\n</div>\n";
			echo "<!--editprofile_sub_idx-->";
		}
		echo $close;
	}
}

/**
 * Profile display
 */
if (!function_exists('render_userprofile')) {
	function render_userprofile($info) {
		// Basic User Information
		$basic_info = isset($info['core_field']) && is_array($info['core_field']) ? $info['core_field'] : array();
		//User Fields Module Information
		$field_info = isset($info['user_field']) && is_array($info['user_field']) ? $info['user_field'] : array();
		$user_info = '';
		$user_avatar = '';
		$user_name = '';
		$user_level = '';
		foreach ($basic_info as $field_id => $data) {
			if ($field_id == 'profile_user_avatar') {
				$avatar['user_avatar'] = $data['value'];
				$avatar['user_status'] = $data['status'];
				$user_avatar = "<img src='".$data['value']."' style='width:50px;' alt='".$info['core_field']['profile_user_name']['value']."'/>\n";
			} elseif ($field_id == 'profile_user_name') {
				$user_name = "<h4>".$data['value']."</h4>\n";
			} elseif ($field_id == 'profile_user_level') {
				$user_info .= "
				<div id='".$field_id."' class='m-b-5 row'>
					<span class='col-xs-12 col-sm-3'>".$data['title']."</span>
					<div class='col-xs-12 col-sm-9 profile_text overflow-hide'>".$data['value']."</div>
				</div>\n";
			} else {
				$user_info .= $data['value'] ? "
					<div id='".$field_id."' class='m-b-5 row'>
					<span class='col-xs-12 col-sm-3'>".$data['title']."</span>
					<div class='col-xs-12 col-sm-9 profile_text'>".$data['value']."</div>
					</div>\n" : '';
			}
		}

		if (!empty($field_info)) {
			$user_field = '';
			foreach ($field_info as $field_cat_id => $category_data) {
				if (!empty($category_data['fields'])) {
					$user_field .= $category_data['title'];
					$user_field .= "<div class='list-group-item'>";
					if (isset($category_data['fields'])) {
						foreach ($category_data['fields'] as $field_id => $field_data) {
							$user_field .= "<div id='".$field_id."' class='m-b-5 row'>
						<span class='col-xs-12 col-sm-3'>".$field_data['title']."</span>
						<div class='col-xs-12 col-sm-9 profile_text'>".$field_data['value']."</div>
						</div>\n";
						}
					}
					$user_field .= "</div>\n";
				}
			}
		} else {
			global $locale;
			$user_field = "<div class='m-t-20 text-center well'>".$locale['uf_108']."</div>\n";
		}

		// buttons
		$user_buttons = '';
		if (!empty($info['buttons'])) {
			$user_buttons = "<div class='btn-group m-t-10 m-b-10 col-sm-offset-3'>";
			foreach($info['buttons'] as $buttons) {
				$user_buttons .= "<a class='btn btn-sm button btn-default' href='".$buttons['link']."'>".$buttons['name']."</a>";
			}
			$user_buttons .= "</div>\n";
		}
		?>
		<!--userprofile_pre_idx-->
		<section id='user-profile' class='row'>
			<?php
			if (!empty($info['section'])) {
				foreach ($info['section'] as $page_section) {
					$tab_title['title'][$page_section['id']] = $page_section['name'];
					$tab_title['id'][$page_section['id']] = $page_section['id'];
					$tab_title['icon'][$page_section['id']] = "";
				}
				echo opentab($tab_title, $_GET['section'], "profile_tab", TRUE);
			}
			?>
			<div class='col-xs-12 col-sm-12'>
				<div class='clearfix m-t-10'>
					<div class='pull-left m-r-20'><?php echo $user_avatar ?></div>
					<div class='overflow-hide'>
						<?php
						echo $user_name;
						echo $user_level;
						echo $user_info;
						echo $user_buttons;
						echo $user_field;
						if (!empty($info['admin'])) echo $info['admin'];
						?>
					</div>
				</div>
			</div>
		</section>
		<!--userprofile_sub_idx-->
	<?php
	}
}
