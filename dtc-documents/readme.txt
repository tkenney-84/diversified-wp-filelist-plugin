=== DTC Documents ===

Contributors: Diversified Technology Corp., wpyog, and Gagan Deep Singh
Tags: Document Management, Document, Simple Documents, File, File list, File management, PDF, Word, png, jpg, Image management, image
Requires at least: 4.0
Tested up to: 8.1
Stable tag: trunk
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

== Description ==

A plugin that allows you to easily upload files, create custom categories to store them in, display a customizable file list, generate text or a customizable button that
automatically links to the most recent file of all of them or of a specific category. This plugin also allows for the creation of shortcode in your Menu items!

Shortcode Options:

[dtc-doc-documents-list] – Allows the displaying of a fully dynamic and customizable list of currently uploaded files.

Parameter Name:         orderby
Parameter Desc:         Specifies the attribute to order the list by. Input is case-insensitive.
Parameter Options:      id, title, filetype, created, category, description, status
Parameter Default:      id
Parameter Example 1:    [dtc-doc-documents-list orderby=”category”]
Parameter Example 2:    [dtc-doc-documents-list orderby=”category,created”]

Parameter Name:         sortorder
Parameter Desc:         Specifies the direction to sort the list in. That being ‘asc’ for ascending order or ‘desc’ for descending order. Input is case-insensitive.
Parameter Options:      asc, desc
Parameter Default:      asc
Parameter Example:      [dtc-doc-documents-list sortorder=”desc”]

Parameter Name:         hidedisableddocs
Parameter Desc:         Specifies whether to list disabled documents or not. ‘true’ hides disabled documents, ‘false’ shows them. Input is case-insensitive.
Parameter Options:      true, false
Parameter Default:      true
Parameter Example:      [dtc-doc-documents-list hidedisableddocs=”false”]

Parameter Name:         hidedisabledcats
Parameter Desc:         Specifies whether to list documents categorized under disabled categories or not. ‘true’ hides documents that are part of disabled categories, ‘false’ shows them. Input is case-insensitive.
Parameter Options:      true, false
Parameter Default:      true
Parameter Example:      [dtc-doc-documents-list hidedisabledcats=”false”]

Parameter Name:         category
Parameter Desc:         Specifies which categories to display in this list. Input is case-insensitive.
Parameter Options:      all, Uncategorized, *ALL USER CREATED CATEGORIES*
Parameter Default:      all
Parameter Example 1:    [dtc-doc-documents-list category=”Forms”]
Parameter Example 2:    [dtc-doc-documents-list category=”Forms,Meeting Agenda”]

Parameter Name:         itemsatatime
Parameter Desc:         The number of items to leave height for. This number is multiplied by 50px (the height of one entry) and set as the maximum height of the list window.
Parameter Options:      *ALL X > 0*
Parameter Default:      10
Parameter Example:      [dtc-doc-documents-list itemsatatime=”10”]

[dtc-doc-recent-button] – Displays a button with the word ‘Latest’ followed by the name of the selected category, or ‘All’ depending on the input. Or, displays an error if no documents are found under the given category. Generates a formatted button with this text that then links to the most recent document of the given category.

Parameter Name:         category
Parameter Desc:         Specifies the category to use the most recent document from. Input is case-insensitive.
Parameter Options:      all, Uncategorized, *ALL USER CREATED CATEGORIES*
Parameter Default:      all
Parameter Example 1:    [dtc-doc-recent-button category=”Forms”]

Parameter Name:		    backgroundcolor
Parameter Desc:		    Sets the background color of the button.
Parameter Options:	    *Any valid rgb,rgba,hex, or pre-defined CSS color*
Parameter Default:	    #2eb3ed
Parameter Example:	    [dtc-doc-recent-button backgroundcolor="#ff0000"]

Parameter Name:		    textcolor
Parameter Desc:		    Sets the text color of the button.
Parameter Options:	    *Any valid rgb,rgba,hex, or pre-defined CSS color*
Parameter Default:	    whitesmoke
Parameter Example:	    [dtc-doc-recent-button backgroundcolor="rgba(100,100,100,0.5)"]

Parameter Name:		    widthpercentage
Parameter Desc:		    Sets the maximum width of the button in percent format.
Parameter Options:	    *0% to 100%*
Parameter Default:	    45%
Parameter Example:  	[dtc-doc-recent-button widthpercentage="30%"]

[dtc-doc-recent-redirect] – Displays the selected category name, and error, or ‘All’ depending on the input. Links this text to the most recent document from the specified category, or all categories if category is set to ‘all’.

Parameter Name:         category
Parameter Desc:         Specifies the category to use the most recent document from. Input is case-insensitive.
Parameter Options:      all, Uncategorized, *ALL USER SPECIFIED CATEGORIES*
Parameter Default:      all
Parameter Example 1:    [dtc-doc-recent-redirect category=”Forms”]

Parameter Name:         cssclasses
Parameter Desc:         Allows the application of CSS classes to this shortcode element.
Parameter Options:      *ANY VALID CUSTOM OR PRE-BUILT CSS CLASS*
Parameter Default:      *BLANK*
Parameter Example 1:    [dtc-doc-recent-redirect cssclasses=”cssclass1”]
Parameter Example 2:    [dtc-doc-recent-redirect cssclasses=”cssclass1 cssclass2 cssclass3”]

Parameter Name:         displaytext
Parameter Desc:         Allows the user to specify what text should be displayed for the redirect text. This defaults to the category name or an error.
Parameter Options:      *ANY VALID STRING*
Parameter Default:      *BLANK*
Parameter Example:   	[dtc-doc-recent-redirect displaytext=”testing one two three!”]

== Installation ==

1. Download the plugin, keep it in it's .zip file.
2. Access your wordpress sites 'Plugins' page, then select 'Add New', then select 'Upload Plugin', then select 'Browse', navigate to the .zip file, select it, and select 'Open'.
3. Select 'Install' then 'Activate'.
4. Upload documents to "DTC Documents".
5. Place shortcode (i.e. [dtc-doc-documents-list]) and use the settings to specify the list of documents you want listed.

== Plugin Features ==
* Upload any documents like pdf, word, jpg, png, etc.
* Display a dynamic and custom file list.
* Add / Edit custom categories for documents.
* Add a button or href text that automatically points to the latest file in a specified category or all categories.