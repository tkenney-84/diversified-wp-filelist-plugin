<?php

/**
Plugin Name:                DTC Documents
Description:                Allows you to easily add and dynamically list documents on your wordpress site. You can list documents with filters such as custom categories, id title, description, creation date, and much more. You can also place shortcode for a button that links to the most recently uploaded document, which can also be filtered by custom category! In additon to the listing and most recent button, you can place shortcode in any menu that links to the most recent document (optionally, of a specific category) with just text!
Author: 					Diversified Technology Corp., WPYog, and Gagan Deep Singh
Author URI: 				https://diversifiedtechnology.com/
Version:                    1.9.7
License:					GPLv2 or later
License URI:				http://www.gnu.org/licenses/gpl-2.0.html

Assimilated-Plugin: 		Shortcode in Menus
Assimilated-Plugin Name:    Shortcode in Menus
Assimilated-Description:    Allows you to add shortcodes in WordPress Navigation Menus
Assimilated-Plugin URI:     http://wordpress.org/plugins/shortcode-in-menus/
Assimilated-Version:        3.5.1
Assimilated-Author:         Gagan Deep Singh
Assimilated-Author URI:     https://gagan0123.com
Assimilated-Text Domain:	shortcode-in-menus
Assimilated-Domain Path:    /languages
Assimilated-@package		Shortcode_In_Menus

Assimilated-Plugin: 		WPYog Documents
Assimilated-Plugin Name:    WPYog Documents
Assimilated-Description:    WPYog Documents.
Assimilated-Author:         WPYog
Assimilated-Author URI:     http://wpyog.com/
Assimilated-Version:        1.0
Assimilated-License:        GPLv2 or later
Assimilated-License URI:	http://www.gnu.org/licenses/gpl-2.0.html
*/

#####################################
###### SHORTCODE IN MENU INIT #######
#####################################

// If this file is called directly, abort.
if (!defined('ABSPATH')) {
	exit;
}

if (!defined('DTC_DOC_PLUGIN_DIR')) {
	/**
	 * Path to the plugin directory.
	 *
	 * @since 3.2
	 */
	define('DTC_DOC_PLUGIN_DIR', trailingslashit(plugin_dir_path(__FILE__)));
}
if (!defined('SHORTCODE_IN_MENUS_URL')) {
	/**
	 * URL to the plugin directory.
	 *
	 * @since 3.2
	 */
	define('SHORTCODE_IN_MENUS_URL', trailingslashit(plugins_url('', __FILE__)));
}
if (!defined('SHORTCODE_IN_MENUS_RES')) {
	/**
	 * Resource version for busting cache.
	 *
	 * @since 3.5
	 */
	define('SHORTCODE_IN_MENUS_RES', 1.0);
}
/**
 * The core plugin class
 */
require_once DTC_DOC_PLUGIN_DIR . '.shortcode_in_menus/includes/class-shortcode-in-menus.php';

/**
 * Load the admin class if its the admin dashboard
 */
if (is_admin()) {
	require_once DTC_DOC_PLUGIN_DIR . '.shortcode_in_menus/admin/class-shortcode-in-menus-admin.php';
	Shortcode_In_Menus_Admin::get_instance();
} else {
	Shortcode_In_Menus::get_instance();
}

#####################################
######### DTC DOCUMENTS INIT ########
#####################################

#########################################################
############# FONT & FILTER INITIALIZATION ##############
#########################################################

// Specifies fonts and filters.
add_action('wp_head', 'dtc_front_scripts');
function dtc_front_scripts()
{
	wp_register_style('dtc_font_front_css', plugin_dir_url(__FILE__) . 'style/css/font-awesome.min.css', false, '1.0.0');
	wp_enqueue_style('dtc_font_front_css');
	wp_register_style('dtc_document_front_css', plugin_dir_url(__FILE__) . 'style/css/dtc_document.min.css', false, '1.0.0');
	wp_enqueue_style('dtc_document_front_css');
}
add_filter('widget_text', 'do_shortcode');

#################################################
####### INITIALIZATION FUNCTIONS ################
#################################################

// Creates plugin documents and categories tables and adds the default 'Uncategorized'
// category.
add_action('init', 'dtc_doc_initialize', 1);
function dtc_doc_initialize()
{
	global $wpdb, $uncategorizedID, $table;
	require_once(ABSPATH . 'wp-admin/includes/upgrade.php');

	// Specifies the creation of the categories table.
	$table_categories = $wpdb->prefix . "dtc_doc_categories";
	$charset_collate = $wpdb->get_charset_collate();
	$sqlCategory = "CREATE TABLE IF NOT EXISTS $table_categories (
		`id` BIGINT(20) NOT NULL AUTO_INCREMENT ,
		`cat_name` TEXT NOT NULL ,
		`cat_desc` MEDIUMTEXT NULL ,
		`status` TINYINT(2) NULL DEFAULT '1' ,
		`created` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ,
		PRIMARY KEY (`id`)
	) $charset_collate;";

	// Specifies the creation of the documents table.
	$table_documents = $wpdb->prefix . "dtc_doc_documents";
	$charset_collate = $wpdb->get_charset_collate();
	$sqlDocuments = "CREATE TABLE IF NOT EXISTS $table_documents (
		`id` BIGINT(20) NOT NULL AUTO_INCREMENT ,
		`category_id` BIGINT(20) NULL,
		`title` TEXT NOT NULL ,
		`description` TEXT NULL ,
		`document_type` VARCHAR(100) NULL,
		`document_link` VARCHAR(250) NULL,
		`media_file` VARCHAR(255) NULL,
		`status` TINYINT(4) NULL DEFAULT '1',
		`created` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ,
		PRIMARY KEY (`id`)
	) $charset_collate;";

	// Assigns the categories and documents table names to a variable as an array of values for later reference.
	$table = ['categories' => $table_categories, 'documents' => $table_documents];

	// Adds the 'Uncategorized' category to the categories table and stores its ID for
	// later reference upon document deletion.
	$uncategorizedID = checkAddUncategorized();

	// Run the table creation queries and define the plugins default directory.
	dbDelta($sqlCategory);
	dbDelta($sqlDocuments);
	define('DTC_DOC_PLUGIN_DIR', plugin_dir_path(__FILE__));

}

// Generates the menu seen for the plugin in the administrator menu of the website.
add_action('admin_menu', 'dtc_doc_plugin_menu');
function dtc_doc_plugin_menu()
{
	add_menu_page('DTC Document', 'DTC Documents', 'upload_files', 'dtc_document', 'dtc_document', 'dashicons-dtc-icon', '120');

	add_submenu_page('dtc_document', 'DTC Documents', 'All Documents', 'upload_files', 'dtc_display_all_documents', 'dtc_display_all_documents');

	add_submenu_page('dtc_document', 'Add New Document', 'Add New Document', 'upload_files', 'dtc_add_new_document', 'dtc_add_new_document');

	add_submenu_page('dtc_document', 'DTC Categories', 'All Categories', 'manage_options', 'dtc_all_categories', 'dtc_all_categories');

	add_submenu_page('dtc_document', 'Add New Category', 'Add New Category', 'manage_options', 'dtc_add_new_category', 'dtc_add_new_category');

	// These menus are invisible accessors to function within this file.
	add_submenu_page(null, 'Delete Item', 'Delete Topic', 'upload_files', 'dtc_delete_item', 'dtc_delete_item');
	add_submenu_page(null, 'Document Status', 'Document Status', 'upload_files', 'dtc_change_item_status', 'dtc_change_item_status');
	add_submenu_page(null, 'Mass Document Status', 'Mass Document Status', 'upload_files', 'dtc_mass_change_item_status', 'dtc_mass_change_item_status');
	add_action('admin_enqueue_scripts', 'dtc_document_admin_script');
}

// Includes all style related files.
function dtc_document_admin_script()
{
	global $post;
	wp_register_style('dtc_document_admin_css', plugin_dir_url(__FILE__) . 'style/css/dtc-document.css', false, '1.0.0');
	wp_enqueue_style('dtc_document_admin_css');

	wp_register_script('dtc_document_admin_validate', plugin_dir_url(__FILE__) . 'js/jquery.validate.min.js', false, '1.0.0');
	wp_enqueue_script('dtc_document_admin_validate');

	wp_register_script('dtc_document_admin_document_js', plugin_dir_url(__FILE__) . 'js/document-js.js', false, '1.0.0');
	wp_enqueue_script('dtc_document_admin_document_js');

	wp_enqueue_script('media-upload');

	if (is_object($post)) {
		wp_enqueue_media(array('post' => $post->ID));
	} else {
		wp_enqueue_media();
	}
}

#################################################
############# DOCUMENT FUNCTIONS ################
#################################################

// This is the primary menu options function that just displays the index page through
// inclusion.
function dtc_document()
{
	$includeFile = DTC_DOC_PLUGIN_DIR . 'views/index.php';
	include($includeFile);
}

// This function queries all documents and passes them to an included file for display.
function dtc_display_all_documents()
{
	// Includes the global database object and the name of all relevant tables.
	global $wpdb, $table;
	$tableDocument = $table['documents'];
	$tableCategory = $table['categories'];

	consoleLog("Logging Functional!");

	// Query a result set of all available categories with their IDs and names. Orderby
	// and the direction of ordering determines what new documents categories default to.
	// i.e. A new 'test' category is created. When this query runs, it's ID will be the
	// largest (since it is the newest). as such, it will appear at the top of the query,
	// which is what the select in the included file chooses initially.
	$categoryList = $wpdb->get_results("SELECT id,cat_name FROM $tableCategory WHERE status = 1 ORDER BY id DESC");

	// If a page number is specified, the documents list is paginated and the specified
	// page should be loaded. If documents are not paginated, default to page 1.
	$pagenum = isset($_GET['pagenum']) ? absint($_GET['pagenum']) : 1;

	// The magic-number amount of items to be listed before pagination is used.
	$limit = isset($_GET['show']) ? absint($_GET['show']) : 10;

	// Determines the starting index of documents to query for the page (if paginated).
	$offset = ($pagenum - 1) * $limit;

	// Count the total number of documents in the documents table and store it as a
	// variable.
	$total = $wpdb->get_var("SELECT COUNT(`id`) FROM $tableDocument");

	// Determine and store the number of pages required.
	$num_of_pages = ceil($total / $limit);

	// Store the query to select the specified documents on the specified 'page'.
	$sql = "SELECT $tableDocument.id,$tableDocument.title,$tableDocument.description,$tableDocument.status,$tableDocument.created,$tableCategory.cat_name
			FROM $tableDocument
			INNER JOIN $tableCategory ON $tableDocument.category_id = $tableCategory.id
			ORDER BY $tableDocument.id DESC
			LIMIT %d,%d";

	// Run the above stored query and store the result set as an object.
	$rows = $wpdb->get_results($wpdb->prepare($sql, $offset, $limit));

	// Set the include file and include it to display the documents.
	$includeFile = DTC_DOC_PLUGIN_DIR . 'views/document-list.php';
	include($includeFile);
}

function dtc_add_new_document()
{
	// Includes the global database object and the name of all relevant tables.
	global $wpdb, $table;
	$tableDocument = $table['documents'];
	$tableCategory = $table['categories'];

	// Query a result set of all available categories with their IDs and names. Orderby
	// and the direction of ordering determines what new documents categories default to.
	// i.e. A new 'test' category is created. When this query runs, it's ID will be the
	// largest (since it is the newest). as such, it will appear at the top of the query,
	// which is what the select in the included file chooses initially.
	$categoryList = $wpdb->get_results("SELECT id,cat_name FROM $tableCategory WHERE status = 1 ORDER BY id DESC");

	// Sanitizes the GET field for ID (trims it basically).
	$id = sanitize_text_field($_GET['id']);

	// Stores the query that searches all needed category and document information.
	$sql = "SELECT $tableCategory.cat_name,
			$tableDocument.id,$tableDocument.title,$tableDocument.description,$tableDocument.status,
			$tableDocument.created,$tableDocument.category_id,$tableDocument.document_link
			FROM $tableDocument
			INNER JOIN $tableCategory ON $tableDocument.category_id = $tableCategory.id
			WHERE $tableDocument.id = %d";

	// If there is an ID (the item already exist) then query the document information and
	// category name to set as the initial values of the user input tags of the included
	// file.
	if (!empty($id)) {
		$documentRow = $wpdb->get_row($wpdb->prepare($sql, $id));
	}

	// Includes the file to display the ad document menu.
	$includeFile = DTC_DOC_PLUGIN_DIR . 'views/add_document.php';
	include($includeFile);
}

###############################################
############# DOCUMENT ACTIONS ################
###############################################

// This is the action taken when the user submits the form on the add_document.php view.
add_action('admin_post_add_document', 'dtc_save_document');
function dtc_save_document()
{
	// Includes the global database object and the name of all relevant tables. Also
	// starts an output object.
	global $wpdb, $table;
	$tableDocument = $table['documents'];
	ob_start();

	// Sanitize the ID field (trim the string).
	$id = sanitize_text_field($_POST['id']);

	// If the action of _POST is 'add_document'...
	if ($_POST['action'] == 'add_document') {

		// Store all of the user input fields of the POST request as variables.
		$category_id = sanitize_text_field($_POST['category_id']);
		$title = sanitize_text_field(stripslashes(trim($_POST['title'])));
		$description = sanitize_text_field(stripslashes(trim($_POST['description'])));
		$document_link = substr(sanitize_text_field($_POST['document_link']), strpos(sanitize_text_field($_POST['document_link']), "/wp-content"));
		$document_type = pathinfo($document_link, PATHINFO_EXTENSION);

		// If the ID is not empty, this was an edit and the table should be UPDATED.
		if (!empty($id)) {
			$wpdb->update($tableDocument, array('category_id' => $category_id, 'title' => $title, 'description' => $description, 'document_type' => $document_type, 'document_link' => $document_link), array('id' => $id));

		// If the ID is empty, this is a NEW document and should be INSERTED.
		} else {
			$wpdb->insert($tableDocument, array('category_id' => $category_id, 'title' => $title, 'description' => $description, 'document_type' => $document_type, 'document_link' => $document_link));
		}

		// Redirect the user to the display all documents page and exit.
		$redirectUrl = admin_url('admin.php?page=dtc_display_all_documents');
		wp_redirect($redirectUrl);
		exit;
	}
}

// A function that takes an array of document IDs and a category ID, then sets all
// documents of that document ID's category_ids to the given category ID.
add_action('admin_post_mass_cat_change', 'mass_category_change');
function mass_category_change() {

	// Includes the global database object and the name of all relevant tables. Also
	// starts an output object.
	global $wpdb, $table;
	$tableDocument = $table['documents'];
	ob_start();

	// Sanitize the ID field (trim the string).
	$id = $_POST['id'];
	$categoryID = $_POST['categoryID'];

	foreach ($id as $docID) {
		// If the action of _POST is 'mass_cat_change'...
		if ($_POST['action'] == 'mass_cat_change') {
			$wpdb->query($wpdb->prepare("UPDATE $tableDocument SET category_id=%d WHERE id=%d", $categoryID, $docID));
		}
	}

	// Returns the user to the documents page.
	echo "<script>window.location.href = '" . home_url() . "/wp-admin/admin.php?page=dtc_display_all_documents'</script>";
}

#################################################
############# CATEGORY FUNCTIONS ################
#################################################

// This function queries a result set of all existing categories and includes the file to
// display them.
function dtc_all_categories()
{
	// Includes the global database object and the name of all relevant tables.
	global $wpdb, $table;
	$tableCategory = $table['categories'];

	// If a page number is specified, the categories list is paginated and the specified
	// page should be loaded. If categories are not paginated, default to page 1.
	$pagenum = isset($_GET['pagenum']) ? absint($_GET['pagenum']) : 1;

	// The magic-number amount of items to be listed before pagination is used.
	$limit = isset($_GET['show']) ? absint($_GET['show']) : 10;

	// Determines the starting index of categories to query for the page (if paginated).
	$offset = ($pagenum - 1) * $limit;

	// Count the total number of categories in the categories table and store it as a
	// variable.
	$total = $wpdb->get_var("SELECT COUNT(`id`) FROM $tableCategory");

	// Determine and store the number of pages required.
	$num_of_pages = ceil($total / $limit);

	// Store the query to select the specified categories on the specified 'page'.
	$sql = "SELECT id,cat_name,cat_desc,status,created from $tableCategory order by id desc LIMIT %d,%d";

	// Run the above stored query and store the result set as an object.
	$rows = $wpdb->get_results($wpdb->prepare($sql, $offset, $limit));

	// Set the include file and include it to display the documents.
	$includeFile = DTC_DOC_PLUGIN_DIR . 'views/category-list.php';
	include($includeFile);
}

// This function is used to add a new category.
function dtc_add_new_category()
{
	// Includes the global database object and the name of all relevant tables.
	global  $wpdb, $table;
	$tableCategory = $table['categories'];

	// Sanitize the ID field (trim the string).
	$id = sanitize_text_field($_GET['id']);

	// If the id exists, get the category information to set as the initial values for the
	// user input tags in the included file.
	if (!empty($id)) {
		$documentRow = $wpdb->get_row($wpdb->prepare("SELECT id,cat_name,cat_desc FROM $tableCategory WHERE id = %d", $id));
	}

	// Set the include file and include it to display the documents.
	$includeFile = DTC_DOC_PLUGIN_DIR . 'views/add_category.php';
	include($includeFile);
}

###############################################
############# CATEGORY ACTIONS ################
###############################################

// This is the function performed when a post request is sent through with the action 'add_category'.
add_action('admin_post_add_category', 'dtc_handle_save_category');
function dtc_handle_save_category()
{
	// Includes the global database object and the name of all relevant tables. Also
	// initializes an output object.
	global  $wpdb,$table;
	$tableCategory = $table['categories'];
	ob_start();

	// Sanitize the ID field (trim the string).
	$id = sanitize_text_field($_POST['id']);

	// If a post request is sent and action is set to add_category...
	if ($_POST['action'] == 'add_category') {

		// Get the category name and description. If the name is 'Uncategorized' then
		// rename it to 'Uncategorized (copy)'. This shouldn't be necessary since the
		// actual 'Uncategorized' category's Id is stored as soon as it is created, but
		// it's better safe than sorry.
		$cat_name = sanitize_text_field(stripslashes(trim($_POST['cat_name'])));
		$cat_desc = sanitize_text_field(stripslashes(trim($_POST['cat_desc'])));
		if ($cat_name == "Uncategorized") {
			$cat_name = "Uncategorized (copy)";
		}

		// If the ID is not empty, then this category exists and is just being edited and
		// should be UPDATED.
		if (!empty($id)) {
			$wpdb->update($tableCategory, array('cat_name' => $cat_name, 'cat_desc' => $cat_desc), array('id' => $id));

		// If the ID doesn't exist, this is a new category addition and should be INSERTED.
		} else {
			$wpdb->insert($tableCategory, array('cat_name' => $cat_name, 'cat_desc' => $cat_desc));
		}

		// Redirect the user to the page that lists all categories and exit.
		$redirectUrl = admin_url('admin.php?page=dtc_all_categories');
		wp_redirect($redirectUrl);
		exit;
	}
}

###############################################
############# HYBRID FUNCTIONS ################
###############################################

// This function is used to disable and enable status' of documents and categories.
function dtc_change_item_status()
{
	// Starts a return object, includes the global database variable, and specifies the
	// table name using the name sent via the GET request.
	ob_start();
	global  $wpdb, $table;
	$tableDocuments = $table['documents'];
	$table_name = $wpdb->prefix . sanitize_text_field($_GET['table']);

	// Sanitize the 'value' field (trim the string). This represents the items status.
	$status = sanitize_text_field($_GET['value']);

	// Inverts the current status and stores it as the new status.
	$newStatus = ($status == 1) ? 0 : 1;

	// Sanitize the ID field (trim the string).
	$id = sanitize_text_field($_GET['id']);

	// Update the given items status to the new (inverted) status.
	$wpdb->update($table_name, array('status' => $newStatus), array('id' => $id));

	// Clean the output object.
	ob_clean();

	// If this was a request for a document, return the user to the list all documents
	// page. If this was a request for a category, return the user to the list all
	// categories page.
	if ($table_name != $tableDocuments) {
		echo "<script>window.location.href = '" . home_url() . "/wp-admin/admin.php?page=dtc_all_categories'";
	} else {
		echo "<script>window.location.href = '" . home_url() . "/wp-admin/admin.php?page=dtc_display_all_documents'";
	}
	echo "</script>";
}

function dtc_mass_change_item_status() {

	// Includes the global database object and the name of all relevant tables. Also
	// starts an output object.
	global $wpdb, $table;
	$tableName = $wpdb->prefix . sanitize_text_field($_POST['table']);
	$tableDocuments = $table['documents'];
	ob_start();

	// Sanitize the ID field (trim the string).
	$id = $_POST['id'];
	$status = (($_POST['action'] == 'enable_all') ? 1 : 0);

	foreach ($id as $itemID) {
		$wpdb->update($tableName, array('status' => $status), array('id' => $itemID));
	}

	// If this was a request for a document, return the user to the list all documents
	// page. If this was a request for a category, return the user to the list all
	// categories page.
	if ($tableName != $tableDocuments) {
		echo "<script>window.location.href = '" . home_url() . "/wp-admin/admin.php?page=dtc_all_categories'";
	} else {
		echo "<script>window.location.href = '" . home_url() . "/wp-admin/admin.php?page=dtc_display_all_documents'";
	}
	echo "</script>";
}

// This function is used to delete both documents and categories from their respective tables.
function dtc_delete_item()
{
	// Starts a return object, includes the global database variable, and specifies the
	// table name using the name sent via the GET request. Also specifies all relevant
	// table names.
	ob_start();
	global $wpdb, $uncategorizedID, $table;
	$tableDocument = $table['documents'];
	$table_name = $wpdb->prefix . sanitize_text_field($_GET['table']);

	// Sanitize the ID field (trim the string).
	$id = sanitize_text_field($_GET['id']);

	// If this is a request for the documents table, set all documents using this category
	// ID to the 'Uncategorized' category ID.
	if ($table_name != $tableDocument) {
		$wpdb->query($wpdb->prepare("UPDATE $tableDocument SET category_id=%d WHERE category_id=%d", $uncategorizedID, $id));
	}

	// Create the deleltion query, prepare it, and run it.
	$deleteTopic = "DELETE FROM $table_name WHERE id=%d";
	$wpdb->query($wpdb->prepare($deleteTopic, $id));

	// If this was a request for a document, return the user to the list all documents
	// page. If this was a request for a category, return the user to the list all
	// categories page.
	if ($table_name != $tableDocument) {
		echo "<script>window.location.href = '" . home_url() . "/wp-admin/admin.php?page=dtc_all_categories'";
	} else {
		echo "<script>window.location.href = '" . home_url() . "/wp-admin/admin.php?page=dtc_display_all_documents'";
	}
	echo "</script>";
}

add_action('admin_post_bulk_delete', 'bulk_delete_items');
function bulk_delete_items() {

	// Includes the global database object and the name of all relevant tables. Also
	// starts an output object.
	global $wpdb, $table, $uncategorizedID;
	$tableDocument = $table['documents'];
	$tableName = $wpdb->prefix . sanitize_text_field($_POST['table']);
	$isDocTable = $tableName == $tableDocument;
	ob_start();

	// Sanitize the ID field (trim the string).
	$id = $_POST['id'];

	// If the action of _POST is 'add_document'...
	if ($_POST['action'] == 'bulk_delete') {

		foreach ($id as $rowID) {
			// If this is a request for the documents table, set all documents using this category
			// ID to the 'Uncategorized' category ID.
			if (!$isDocTable) {
				$wpdb->query($wpdb->prepare("UPDATE $tableDocument SET category_id=%d WHERE category_id=%d", $uncategorizedID, $rowID));
			}

			// Create the deleltion query, prepare it, and run it.
			$deleteTopic = "DELETE FROM $tableName WHERE id=%d";
			$wpdb->query($wpdb->prepare($deleteTopic, $rowID));
		}
	}

	// If this was a request for a document, return the user to the list all documents
	// page. If this was a request for a category, return the user to the list all
	// categories page.
	if (!$isDocTable) {
		echo "<script>window.location.href = '" . home_url() . "/wp-admin/admin.php?page=dtc_all_categories'";
	} else {
		echo "<script>window.location.href = '" . home_url() . "/wp-admin/admin.php?page=dtc_display_all_documents'";
	}
	echo "</script>";
}

###############################################
############# HELPER FUNCTIONS ################
###############################################

// This function is just a lookup table for what CSS class to use depending on the file
// extension of the listed file. This is used for the document listing shortcodes
// front-end.
function dtc_file_extension_list($ext)
{
	switch ($ext) {
		case 'doc':
		case 'docx':
			$ext = "fa-file-word-o";
			break;
		case 'pdf':
			$ext = "fa-file-pdf-o";
			break;
		case 'txt':
			$ext = "fa-file-text-o";
			break;
		case 'zip':
		case 'rar':
			$ext = "fa-file-zip-o";
			break;
		case 'ppt':
		case 'pptx':
			$ext = "fa-file-powerpoint-o";
			break;
		case 'xls':
		case 'csv':
		case 'xlsx':
			$ext = "fa-file-excel-o";
			break;
		case 'png':
		case 'jpg':
		case 'jpeg':
		case 'gif':
			$ext = "fa-file-image-o";
			break;
		case 'com':
			$ext = "fa-globe";
			break;
		default:
			$ext = "fa-file-o";
	}
	return $ext;
}

// This function is used during plugin initialization to check for an 'Uncategorized'
// category, create one if one is not found, then return the found or new ones ID. the
// check is largely necessary because of the repetitious calls to the initialization
// function, but the necesity of this task to be performed upon initialization at least
// once.
function checkAddUncategorized()
{
	// Includes the global database object and the name of all relevant tables.
	global $wpdb, $table;
	$tableCategory = $table['categories'];

	// Sets a value equal to the return of a check for any category with the name
	// 'Uncategorized'. NULL if one is not found.
	$check = getCategoryFromName("Uncategorized");

	// If none is found (check is NULL), insert a new category called 'Uncategorized' into
	// the categories table and return its ID for storage and later reference.
	if ($check == NULL) {
		$wpdb->insert($tableCategory, array('cat_name' => "Uncategorized", 'cat_desc' => 'This document has not been categorized, or was part of a category that has been deleted.'));
		return $wpdb->insert_id;
	}

	// If you've reached this point, $check returned non-NULL and the ID of the category
	// it found is returned.
	return $check->id;
}

// Takes the name of a category in the database and does a case-insensitive query that retrieves any matching category's ID and name.
function getCategoryFromName($name)
{
	// Includes the global database object and the name of all relevant tables.
	global $wpdb, $table;
	$tableCategory = $table['categories'];

	// Queries the category table for the id and name of any category case-insensitive
	// matching the given category name.
	$check = $wpdb->get_row($wpdb->prepare("SELECT id,cat_name FROM $tableCategory WHERE cat_name LIKE %s", $name));

	// Return the result of the above query. NULL if nothing found, an object with the
	// category name and ID if found.
	return $check;
}

function consoleLog($log) {
	echo "<script>console.log('".json_encode($log, JSON_HEX_TAG)."');</script>";
}

#########################################
############# SHORTCODES ################
#########################################

/*

This is the shortcode for the document list.

*/
add_shortcode('dtc-doc-documents-list', 'dtc_short_list_documents');
function dtc_short_list_documents($atts)
{

	// Includes the global database object and opens an object to return at the end of the
	// function.
	global $wpdb, $table;
	ob_start();

	// Specifies and sets the defaults of shortcode attributes.
	$options = shortcode_atts(array(
		'orderby' => 'id',
		'sortorder' => 'asc',
		'hidedisableddocs' => 'true',
		'hidedisabledcats' => 'true',
		'category' => 'all',
		'itemsatatime' => '10', // 50px per individual item.
	), $atts, 'dtc-doc-documents-list');

	// Specifies the name of the documents and categories table.
	$tableDocuments = $table['documents'];
	$tableCategory = $table['categories'];

	// The section that stitches together the select statement.
	$counter = 0;
	$selectModule = "SELECT title,document_link FROM $tableDocuments";

	// The section that stitches together the where statement.
	$counter = 0;
	$whereModule = "WHERE ";
	$whereModule = $whereModule . (($options['hidedisableddocs'] == "true") ? $tableDocuments . ".status=1" : "");
	if ((($options['category'] != "all") || ($options['hidedisabledcats'] == "true")) && ($options['hidedisableddocs'] == "true")) {
		$whereModule = $whereModule . " AND ";
	}
	$whereModule = $whereModule . (($options['hidedisabledcats'] == "true") ? $tableCategory . ".status=1" : "");
	if (($options['category'] != "all") && ($options['hidedisabledcats'] == "true")) {
		$whereModule = $whereModule . " AND ";
	}

	if (trim(strtolower($options['category'])) != "all") {
		$whereModule = $whereModule . " $tableCategory.cat_name IN (";
		$categories = explode(',', $options['category']);
		foreach ($categories as $category) {
			$whereModule = $whereModule . "'" . trim($category) . "'";
			if (++$counter != count($categories)) {
				$whereModule = $whereModule . ",";
			}
		}
		$whereModule = $whereModule . ")";
	}

	// This section stitches the module that tells how to sort the list together.
	$counter = 0;
	$orderByModule = " ORDER BY ";
	$orders = explode(',', $options['orderby']);
	foreach ($orders as $order) {
		switch (trim(strtolower($order))) {
			case "id":
				$orderByModule = $orderByModule . $tableDocuments . ".id";
				break;
			case "title":
				$orderByModule = $orderByModule . $tableDocuments . ".title";
				break;
			case "filetype":
				$orderByModule = $orderByModule . $tableDocuments . ".document_type";
				break;
			case "created":
				$orderByModule = $orderByModule . $tableDocuments . ".created";
				break;
			case "category":
				$orderByModule = $orderByModule . $tableCategory . ".cat_name";
				break;
			case "description":
				$orderByModule = $orderByModule . $tableDocuments . ".description";
				break;
			case "status":
				$orderByModule = $orderByModule . $tableDocuments . ".status";
				break;
			case "document_link":
				$orderByModule = $orderByModule . $tableDocuments . ".document_link";
				break;
		}
		if (++$counter != count($orders)) {
			$orderByModule = $orderByModule . ",";
		}
	}
	$orderByModule = $orderByModule . ((strtolower(trim($options['sortorder'])) == "asc") ? " ASC" : " DESC");

	// Joins the category table for the query, prepare the query, and run it.
	$documentRows = $wpdb->get_results($wpdb->prepare($selectModule . " INNER JOIN $tableCategory ON $tableDocuments.category_id = $tableCategory.id " . $whereModule . $orderByModule));
	$maxHeight = trim($options['itemsatatime']) * 50;

	// Specifies the file to include (the view to present) and includes it.
	$includeFile = plugin_dir_path(__FILE__) . 'views/shortcode-document-list.php';
	include($includeFile);

	// Flushes the object and returns the flush.
	$output = ob_get_clean();
	return $output;
}

/*

This is the shortcode that generates the button that automatically links the most recent
document of the specified category if one is specified.

*/
add_shortcode('dtc-doc-recent-button', 'dtc_short_recent_btn');
function dtc_short_recent_btn($atts)
{

	// Includes the global database object and opens an object to return at the end of the
	// function.
	global $wpdb, $table;
	ob_start();

	// Specifies and sets the defaults of shortcode attributes.
	$options = shortcode_atts(array(
		'category' => 'all',
		'backgroundcolor' => '#2eb3ed',
		'textcolor' => 'whitesmoke',
		'center' => 'true',
		'widthpercentage' => '45%',
	), $atts, 'dtc-doc-recent-button');

	$backgroundColor = trim($options['backgroundcolor']);
	$textColor = trim($options['textcolor']);
	$center = (trim($options['center']) == "true") ? "margin-right:auto;margin-left:auto;" : "";
	$widthPercentage = trim($options['widthpercentage']);

	// Specifies the name of the documents table.
	$tableDocuments = $table['documents'];

	// Starts the select for the latest document.
	$query = "SELECT document_link FROM $tableDocuments";

	// Removes trailing whitespace and any other words that may be separated with commas (in case someone tries to enter multiple categories).
	$catStr = (strpos($options['category'], ",")) ? trim(substr($options['category'], 0, strpos($options['category'], ","))) : trim($options['category']);

	$query = $query . " WHERE status=1";

	// If a category is specified, find it. If it exists, add a where statement to the
	// query that specifies the category of the latest document.
	if ($catStr != 'all') {
		$category = getCategoryFromName($catStr);
		if ($category != NULL) {
			$query = $query . " AND category_id=$category->id";
		}
	}

	// Oder by the created date and order is descending so the latest is at the top.
	$query = $query . " ORDER BY created DESC;";

	// Prepare and run the query. Preparation prevents SQL injection.
	$document = $wpdb->get_results($wpdb->prepare($query));

	// If no document was found or the category was not found, indicate so by setting
	// document to NULL.
	$document = ($document[0]->document_link == NULL || (($catStr != 'all') && $category == NULL)) ? "NULL" : $document[0]->document_link;

	// Specifies the file to include (the view to present) and includes it.
	$includeFile = plugin_dir_path(__FILE__) . 'views/shortcode-recent-document-btn.php';
	include($includeFile);

	// Flushes the object and returns the flush.
	$output = ob_get_clean();
	return $output;
}


// This is the shortcode used to provide a new-tab link to the most recent document of all
// of them, or of a specified category. The difference between this and recent button is
// that this prints the name of the category and links it to the most recent document (if
// a category is specified). Or, prints 
add_shortcode('dtc-doc-recent-redirect', 'dtc_short_recent_redirect');
function dtc_short_recent_redirect($atts)
{

	// Includes the global database object and opens an object to return at the end of the
	// function.
	global $wpdb, $table;
	ob_start();

	// Specifies and sets the defaults of shortcode attributes.
	$options = shortcode_atts(array(
		'category' => 'all',
		'cssclasses' => '',
		'displaytext' => '',
	), $atts, 'dtc-doc-recent-redirect');

	// Specifies the name of the documents table.
	$tableDocuments = $table['documents'];

	// Starts the select for the latest document.
	$query = "SELECT document_link FROM $tableDocuments";

	// Removes trailing whitespace and any other words that may be separated with commas (in case someone tries to enter multiple categories).
	$catStr = (strpos($options['category'], ",")) ? trim(substr($options['category'], 0, strpos($options['category'], ","))) : trim($options['category']);

	$query = $query . " WHERE status=1";

	// If a category is specified, find it. If it exists, add a where statement to the
	// query that specifies the category of the latest document.
	if ($catStr != "all") {
		$category = getCategoryFromName($catStr);
		if ($category != NULL) {
			$query = $query . " AND category_id=%d";
		}
	}

	// Oder by the created date and order is descending so the latest is at the top.
	$query = $query . " ORDER BY created DESC";

	// Prepare and run the query. Preparation prevents SQL injection.
	$document = $wpdb->get_results($wpdb->prepare($query, $category->id));

	// If no document was found or the category was not found (and is not 'all'), indicate so by setting
	// document to NULL.
	$document = ($document[0]->document_link == NULL || (($catStr != 'all') && $category == NULL)) ? "NULL" : $document[0]->document_link;

	if ($document == "NULL") {
		echo "<p style='color:darkred;'>No docs in '" . (($category->cat_name == NULL) ? $options['category'] : $category->cat_name) . "'!</p>";
	} else {
		echo "<p class=\"".$options['cssclasses']."\"><a href=\"$document\" target=\"_blank\">" . ((trim($options['displaytext']) == "") ? (($category->cat_name == NULL) ? $catStr : $category->cat_name) : trim($options['displaytext'])) . "</a></p>";
	}

	// Flushes the object and returns the flush.
	$output = ob_get_clean();
	return $output;
}
