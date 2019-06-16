<?php  
global $user; 
   foreach ($rows as $count => $row): 
    foreach ($row as $field => $content): 
      if($field == 'title'){ $title = $content;}
      if($field =='uid'){$uid = $content;}
      if($field == 'nid'){ $nid = $content;}
      if($field == 'group_group'){$group = $content;}
    endforeach; 
 endforeach;
 $account = user_load($user->uid);
  ?>
  <div class="group-title"><?php print $title; ?></div>
  <?php
  if ($user->uid == $uid) {
     echo "<div class='group-edit'>";
     print l('Edit', 'node/'.$nid.'/edit/nojs', array('attributes' => array('class' => 'ctools-use-modal btn btn-default process-edit')));
     print l('Delete', 'node/'.$nid.'/delete/nojs', array('attributes' => array('class' => 'ng-lightbox btn btn-default process-delete'), 'query' => array('destination' => 'process')));
     global $base_url;
     //echo "<a href='/node/".$nid."/delete/nojs' class='ctools-use-modal btn btn-default'>Delete</a>";
     echo "</div>";
  }
  else{
    if(og_is_member('node', $nid, 'user', $account)) {
      echo "<div class='group-edit'>";
      print l('Unsubscribe', 'group/node/'.$nid.'/unsubscribe', array('attributes' => array('class' => 'btn btn-default')));
      echo "</div>";
    } else{
      if(user_is_logged_in()){
        echo "<div class='group-edit'>";
          print l('Subscribe', 'group/node/'.$nid.'/subscribe/og_user_node', array('attributes' => array('class' => 'btn btn-default')));
        echo "</div>";
     }
    }
  }

  
