<div class="wrap">
	<hr class="wp-header-end">
	<div class="dtc-dashboard">

		<!-- Plugin logo. -->
		<img src="<?php echo plugin_dir_url(dirname(__FILE__ ));?>img/dtc-doc-icon.png" width="140" class="dtc-image-circle" draggable="false"/>

		<!-- Plugin title. -->
		<h2>DTC Documents</h2>

		<!-- Plugin main menu content. -->
		<div style="display:inline-block; margin: 0px auto;text-align: center;width:100%;">
		<a href="<?php echo admin_url('admin.php?page=dtc_display_all_documents'); ?>" class="dtc_doc_btn">All Documents</a>
		<?php if (current_user_can( 'manage_options' )) { ?>
		<a href="<?php echo admin_url('admin.php?page=dtc_all_categories'); ?>" class="dtc_doc_btn">All Categories</a>
		<?php } ?>
		</div>
	</div>
</div>