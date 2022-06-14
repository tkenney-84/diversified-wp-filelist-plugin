		<script type="text/javascript">

			function SetFormAction(action) {
				document.primaryForm.action = action;
			}

			function SetAction(action) {
				document.getElementById("action").value = action;
			}

			function CheckUncheckAll(){
				var  selectAllCheckbox=document.getElementById("checkUncheckAll");
				if(selectAllCheckbox.checked==true){
					document.getElementById("bulkButtonOption0").style.visibility = 'visible';
					document.getElementById("bulkButtonOption1").style.visibility = 'visible';
					document.getElementById("bulkButtonOption2").style.visibility = 'visible';
					document.getElementById("bulkButtonOption3").style.visibility = 'visible';
					document.getElementById("bulkButtonOption4").style.visibility = 'visible';
					var checkboxes =  document.getElementsByName("id[]");
					for(var i=0, n=checkboxes.length;i<n;i++) {
						checkboxes[i].checked = true;
					}
				} else {
					document.getElementById("bulkButtonOption0").style.visibility = 'hidden';
					document.getElementById("bulkButtonOption1").style.visibility = 'hidden';
					document.getElementById("bulkButtonOption2").style.visibility = 'hidden';
					document.getElementById("bulkButtonOption3").style.visibility = 'hidden';
					document.getElementById("bulkButtonOption4").style.visibility = 'hidden';
					var checkboxes =  document.getElementsByName("id[]");
					for(var i=0, n=checkboxes.length;i<n;i++) {
						checkboxes[i].checked = false;
					}
				}
			}

			function CheckMainUncheckMain() {
				document.getElementById("bulkButtonOption0").style.visibility = 'hidden';
				document.getElementById("bulkButtonOption1").style.visibility = 'hidden';
				document.getElementById("bulkButtonOption2").style.visibility = 'hidden';
				document.getElementById("bulkButtonOption3").style.visibility = 'hidden';
				document.getElementById("bulkButtonOption4").style.visibility = 'hidden';
				var selectAllCheckbox=document.getElementById("checkUncheckAll");
				var checkboxes = document.getElementsByName("id[]");
				var mainStatus = true;
				for(var i=0, n=checkboxes.length;i<n;i++) {
					if(!checkboxes[i].checked) {
						mainStatus = false;
					} else {
						document.getElementById("bulkButtonOption0").style.visibility = 'visible';
						document.getElementById("bulkButtonOption1").style.visibility = 'visible';
						document.getElementById("bulkButtonOption2").style.visibility = 'visible';
						document.getElementById("bulkButtonOption3").style.visibility = 'visible';
						document.getElementById("bulkButtonOption4").style.visibility = 'visible';
					}
				}
				selectAllCheckbox.checked = mainStatus;
			}
		</script>
<div class="wrap">

	<!-- The displayed page title. -->
	<h1 class="wp-heading-inline">DTC Documents</h1>

	<!-- The button to add a new document. -->
	<a href="<?php echo admin_url('admin.php?page=dtc_add_new_document'); ?>" class="page-title-action">Add New</a>

	<!-- The form that allows bulk actions submission. Action is set by default but subject to change. -->
	<form method="post" name="primaryForm" id="addDocuments" action="<?php echo get_admin_url(); ?>admin-post.php" enctype="multipart/form-data">

	<!-- The bulk deletion button. -->
	<input style="display:inline" class="button-outline-danger" id="bulkButtonOption0" type="submit" value="Bulk Delete" onclick="SetFormAction('<?php echo get_admin_url(); ?>admin-post.php'); SetAction('bulk_delete'); return confirm('Are you sure you want to delete these documents?')"/>

	<!-- The select and input that allows the changing of multiple documents categories at once. -->
	<input style="display:inline;margin-left:0px;position:unset;" class="page-title-action" id="bulkButtonOption1" type="submit" value="Change Selected Document Categories" onclick="SetFormAction('<?php echo get_admin_url(); ?>admin-post.php'); SetAction('mass_cat_change'); return confirm('Are you sure you want to change all selected documents\' categories to the selected category?')"/>
	<select id="bulkButtonOption2" style="display:inline;margin:0px;vertical-align:baseline;" name="categoryID" class="large-text required">
		<?php foreach ($categoryList as $category) { ?>
			<option value="<?php echo $category->id ?>"><?php echo $category->cat_name ?></option>
		<?php } ?>
	</select>

	<!-- The enable selected button. -->
	<input style="display:inline;margin-left:0px;position:unset;" class="page-title-action" id="bulkButtonOption3" type="submit" value="Enable Selected" onclick="SetFormAction('<?php echo admin_url('admin.php?page=dtc_mass_change_item_status'); ?>'); SetAction('enable_all'); return confirm('Are you sure you want to enable these documents?')"/>

	<!-- The disable selected button. -->
	<input style="display:inline" class="button-outline-danger" id="bulkButtonOption4" type="submit" value="Disable Selected" onclick="SetFormAction('<?php echo admin_url('admin.php?page=dtc_mass_change_item_status'); ?>'); SetAction('disable_all'); return confirm('Are you sure you want to disable these documents?')"/>

	<!-- Hidden inputs that indicate what action to take against what table. The script at the top of this document determines the action, hence its value is blank. -->
	<input type='hidden' name='table' value='dtc_doc_documents' />
	<input type='hidden' id="action" name='action' value='' />

	<hr style="margin-top:5px;" class="wp-header-end">
	<div class="table-content">
		<table class='wp-list-table widefat fixed striped posts' id="dtc_table">

			<!-- Adds the table header with all column headers. -->
			<thead>
			<tr>
				<th class="manage-column dtc-checkbox">

					<!-- If there is more than 1 document, show the main bulk actions checkbox. -->
					<?php if (count($rows) > 1) { ?>
						<input id="checkUncheckAll" onClick="CheckUncheckAll()" class="dtc-checkbox" type="checkbox"></input>
					<?php } ?>
				</th>
				<th class="manage-column ss-list-width">Title</th>
				<th class="manage-column ss-list-width">Category</th>
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
					<td><h1 style="text-align:end;">None</h1></td>
					<td><h1>Found</h1></td>
					<td></td>
					<td></td>
				</tr>
			<?php }

			// For every record in the document table...
			foreach ($rows as $row) { ?>
				<tr>

					<!-- Show the bulk action selection checkbox. -->
					<td class="manage-column dtc-checkbox">
						<input onclick="CheckMainUncheckMain()" id="rowSelectCheckBox" name="id[]" class="dtc-checkbox" value="<?php echo $row->id; ?>" type="checkbox"></input>
					</td>

					<!-- Add a cell with the document title in it. -->
					<td class="manage-column ss-list-width"><?php echo $row->title; ?></td>

					<!-- Add a cell with the name of the document document in it. -->
					<td class="manage-column ss-list-width"><?php echo $row->cat_name; ?></td>

					<!-- Add a cell with the document description in it. -->
					<td class="manage-column ss-list-width"><?php echo $row->description; ?></td>

					<!-- Add a document that displays current document status. The user can select the contents of this cell to switch document status. -->
					<td class="manage-column ss-list-width"><a href="<?php echo admin_url('admin.php?page=dtc_change_item_status&id='.$row->id.'&table=dtc_doc_documents&value='.$row->status); ?>">Currently <?php echo ($row->status == "1") ? "Enabled" : "Disabled";?></a></td>

					<!-- Uses PHP to format and display the creation date of the document in another cell. -->
					<td class="manage-column ss-list-width"><?php echo date('M d, Y h:i A',strtotime($row->created)); ?></td>

					<!-- Adds the available actions to the final table cell. 'Edit' uses the 'Add document' menu to edit the document, 'Delete' warns the user
				         of the effects of deletion then deletes the document. -->
					<td>
						<a href="<?php echo admin_url('admin.php?page=dtc_add_new_document&id=' . $row->id); ?>" class="dtc-btn-admin dtc-btn-edit">Edit</a>
						<a href="<?php echo admin_url('admin.php?page=dtc_delete_item&table=dtc_doc_documents&id=' . $row->id); ?>" class="dtc-btn-admin dtc-btn-delete" onclick="return confirm('Are you sure you want to delete?')">Delete</a>
					</td>
				</tr>
			<?php } ?>
			</tbody>
		</table>
			</form>
	</div>

	<!-- Uses the limit (which determines how many items to show per page) to allow the user to specify how many items they want to see per page. -->
	<?php
		echo "<p style=\"display:inline;margin:5px;\">Show</p>";

		if ($limit != 10) {
			echo "<a style=\"display:inline;margin:5px;\" href=\"admin.php?page=dtc_display_all_documents&show=10\">10</a>";
		} else {
			echo "<p style=\"display:inline;margin:5px;font-weight:bold;\">10</p>";
		}

		if ($limit != 20) {
			echo "<a style=\"display:inline;margin:5px;\" href=\"admin.php?page=dtc_display_all_documents&show=20\">20</a>";
		} else {
			echo "<p style=\"display:inline;margin:5px;font-weight:bold;\">20</p>";
		}

		if ($limit != 50) {
			echo "<a style=\"display:inline;margin:5px;\" href=\"admin.php?page=dtc_display_all_documents&show=50\">50</a>";
		} else {
			echo "<p style=\"display:inline;margin:5px;font-weight:bold;\">50</p>";
		}

		if ($limit != 100) {
			echo "<a style=\"display:inline;margin:5px;\" href=\"admin.php?page=dtc_display_all_documents&show=100\">100</a>";
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