<div class="wrap">
	<h1 class="wp-heading-inline">&nbsp;</h1>
	<form method="post" id="addDocuments" action="<?php echo get_admin_url(); ?>admin-post.php" enctype="multipart/form-data">
		<div class="postbox ">

			<!-- The displayed page title. -->
			<h3 class="handle"><span>Add Document</span></h3>

			<div class="inside">

				<!-- The text indicating a * means required. -->
				<strong><p>* = Required</p></strong>

				<!-- The div that collects the category name. This is required. -->
				<div class="post-option">
					<label class="post-option-label">Name*</label>
					<div class="post-option-value">
						<input type="text" name="cat_name" class="large-text required" id="cat_name" value="<?php echo isset($documentRow->cat_name) ? $documentRow->cat_name : ''; ?>" />
					</div>
				</div>

				<!-- The div that collects the category description. Optional. -->
				<div class="post-option">
					<label class="post-option-label">Description</label>
					<div class="post-option-value">
						<input type="text" name="cat_desc" class="large-text" id="cat_desc" value="<?php echo isset($documentRow->cat_desc) ? $documentRow->cat_desc : ''; ?>"></input>
					</div>
				</div>

				<!-- Submits the category's ID with the form if it already exists. This is to pass along what record is the one being updated. -->
				<input type="hidden" name="id" id="id" value="<?php echo isset($documentRow->id) ? $documentRow->id : ''; ?>" />

				<!-- Specifies the action being performed. -->
				<input type='hidden' name='action' value='add_category' />

				<!-- Div that provides buttons to return the user to the previous page or submit the category. -->
				<div class="post-option">
					<div class="post-option-value">
						<input type="submit" value="Save" class="button button-primary" id="sub" />
						<a href="javascript:history.go(-1);" class="button button-outline-primary">Back</a>
					</div>
				</div>
			</div>
		</div>
	</form>
</div>