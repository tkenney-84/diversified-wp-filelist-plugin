<script type="text/javascript">

			// Allows the setting of the primary forms action. This function is not made for modularity.
			function SetFormAction(action) {
				document.primaryForm.action = action;
			}

			// Allows the setting of a specific buttons action. This function is made for modularit.
			function SetAction(action) {
				document.getElementById("action").value = action;
			}

			// This function checks if the primary (table header) checkbox is checked. If
			// it is, all other checkboxes are checked and bulk actions are made visible.
			// If it has just been unchecked, all other checkboxes are unchecked and bulk
			// actions are hidden. This function is not made for modularity.
			function CheckUncheckAll(){

				// Stores the main (table header) checkbox as a var.
				var  selectAllCheckbox=document.getElementById("checkUncheckAll");
				// Create an array of elements using the table body checkbox name.
				// (An array of all non-main checkboxes.)
				var checkboxes =  document.getElementsByName("id[]");

				// If the main checkbox is checked...
				if(selectAllCheckbox.checked==true){

					// Show all bulk actions.
					document.getElementById("bulkButtonOption0").style.visibility = 'visible';
					document.getElementById("bulkButtonOption1").style.visibility = 'visible';
					document.getElementById("bulkButtonOption2").style.visibility = 'visible';

					// For every checkbox in the non-main array, set it to checked.
					for(var i=0, n=checkboxes.length;i<n;i++) {
						checkboxes[i].checked = true;
					}

				// If the main checkbox is not checked...
				} else {

					// Hide all bulk actions.
					document.getElementById("bulkButtonOption0").style.visibility = 'hidden';
					document.getElementById("bulkButtonOption1").style.visibility = 'hidden';
					document.getElementById("bulkButtonOption2").style.visibility = 'hidden';

					// For every checkbox in the non-main array, set it to unchecked.
					for(var i=0, n=checkboxes.length;i<n;i++) {
						checkboxes[i].checked = false;
					}
				}
			}

			// Checks to see if all non-main (non-table header) checkboxes are checked. If
			// they are, check the main box. Also, if any of the small checkboxes are
			// checked, show the bulk actions.
			function CheckMainUncheckMain() {

				// Hide all bulk actions initially.
				document.getElementById("bulkButtonOption0").style.visibility = 'hidden';
				document.getElementById("bulkButtonOption1").style.visibility = 'hidden';
				document.getElementById("bulkButtonOption2").style.visibility = 'hidden';

				// Store the main (table header) checkbox as a var.
				var selectAllCheckbox=document.getElementById("checkUncheckAll");

				// Store an array of all non-main checkboxes as a variable.
				var checkboxes = document.getElementsByName("id[]");

				// Set the indicator of the main checkboxes status to true initially.
				var mainStatus = true;

				// For all checkboxes in the non-main array...
				for(var i=0, n=checkboxes.length;i<n;i++) {

					// If this checkbox is not checked...
					if(!checkboxes[i].checked) {

						// Set the final indicator of the main checkbox to false.
						mainStatus = false;

					// If the checkbox IS checked, that means at least one is, so show the
					// bulk options.
					} else {
						document.getElementById("bulkButtonOption0").style.visibility = 'visible';
						document.getElementById("bulkButtonOption1").style.visibility = 'visible';
						document.getElementById("bulkButtonOption2").style.visibility = 'visible';
					}
				}

				// Set the checked status of the main checkbox to whatever the final
				// indicator ended up as.
				selectAllCheckbox.checked = mainStatus;
			}
</script>

<div class="wrap">

	<!-- The displayed page title. -->
	<h1 class="wp-heading-inline">DTC Categories</h1>

	<!-- The button to add a new category. -->
	<a href="<?php echo admin_url('admin.php?page=dtc_add_new_category'); ?>" class="page-title-action">Add New</a>

	<!-- The form that allows bulk actions submission. Action is set by default but subject to change. -->
	<form method="post" name="primaryForm" id="addDocuments" action="<?php echo get_admin_url(); ?>admin-post.php" enctype="multipart/form-data">

	<!-- The bulk deletion button. -->
	<input style="display:inline" class="button-outline-danger" id="bulkButtonOption0" type="submit" value="Bulk Delete" onclick="SetFormAction('<?php echo get_admin_url(); ?>admin-post.php'); SetAction('bulk_delete'); return confirm('Are you sure you want to delete these documents?')"/>

	<!-- The enable selected button. -->
	<input style="display:inline;margin-left:0px;position:unset;" class="page-title-action" id="bulkButtonOption1" type="submit" value="Enable Selected" onclick="SetFormAction('<?php echo admin_url('admin.php?page=dtc_mass_change_item_status'); ?>'); SetAction('enable_all'); return confirm('Are you sure you want to enable these documents?')"/>

	<!-- The disable selected button. -->
	<input style="display:inline" class="button-outline-danger" id="bulkButtonOption2" type="submit" value="Disable Selected" onclick="SetFormAction('<?php echo admin_url('admin.php?page=dtc_mass_change_item_status'); ?>'); SetAction('disable_all'); return confirm('Are you sure you want to disable these documents?')"/>

	<!-- Hidden inputs that indicate what action to take against what table. The script at the top of this document determines the action, hence its value is blank. -->
	<input type='hidden' name='table' value='dtc_doc_categories' />
	<input type='hidden' id="action" name='action' value='' />

	<hr style="margin-top:5px;" class="wp-header-end">
	<div class="table-content">
		<table class='wp-list-table widefat fixed striped posts' id="dtc_table">

			<!-- Adds the table header with all column headers. -->
			<thead>
			<tr>
				<th class="manage-column dtc-checkbox">

					<!-- If there is more than 1 category, show the main bulk actions checkbox. -->
					<?php if (count($rows) > 1) { ?>
						<input id="checkUncheckAll" onClick="CheckUncheckAll()" class="dtc-checkbox" type="checkbox"></input>
					<?php } ?>
				</th>
				<th class="manage-column ss-list-width">Name</th>
				<th class="manage-column ss-list-width">Description</th>
				<th class="manage-column ss-list-width">Status</th>
				<th class="manage-column ss-list-width">Created</th>
				<th class="manage-column ss-list-width">Action</th>
			</tr>
			</thead>
			<tbody>

			<!-- If no records are found, indicate so by displaying pseudo-centered text. -->
			<?php
			if(empty($rows)) { ?>
				<tr>
					<td></td>
					<td></td>
					<td></td>
					<td><h1 style="text-align:center;">None Found</h1></td>
					<td></td>
					<td></td>
				</tr>
			<?php } ?>

			<!-- For every record in the category table... -->
			<?php foreach ($rows as $row) { ?>
				<tr>

					<td class="manage-column dtc-checkbox">

						<!-- If this is not the default, unmodifiable 'Uncategorized' category, print the bulk actions selection checkbox for this item. -->
						<?php if ($row->cat_name != "Uncategorized") { ?>
							<input onclick="CheckMainUncheckMain()" id="rowSelectCheckBox" name="id[]" class="dtc-checkbox" value="<?php echo $row->id; ?>" type="checkbox"></input>
						<?php } ?>
					</td>

					<!-- Add a cell with category name. -->
					<td class="manage-column ss-list-width"><?php echo $row->cat_name; ?></td>

					<!-- Add a cell with category description. -->
					<td class="manage-column ss-list-width"><?php echo $row->cat_desc; ?></td>

					<!-- Add a category that displays current category status. The user can select the contents of this cell to switch category status. -->
					<td class="manage-column ss-list-width">
						<a href="<?php echo admin_url('admin.php?page=dtc_change_item_status&id='.$row->id.'&table=dtc_doc_categories&value='.$row->status); ?>">Currently <?php echo ($row->status == "1") ? "Enabled" : "Disabled";?></a>
					</td>

					<!-- Uses PHP to format and display the creation date of the category in another cell. -->
					<td class="manage-column ss-list-width"><?php echo date('M d, Y h:i A',strtotime($row->created)); ?></td>

					<!-- Adds the available actions to the final table cell. 'Edit' uses the 'Add Category' menu to edit the category, 'Delete' warns the user
				         of the effects of deletion (most notably being that, upon deletion, all documents using this category are set to 'Uncategorized'.), and
						 finally checks if this is the category 'Uncategorized' (if it is, all actions are disabled and text is displayed to indicate that the
						 'Uncategorized' category is non-modifiable. -->
					<td>
						<?php if ($row->cat_name != "Uncategorized") {?>
							<a href="<?php echo admin_url('admin.php?page=dtc_add_new_category&id=' . $row->id); ?>" class="dtc-btn-admin dtc-btn-edit">Edit</a>
							<a href="<?php echo admin_url('admin.php?page=dtc_delete_item&table=dtc_doc_categories&id=' . $row->id); ?>" class="dtc-btn-admin dtc-btn-delete" onclick="return confirm('Are you sure you want to delete this category? All documents using this category will automatically be uncategorized!')">Delete</a>
						<?php } else { ?>
							<h4>Cannot Modify</h4>
						<?php } ?>
					</td>
				</tr>
			<?php } ?>
			</tbody>
		</table>
	</div>

	<!-- Uses the limit (which determines how many items to show per page) to allow the user to specify how many items they want to see per page. -->
	<?php
		echo "<p style=\"display:inline;margin:5px;\">Show</p>";

		if ($limit != 10) {
			echo "<a style=\"display:inline;margin:5px;\" href=\"admin.php?page=dtc_all_categories&show=10\">10</a>";
		} else {
			echo "<p style=\"display:inline;margin:5px;font-weight:bold;\">10</p>";
		}

		if ($limit != 20) {
			echo "<a style=\"display:inline;margin:5px;\" href=\"admin.php?page=dtc_all_categories&show=20\">20</a>";
		} else {
			echo "<p style=\"display:inline;margin:5px;font-weight:bold;\">20</p>";
		}

		if ($limit != 50) {
			echo "<a style=\"display:inline;margin:5px;\" href=\"admin.php?page=dtc_all_categories&show=50\">50</a>";
		} else {
			echo "<p style=\"display:inline;margin:5px;font-weight:bold;\">50</p>";
		}

		if ($limit != 100) {
			echo "<a style=\"display:inline;margin:5px;\" href=\"admin.php?page=dtc_all_categories&show=100\">100</a>";
		} else {
			echo "<p style=\"display:inline;margin:5px;font-weight:bold;\">100</p>";
		}

		echo "<p style=\"display:inline;margin:5px;\">items per page.</p>";
	?>

	<!-- If the number of items is greater than the number set in the non-view index.php around line 191 (10 by default), then paginate. -->
	<?php

	// Generates the object containing the paginated pages. The nav buttons are stored in a backwards array.
	$page_links = paginate_links( array(
		'base' => add_query_arg( 'pagenum', '%#%' ),
		'format' => '',
		'prev_next' => false,
		// 'prev_text' => __( '&laquo;', 'text-domain' ),
		// 'next_text' => __( '&raquo;', 'text-domain' ),
		'total' => $num_of_pages,
		'current' => $pagenum,
		'type' => 'array',
	));

	// If page links is not null, print the navbar divs and add each button to the navbar in reverse order (or the buttons will be backwards).
	if ($page_links) {
		echo '<div class="tablenav"><div style="float:none;text-align:center;margin-top:10px;" class="tablenav-pages">';
		foreach ($page_links as $page) {
			echo $page;
		}
		echo '</div></div>';
	} ?>

	<script>CheckMainUncheckMain();</script>
	<script>CheckUncheckAll();</script>
</div>