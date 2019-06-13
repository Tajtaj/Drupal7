<?php

/**
 * @file
 * Template to display a view as a table.
 *
 * - $title : The title of this group of rows.  May be empty.
 * - $header: An array of header labels keyed by field id.
 * - $caption: The caption for this table. May be empty.
 * - $header_classes: An array of header classes keyed by field id.
 * - $fields: An array of CSS IDs to use for each field id.
 * - $classes: A class or classes to apply to the table, based on settings.
 * - $row_classes: An array of classes to apply to each row, indexed by row
 *   number. This matches the index in $rows.
 * - $rows: An array of row items. Each row is an array of content.
 *   $rows are keyed by row number, fields within rows are keyed by field ID.
 * - $field_classes: An array of classes to apply to each field, indexed by
 *   field id, then row number. This matches the index in $rows.
 * @ingroup views_templates
 */
 $field_title ="";
 $field_body ="";
 $node_id = "";
 $edit_link = "";
?>

    <?php foreach ($rows as $row_count => $row): 
		    foreach ($row as $field => $content): 
		      if($field == "title"):
		        $field_title = $content;
		      endif;
		      if($field == "body"):
		        $field_body = $content;
		      endif;
		      if($field == "nid"):
		        $node_id = $content;
		      endif;
			   if($field == "edit_node"):
		        $edit_link = $content;
		      endif;  
		    endforeach; ?>
            <div <?php print 'class= "views-row-outer ' . implode(" ", $row_classes[$row_count]) .'"';?>>
              <div class="views-field views-field-title-outer">
                <div class="field-content">
                  <?php print $field_title ?>
                </div>  
              </div>
              <div class="views-field views-field views-field-edit-node views-field-edit-link-outer">
                <div class="field-content">
                  <?php print $edit_link ?>
                </div>  
              </div>
              <div class="views-field views-field-body-outer">
                <div class="field-content">
                  <?php print $field_body ?>
                </div>  
              </div>
              <?php print views_embed_view('2nodes_slide_show', 'block_2',$node_id); ?>
            </div>
    
    <?php endforeach; ?>
  






