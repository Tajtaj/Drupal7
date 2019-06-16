<?php
$get_nid = arg(1);
$curr_node = node_load($get_nid);
global $user; 
   foreach ($rows as $count => $row): 
    foreach ($row as $field => $content): 
      if($field == 'title'){ $title = $content;}
      if($field =='uid'){$uid = $content;}
      if($field == 'nid'){ $nid = $content;}
      if($field == 'nid_1'){ $g_nid = $content;}
    endforeach; 
 endforeach;
 $curr_nid = arg(1);
    if ($user->uid == $uid) {
     
     echo "<div id='node-edit'>";
     if($curr_node->type == "sketch"){
      ?>
      <span class="graph-btns">
        <img class="graphLoader-save" src="http://localhost/wiki3/sites/default/files/graphloader.gif"><button id="btn-run" class="btn btn-default">Run</button>  
        <button id="btn-save" class="btn btn-default">Save</button>
        <button id="btn-clear" class="btn btn-default">Clear</button>
        <button id="btn-group" class="btn btn-default">Group</button>
        <button id="btn-ungroup" class="btn btn-default">UnGroup</button>
        <button id="btn-import" class="btn btn-default">Import</button>
        <button id="btn-export" class="btn btn-default">Export</button>
      </span>
      <?php
      }
      print l('Add Operator', 'node/add/operator/nojs', array('attributes' => array('class' => 'ctools-use-modal btn btn-default add-operator')));
      print l('Add Source', 'node/add/source/nojs', array('attributes' => array('class' => 'ctools-use-modal btn btn-default add-source')));
      print l('Edit', 'node/'.$nid.'/edit/nojs', array('attributes' => array('class' => 'ctools-use-modal btn btn-default process-edit')));
      print l('Delete', 'node/'.$nid.'/delete/nojs', array('attributes' => array('class' => 'ng-lightbox btn btn-default process-delete'), 'query' => array('destination' => 'node/'.$g_nid)));
      echo "</div>";
   } ?>
