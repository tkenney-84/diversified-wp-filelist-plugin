
<!-- Specifies the background color based off the status of document queried. -->
<?php $backgroundColor = ($document == "NULL") ? "#777" : $backgroundColor?>
<div style="background-color:<?php echo $backgroundColor ?>;border-radius:15px;min-width:auto;max-width:<?php echo $widthPercentage ?>;<?php echo $center; ?>">
	<!-- If the document was found in the query, display a button that links to it. -->
	<?php if ($document != "NULL") { ?>
		<a style="color:<?php echo $textColor; ?>;text-align:center;font-weight:bold;" href="<?php echo $document;?>" target="_blank">
			<p style="padding:4px;">Latest <?php echo ($category != NULL) ? $category->cat_name : "Document";?></p>
		</a>
	<!-- If the document could not be found, generate a non-interactive paragraph that states that. -->
	<?php } else { ?>
		<p style="color:whitesmoke;text-align:center;font-weight:bold;padding:4px;">No Documents Under '<?php echo ($category->cat_name == NULL) ? $options['category'] : $category->cat_name ?>'</p>
	<?php } ?>
</div>

