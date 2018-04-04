<?php
/**
 * @package dropbox_salesforce_form
 * @version 0.1
 */
/*
Plugin Name: Dropbox_Salesforce_form
Plugin URI: None
Author: Kumagai Yoshinao
Description: send attachment to dropbox and form data to salesforce
Version: 0.1
*/

include 'include/application_form.php';
include 'include/setting_form.php';

// add short code [example] that can be used to embed in post 
function just_for_start(){
	$information = "this is custom information";
	// if you use echo instead of return message will print out in the begging of the post message
	return $information;
}
// add_shortcode('name of short coed', 'function to call')
add_shortcode('example', 'just_for_start');


function dbsf_example(){
	return true;
}

function dbsf_admin_menu_option(){
	add_menu_page('Dropbox Salesforce Form', '応募者フォーム', 'manage_options', 'dbsf-admin-menu', 'dbsf_setting_page','',200);
}

add_action('admin_menu', 'dbsf_admin_menu_option');


function get_dbsf_options(){
	$option = get_option('dbsf_options');
	return $option;
}

function update_dbsf_options($options){
	update_option('dbsf_options', $options);
}

function default_dbsf_options(){
	$dbsf_options = array("db_id" => "",
					  "db_secret" => "",
					  "db_url" => "",
					  "sfdc_id" => "",
					  "sfdc_secret" => "",
					  "sfdc_user" => "",
					  "sfdc_pw" => "",
					  "sfdc_url" => "");
	return $dbsf_options;
}


function default_dbsf_form(){
	$dbsf_form = "";
	return $dbsf_form;
}


function save_dbsf_options_from_post($post){
	
	$dbsf_options = default_dbsf_options();

	if(array_key_exists('_dbsfconf', $post)){
		foreach ($_POST as $key => $value) {
			$dbsf_options[$key] = $value;
		}
		try{
			update_option('dbsf_options', $dbsf_options);
			update_option('dbsf_form', $_POST['dbsf_form']);
		} catch (Exception $e) {
			var_dump($e->getMessage());
		}
	}
}


function dbsf_setting_page(){
	
	if($_POST){
		save_dbsf_options_from_post($_POST);
	}

	try{
		$dbsf_options = get_option('dbsf_options');
		$dbsf_form = get_option('dbsf_form');
	} catch (Exception $e){
		var_dump($e->getMessage());
		$dbsf_options = default_dbsf_options();
		$dbsf_form = default_dbsf_form();
	}
	dbfs_admin_setting_form($dbsf_options, $dbsf_form);
}


// application form tag
function create_dbsf_form(){
	
	if(isset($_POST)){
		var_dump($_POST);
	}

	dbfs_application_form();
}
add_shortcode('dbsf_application_form', 'create_dbsf_form');

?>