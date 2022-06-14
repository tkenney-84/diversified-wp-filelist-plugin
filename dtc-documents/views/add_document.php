<div class="wrap">
	<h1 class="wp-heading-inline">&nbsp;</h1>
	<form method="post" id="addDocuments" action="<?php echo get_admin_url(); ?>admin-post.php" enctype="multipart/form-data">
		<div class="postbox ">

			<!-- The displayed page title. -->
			<h3 class="handle"><span>Add Document</span></h3>

			<div class="inside">

				<!-- The text indicating a * means required. -->
				<strong><p>* = Required</p></strong>

				<!-- Div that collects the document title. Required. -->
				<div class="post-option">
					<label class="post-option-label">Title*</label>
					<div class="post-option-value">
						<input type="text" name="title" class="large-text required" id="title" value="<?php echo isset($documentRow->title) ? $documentRow->title : ''; ?>" />
					</div>
				</div>

				<!-- Div that collects the documents category. It retrieves a list of available categories from the database and adds them as options to a select tag. Required. -->
				<div class="post-option">
					<label class="post-option-label">Category*</label>
					<div class="post-option-value">
						<select name="category_id" id="category_id" class="large-text required">
							<?php foreach ($categoryList as $category) { ?>
								<option value="<?php echo $category->id ?>" <?php if ($documentRow->category_id == $category->id || !isset($documentRow->id) && $category->cat_name == "Uncategorized") { echo "selected"; } ?> ><?php echo $category->cat_name ?></option>
							<?php } ?>
						</select>
					</div>
				</div>

				<!-- Collects the document description. Optional. -->
				<div class="post-option">
					<label class="post-option-label">Description</label>
					<div class="post-option-value">
						<textarea type="text" name="description" class="large-text" id="description" /><?php echo isset($documentRow->description) ? $documentRow->description : ''; ?></textarea>
					</div>
				</div>

				<!-- Submits the documents ID with the form if it already exists. This is to pass along what record is the one being updated. -->
				<input type="hidden" name="id" value="<?php echo isset($documentRow->id) ? $documentRow->id : ''; ?>" />

				<!-- This is the first of two document upload actions. This is the one taken if the document already exists (is being edited). -->
				<?php if (!empty($documentRow->id)) { ?>
					<div class="post-option">
						<label class="post-option-label">Upload Document*</label>
						<div class="post-option-value">
							<a href="<?php echo $documentRow->document_link; ?>" target="__blank"><?php echo $documentRow->document_link; ?></a></span>
							<a href="javascript:void(0);" class="button btn-danger" id="removeDoc">Remove</a>
						</div>
					</div>
				<?php } ?>

				<!-- This is the second of two document upload actions. This is the one taken if the document doesn't yet exist (is being added). -->
				<div class="post-option" id="uploadDoc" style="display:<?php echo (!empty($documentRow->id)) ? 'none' : 'block'; ?>">
					<label class="post-option-label">Upload Document*</label>
					<div class="post-option-value">
						<input type="hidden" name="document_type" value="<?php echo isset($documentRow->document_type) ? $documentRow->document_type : 'document'; ?>" id="document_type">
						<input id="upload-document" type="button" class="button" value="Upload Document" />
						<span id="showLink"></span>
						<input type="hidden" name="document_link" class="large-text required" id="document_link" value="<?php echo isset($documentRow->document_link) ? $documentRow->document_link : ''; ?>" />
						<label class="error" id="fileError"></label>
					</div>
				</div>

				<!-- Specifies the action being performed. -->
				<input type='hidden' name='action' value='add_document' />

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