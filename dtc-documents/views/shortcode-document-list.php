<div id="grid">

<!-- If documents are stored in the database, list them. -->
<?php if (!empty($documentRows)) {?>
	<ul style="overflow-y:scroll;max-height:<?php echo $maxHeight; ?>px;z-index:2;">
	<?php foreach($documentRows as $row) {
		$ext = pathinfo($row->document_link, PATHINFO_EXTENSION);
		$iconClass = dtc_file_extension_list($ext);
		?>
		<a class="read-more-link" href="<?php echo $row->document_link;?>" target="_blank">
			<li class="doc-material fa list-content <?php echo $iconClass;?>">
				<span class="fileIA">
					<?php echo $row->title;?>
				</span>
			</li>
		</a>
	<?php } ?>
	</ul>
</div>

<!-- If there are no documents stored in the database, indicate this with an error message that appears in place of the list. -->
<?php } else { ?>
	<p style="color:darkred;font-weight:bold;background:lightgray;border-radius:5px;text-align:center;padding:5px;">No documents found under '<?php echo $options['category']; ?>'!</p>
<?php } ?>

