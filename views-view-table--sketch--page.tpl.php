 <?php
 echo "testing";
 global $user;

   $graph_nid;
     
   foreach ($rows as $count => $row): 
    foreach ($row as $field => $content): 
 
      if($field=='nid')   {
       $graph_nid=$content;
      }
  endforeach; 

 endforeach;
$node = node_load($graph_nid);
//drupal_set_message('<pre>'.print_r($node,true).'</pre>');
$node = node_load($graph_nid);
$current_graph_data ='';
// //drupal_set_message('<pre>'.print_r($node,true).'</pre>');
if (!empty($node->field_graph)) {
    $current_graph_data  = $node->field_graph['und'][0]['value'];
}

drupal_add_css("sites/all/themes/news/css/joint.css", $type = 'file', $media = 'all', $preprocess = FALSE);
drupal_add_js("sites/all/themes/news/js/lodash.min.js", array('type' => 'file', 'scope' => 'header', 'weight' => 1), $preprocess = FALSE);
drupal_add_js("sites/all/themes/news/js/backbone-min.js", array('type' => 'file', 'scope' => 'header', 'weight' => 2), $preprocess = FALSE);
drupal_add_js("sites/all/themes/news/js/joint.js", array('type' => 'file', 'scope' => 'header', 'weight' => 3), $preprocess = FALSE);
?>
<style>
body {
}
#paper {
  width: 100%;
  overflow:hidden;
  position:relative;
}

/* port styling */
.available-magnet {
    fill: yellow;
}

/* element styling */
.available-cell rect {
    stroke-dasharray: 5, 2;
}
#stencil li {
  cursor: pointer;
}
.toolbar.jointjs-toolbar{
  padding: 15px;
  text-align: center;
}
.router-switch {
    width: 100px;
    margin: 4px;
    background: #68ddd5;
    color: #484e68;
    outline: none;
    font-size: 12px;
    border: none;
    padding: 4px;
    border-radius: 5px;
    cursor: pointer;
}
.router-switch:hover {
    background: #fdc685;
    color: #fff;
}
.marker-arrowheads {
    display: none;
}
.selected rect{
    stroke: black;
}
#jsonOutId{
  overflow-y: scroll;
  height:300px;
}
#myModal .modal-dialog{
  left:0%;
}
/*#myrect {
transform:scale(1.0);
-webkit-transform:scale(1.0);
}*/
</style>
<div class="container-fluid">
   <div class="row">
     <div class="toolbar jointjs-toolbar">
         <?php
            if ($user->uid == 0) {
            }else{
              echo '<button id="btn-save" class="btn btn-default">Save</button>';
            }
         ?>
        <button id="btn-clear" class="btn btn-default">Clear</button>
        <button id="btn-group" class="btn btn-default">Group</button>
        <button id="btn-ungroup" class="btn btn-default">UnGroup</button>
        <button id="btn-export" class="btn btn-default">Export</button>
     </div>
        
   </div>
    <div class="row">
      <div class="col-lg-2">
        <h3>Operators</h3>
        <p>Drag operators into Process section.</p>
        <div id="stencil">
        <ul><li id="source">Source</li><li id="sort">Sort</li><li id="filter">Filter</li><li id="joins">Joins</li><li id="decisionTree">Decision Tree</li></ul>
        </div>
            
      </div>
      <div class="col-lg-8">
         <!-- Modal -->
         <div class="modal fade" id="myModal" role="dialog">
              <div class="modal-dialog">
              
                <!-- Modal content-->
                <div class="modal-content">
                  <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">JSON</h4>
                  </div>
                  <div class="modal-body">
                    <div id="jsonOutId"></div>
                  </div>
                  <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                  </div>
                </div>
                
              </div>
            </div>
        <h3>Process</h3>
        <div id="paper" style="height:500px;border:1px solid #dddddd;" class="col-lg-8">
        </div>
      </div>
      <div id="parameters" class="col-lg-2">
        <h3>Parameters</h3>
        <div class="inspector-container form-group">
          <form>
            <label> File path</label>
            <input type="text" name="fileDataField" class="form-control" id="fileDataField">
            <input type="hidden" name="formId" id="formCellId">
          <input type="button" style='margin-top: 5px;' class='btn btn-default' value="Modify" onClick="modifyPath()">
          </form>
          <div class="importjsondiv form-group">
          <label>Import JSON</label>
          <textarea id="txt" class="form-control"></textarea>
          <button id="btn-import" style='margin-top: 5px;' class="btn btn-default">Import</button>
          </div>
        </div>        
      </div>
    </div>
</div>
<script type="text/javascript">
  //Function start
jQuery(function () {
  var cntrlIsPressed = false;
    jQuery(document).keydown(function(event){
    if(event.which=="17")
        cntrlIsPressed = true;    
    });

  jQuery(document).keyup(function(){
      cntrlIsPressed = false;
  });
  var selectedCells = [];
// Define Custom Model with background image and ports
// Canvas where shape are dropped
var graph = new joint.dia.Graph,
  paper = new joint.dia.Paper({
    el: jQuery('#paper'),
    model: graph,
    gridSize: 8,
    drawGrid:true,
    linkPinning: false,
    defaultLink: new joint.dia.Link({
      router: { name: 'manhattan' },
      connector: { name: 'rounded' },
      attrs: {
        '.connection': {
          stroke: '#b9c0c0',
          'stroke-width': 2
      },
    },
    }),
    validateConnection: function(cellViewS, magnetS, cellViewT, magnetT, end, linkView) {
    // Prevent linking from input ports.
    if (magnetS && magnetS.getAttribute('port-group') === 'in') return false;
      // Prevent linking from output ports to input ports within one element.
    if (cellViewS === cellViewT) return false;
    // Prevent linking to input ports.
    return magnetT && magnetT.getAttribute('port-group') === 'in';
    },
    validateMagnet: function(cellView, magnet) {
        // Note that this is the default behaviour. Just showing it here for reference.
        // Disable linking interaction for magnets marked as passive (see below `.inPorts circle`).
        return magnet.getAttribute('magnet') !== 'passive';
    },
    //Prevent adding vertex by clicking on link
    interactive: function(cellView) {
        if (cellView.model instanceof joint.dia.Link) {
            // Disable the default vertex add functionality on pointerdown.
            return { vertexAdd: false };
        }
        return true;
    },
    snapLinks: { radius: 75 },
    // Enable marking available cells & magnets
    markAvailable: true
  });


////customshape
joint.shapes.devs.MyImageModel = joint.shapes.devs.Model.extend({

  markup: '<g class="rotatable"><g class="scalable" id="myrect"><rect/><image/></g><text/></g>',

  defaults: joint.util.deepSupplement({

    type: 'devs.MyImageModel',
    size: {width: 100, height: 60},
    attrs: {
        rect: { fill: '#e8f1f0',stroke: '#a2a8a8',width: 100, height: 60},
        image: {width: 50, height: 20 }
    }
  }, joint.shapes.devs.Model.prototype.defaults)
});

joint.shapes.devs.MyImageModelView = joint.shapes.devs.ModelView;

// Canvas from which you take shapes
jQuery('#stencil li').mousedown(function(e) {
  drawShape(e, jQuery(this).attr("id"));
});


function drawShape(ev, id) {
  var dLabel, dInPorts, dOutPorts;
  switch(id) {
    case "source":
     dLabel = "Source";
     dInPorts = [];
     dOutPorts = ['out'];
     fillClr = '#e3eeee';
     imgPath = '/sites/all/themes/news/images/source.png';
     custompath ='http://examples.com';
    break;
    case "sort":
     dLabel = "Sort";
     dInPorts = ['in'];
     dOutPorts = ['exa', 'ori'];
     fillClr = '#e3eeee';
     imgPath = '/sites/all/themes/news/images/sort.png';
     custompath ='http://examples.com';
    break;
    case "filter":
     dLabel = "Filter";
     dInPorts = ['in'];
     dOutPorts = ['exa', 'ori', 'unm'];
     fillClr = '#e3eeee';     
     imgPath = '/sites/all/themes/news/images/filter.png';
     custompath ='http://examples.com';
     break;
    case "joins":
     dLabel = "Joins";
     dInPorts = ['lef','rig'];
     dOutPorts = ['joi'];
     fillClr = '#e3eeee';     
     imgPath = '/sites/all/themes/news/images/joins.png';
     custompath ='http://examples.com';
    break;
     case "decisionTree":
     dLabel = "DecisionTree";
     dInPorts = ['tra'];
     dOutPorts = ['mod','exa'];
     fillClr = '#e3eeee';     
     imgPath = '/sites/all/themes/news/images/decTree.png';
     custompath ='http://examples.com';
    break;
  }
  
  jQuery('body').append('<div id="flyPaper" style="position:fixed;z-index:100;opacity:.7;pointer-event:none;"></div>');
  var flyGraph = new joint.dia.Graph,
    flyPaper = new joint.dia.Paper({
      el: jQuery('#flyPaper'),
      model: flyGraph,
      interactive: false
    }),
  flyShape = new joint.shapes.devs.MyImageModel({
  position: { x: 10, y: 10 },
    size: {width: 100, height: 60},
    inPorts: dInPorts,
    outPorts: dOutPorts,
    custompath:custompath,
    ports: {
        groups: {
            'in': {
                attrs: {
                    '.port-body': {
                        fill: '#ccd6d6',
                        magnet: 'passive',
                        r:5,
                        stroke:'#a2a8a8'
                      },
                      '.port-label':{
                        'font-size': 9 
                      }
                }
            },
            'out': {
                attrs: {
                    '.port-body': {
                        fill: '#ccd6d6',
                        r:5,
                        stroke:'#a2a8a8'
                    },
                    '.port-label':{
                      'font-size': 9 
                    },
                    require:true
                }
            }
        }
    },
    attrs: {
        '.label': { text: dLabel , 'ref-x': .5, 'ref-y': .2},
        rect: { fill: '#e8f1f0',stroke: '#a2a8a8',width: 100, height: 60},
        image: { 'xlink:href': imgPath,'ref-x': 25, 'ref-y': 20,ref: 'rect',width: 50, height: 20 }
    }
}),
    pos = {x: ev.pageX, y: ev.pageY},
    offset = {
      x: ev.offsetX,
      y: ev.offsetY
    };

  flyShape.position(0, 0);
  flyGraph.addCell(flyShape);
  jQuery("#flyPaper").offset({
    left: ev.pageX - offset.x,
    top: ev.pageY - offset.y
  });
  jQuery('body').on('mousemove.fly', function(e) {
    jQuery("#flyPaper").offset({
      left: e.pageX - offset.x,
      top: e.pageY - offset.y
    });
  });
  jQuery('body').on('mouseup.fly', function(e) {
    var x = e.pageX,
        y = e.pageY,
      target = paper.$el.offset();
    
    // Dropped over paper ?
    if (x > target.left && x < target.left + paper.$el.width() && y > target.top && y < target.top + paper.$el.height()) {
      var s = flyShape.clone();
      s.position(x - target.left - offset.x, y - target.top - offset.y);
      graph.addCell(s);
    }
    jQuery('body').off('mousemove.fly').off('mouseup.fly');
    flyShape.remove();
    jQuery('#flyPaper').remove();
  });
    
}


//cellview click
paper.on('cell:pointerup', function(cellView, evt) {
  //cellView.highlight();  
  if(cellView.model.attributes.type != 'link'){
  document.getElementById("formCellId").value = cellView.model.attributes.id;
  document.getElementById("fileDataField").value = cellView.model.attributes.custompath;
  }

});
//HERE IS WHAT YOU NEED
//CLICK ON A CELL TO SELECT IT    
  paper.on('cell:pointerdown', function(cellView, evt, x, y) {
    if(cntrlIsPressed) {
      //if (selectedCells) V(selectedCell.el).removeClass('selected');
      V(cellView.el).toggleClass('selected');
      var test = cellView.el;
      var ew = test.getAttribute("class");
      
      if(~ew.indexOf("selected")){
        selectedCells.push(graph.getCell(cellView.model.id));
      }
      else{
        selectedCells = selectedCells.filter(function(el) {
          return el.id !== cellView.model.id;
        });
      }
    }
  });

jQuery(document).keydown(function(event){
  //Delete cell
  if(event.which=="46")
    _.each(selectedCells, function(selectedCell) {
        var currentParentID = selectedCell.get('parent');
    
        selectedCell.remove();
        if(currentParentID){
          var currentParentCell = graph.getCell(currentParentID);
          var numberOfChilds = currentParentCell.getEmbeddedCells({deep: true});
          console.log(numberOfChilds);
          if(numberOfChilds === undefined || numberOfChilds.length == 0) {
            currentParentCell.remove();
          }
        }
        selectedCells = [];
  });
    //copy cell
  if(event.which=="67")
    _.each(selectedCells, function(selectedCell) {
      var clone = selectedCell.clone();
      graph.addCell(clone);
         
  }) 
});
//clear grpah
jQuery('#btn-clear').on('click', _.bind(graph.clear, graph));
//Grouping / Ungrouping
var rect = joint.shapes.basic.Rect;
//AND BRING IT TO FRONT VIA BUTTON CLICK
jQuery('#btn-group').click(function () {
  if (selectedCells === undefined || selectedCells.length == 0) return;
    var parent = element(rect);
    _.each(selectedCells, function(selectedCell) {
        parent.embed(selectedCell);
        parent.fitEmbeds({deep:true,
          padding:10});
        parent.toFront({ deep: true });
        V(paper.findViewByModel(selectedCell).el).removeClass('selected');
        selectedCells=[];
    });
});
jQuery('#btn-ungroup').click(function () {
   if (selectedCells === undefined || selectedCells.length == 0) return;
   
   _.each(selectedCells, function(selectedCell) {

      var numberOfChilds = selectedCell.getEmbeddedCells({deep: true});
      if(numberOfChilds.length > 0){
        _.each(numberOfChilds, function(children) {
          selectedCell.unembed(children);
        });
        selectedCell.remove();
        selectedCells = selectedCells.filter(function(el) {
          return el.id !== selectedCell.id;
        });
      }
    });
});
//Export
jQuery('#btn-export').click(function () {
  jQuery("#myModal").modal("show");
  var jsonOut =JSON.stringify(graph);
  jQuery('#jsonOutId').empty();
  jQuery('#jsonOutId').append(jsonOut);
  //console.log(jsonOut)
});
jQuery('#btn-import').click(function () {
  var rel=document.getElementById("txt").value;
  graph.fromJSON(JSON.parse(rel));
});

var element = function(elm, x, y, label) {
  var cell = new elm({ 
    position: { x: x, y: y },
    size: { width: 100, height: 40 },
    name:'parentCellClass',
    attrs: {
          text: {style: {fill: 'black','font-weight': 'bold'},text: 'Group', 'ref-y': -10, 'font-size': 10},
          rect: {
              'stroke-width': '2',
              'stroke-opacity': .7,
              stroke: 'grey',
              rx: 2,
              ry: 2,
              fill: 'transparent',
              'fill-opacity': .5
          },
    }
  });
  graph.addCell(cell);
  return cell;
};
//modify path
function modifyPath(){
 var hiddenId = document.getElementById("formCellId").value;
 var getCellById = graph.getCell(hiddenId);
 var updateCustomPath = document.getElementById("fileDataField").value;
 var cellView = paper.findViewByModel(getCellById);
 cellView.model.attributes.custompath = updateCustomPath;
}
graph.on('change:source change:target', function(link) {
    var sourcePort = link.get('source');
    var sourcePortId = sourcePort.id;
    var targetId = link.get('target').id;
    var getSourceCell = graph.getCell(sourcePortId);
    getSourceCell.prop('ports/groups/out/attrs/.port-body/fill', 'yellow');
    console.log('need connection');
    if(targetId){
    getSourceCell.prop('ports/groups/out/attrs/.port-body/fill', '#ccd6d6');
    console.log('connected');
    }
});
//restricting child elements in group
graph.on('change:position', function(cell) {

  var parentId = cell.get('parent');
  if (!parentId) return;
  var parent = graph.getCell(parentId);
  var parentBbox = parent.getBBox();
  var cellBbox = cell.getBBox();
  if (parentBbox.containsPoint(cellBbox.origin()) &&
      parentBbox.containsPoint(cellBbox.topRight()) &&
      parentBbox.containsPoint(cellBbox.corner()) &&
      parentBbox.containsPoint(cellBbox.bottomLeft())) {
      return;
  }
  // Revert the child position.
  cell.set('position', cell.previous('position'));
});
//Save graph
jQuery('#btn-save').click(function () {

   jsonSave = JSON.stringify(graph);
   var graph_nid = '<?php echo $graph_nid; ?>';
   //console.log(graph_nid);
   save_graph(graph_nid, jsonSave);
  // jQuery('#jsonOutId').empty();
  // jQuery('#jsonOutId').append(jsonOut);
  //console.log(jsonOut)
});

//current graph data
var current_graph_data = '<?php echo $current_graph_data;?>';
if(current_graph_data){
  console.log(current_graph_data);
  graph.fromJSON(JSON.parse(current_graph_data));
}

//Ajax request for node save
 function save_graph(graph_nid, jsonSave) {

  jQuery.ajax({
        url : '/joint-save',
        data : {
          graph_nid : graph_nid,
          jsonSave : jsonSave
        },
        beforeSend : function() {
          //$("#loadingimg").show();
        },
        type : 'post',
        success : function(data) {
          // this is executed when ajax call finished well
          //alert("Data saved successfully.");
          console.log(data);
          //$("#loadingimg").hide();
          //alert("Data is: "+data);
        },
        error : function(xhr, status, error) {
          // executed if something went wrong during call
          if (xhr.status > 0)
            alert('got error: ' + status); // status 0 - when
          // load is
          // interrupted
        }
      });
};


});
</script>