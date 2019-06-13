<?php
  $title = "";
  $blogImage = "";
  $description = "";
  $counter = 0;
  $blogLikes = 0;
  $author = "";
  $postDateFirst = "";
  $postDateSecond = "";
 foreach ($rows as $row_count => $row): ?>
   
  <div <?php if ($row_classes[$row_count]) { print 'class = " col-sm-12 ' . implode(' ', $row_classes[$row_count]) .'"';  } ?>>
  <?php foreach ($row as $field => $content):
          if($field == 'title') {$title = $content; }
          if($field == 'comment_count') {$comment = $content; }
          if($field == 'count') {$blogLikes = $content; }
          if($field == 'name') {$author = $content; }
          if($field == 'created') {$postDateFirst = $content; }
          if($field == 'created_1') {$postDateSecond = $content; }
        endforeach; 
       ?>
       
       <div class="title "><?php echo $title;?></div>
       <div class="author_time"><div class="col-sm-3 author"><?php echo $author; ?></div><div class="col-sm-9 time"><?php echo $postDateFirst." at ".$postDateSecond; ?></div></div>
       <div class="comment_like"><div class="col-sm-3 comment"><?php echo $comment; ?></div><div class="col-sm-9 like"><?php echo $blogLikes; ?></div></div>
       
  </div>
  
<?php endforeach; ?>

