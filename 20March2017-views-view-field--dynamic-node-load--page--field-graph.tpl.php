<?php
$graph_nid = $row->nid;
$node = node_load($graph_nid);
if($node->type == "sketch") {
$current_graph_data ='';
if (!empty($node->field_graph)) {
    $current_graph_data  = $node->field_graph['und'][0]['value'];
}
drupal_add_css("sites/all/themes/news/css/selection.css", $type = 'file', $media = 'all', $preprocess = FALSE);
drupal_add_css("sites/all/themes/news/css/joint.css", $type = 'file', $media = 'all', $preprocess = FALSE);
drupal_add_css("sites/all/themes/news/css/joint.ui.navigator.min.css", $type = 'file', $media = 'all', $preprocess = FALSE);
drupal_add_css("sites/all/themes/news/css/jqx.base.css", $type = 'file', $media = 'all', $preprocess = FALSE);
drupal_add_css("sites/all/themes/news/css/style.css", $type = 'file', $media = 'all', $preprocess = FALSE);
drupal_add_css("sites/all/themes/news/css/datatables.css", $type = 'file', $media = 'all');
drupal_add_js("sites/all/themes/news/js/lodash.min.js", array('type' => 'file', 'scope' => 'header', 'weight' => 1), $preprocess = FALSE);
drupal_add_js("sites/all/themes/news/js/backbone-min.js", array('type' => 'file', 'scope' => 'header', 'weight' => 2), $preprocess = FALSE);
drupal_add_js("sites/all/themes/news/js/joint.js", array('type' => 'file', 'scope' => 'header', 'weight' => 3), $preprocess = FALSE);
drupal_add_js("sites/all/themes/news/js/joint.ui.selectionView.js", array('type' => 'file', 'scope' => 'header', 'weight' => 4), $preprocess = FALSE);
drupal_add_js("sites/all/themes/news/js/joint.ui.paperScroller.min.js", array('type' => 'file', 'scope' => 'header', 'weight' => 5), $preprocess = FALSE);
drupal_add_js("sites/all/themes/news/js/joint.ui.navigator.min.js", array('type' => 'file', 'scope' => 'header', 'weight' => 6), $preprocess = FALSE);
drupal_add_js("sites/all/themes/news/js/jqx-all.js", array('type' => 'file', 'scope' => 'header', 'weight' => 7), $preprocess = FALSE);
drupal_add_js("sites/all/themes/news/js/jquery.dataTables.js", array('type' => 'file'));
drupal_add_js("sites/all/themes/news/js/dataTables.bootstrap.js", array('type' => 'file'));

?>
<div class="container-fluid">
  <div class="row">
    <div id="operator-rel" class="closed">
      <div id="operator-area">
        <div id="search-box">
          <form>
            <div class="form-group">
              <input type="text" name="searchOperator" class="form-control" id="searchOperator" placeholder="Search operator" value="">
            </div>
          </form>
        </div>
        <div id="stencil">
          <?php
          //Making dynamic pallet and listing operators
            $main_array = array();
            $op_array = array();
            $opId = array();
            $field_input_port = '';
            $field_output_port = '';
            $query = new EntityFieldQuery();
              $query->entityCondition('entity_type', 'node')
                ->entityCondition('bundle', 'pallet')
                ->propertyCondition('status', 1);
              $result = $query->execute();

              if (!empty($result['node'])) {
                $nids = array_keys($result['node']);  
               foreach ($nids as $nid) {
                  $node_pallet = node_load($nid, NULL, TRUE);
                  $pallet_title = $node_pallet->title;
                  $pallet_nid = $node_pallet->nid;
                  $operator_nid = $node_pallet->field_operator['und'];
                  echo '<div class="pallet"><div class="panel-group">
                          <div class="panel panel-default">
                            <div class="panel-heading">
                              <h4 class="panel-title">
                                <a data-toggle="collapse" href="#'.$pallet_title.'">'.$pallet_title.'</a>
                              </h4>
                            </div>
                            <div id="'.$pallet_title.'" class="panel-collapse collapse">
                              <ul class="list-group">';
                 $op_array2 = array();
                 $op_i = 0;
                 foreach ($operator_nid as $value) {
                      $op_id =  $value['target_id'];
                      $op_node = node_load($op_id);
                      $op_title = $op_node->title;
                      $op_nid = $op_node->nid;
                      $fill_color = $op_node->field_fill_color['und'][0]['rgb'];
                      if(!empty($op_node->field_input_port)) {
                        $field_input_port = $op_node->field_input_port['und'][0]['value'];
                      }
                      elseif(empty($op_node->field_input_port)) {
                        $field_input_port = '';
                      }
                      if(!empty($op_node->field_output_port)) {
                        $field_output_port = $op_node->field_output_port['und'][0]['value'];
                      }
                      
                      elseif(empty($op_node->field_output_port)) {
                        $field_output_port = '';
                      }
                      if(!empty($op_node->field_operator_icon)){
                        $field_operator_icon = $op_node->field_operator_icon['und'][0]['filename'];

                      }
                      elseif(empty($op_node->field_operator_icon)){
                        $field_operator_icon = 'default.png';
                      }
                      echo "<li style='-moz-user-select: none; -webkit-user-select: none; -ms-user-select:none; user-select:none;-o-user-select:none;' 
                          unselectable='on' onselectstart='return false;'onmousedown='return false;'
                          class='list-group-item filterdrag' id=$op_nid value=$op_nid>".$op_title."</li>";

                      $op_array[$op_nid] 
                             = array(
                                "dLabel" => $op_title,
                                "dInPorts" => $field_input_port,
                                "dOutPorts" => $field_output_port,
                                "imagePath" => $field_operator_icon,
                                "fillClr" => $fill_color
                      );

                      $op_array2[$op_i] = array('title' => $op_title, 'op_nid' => $op_nid, 'pallet' => $pallet_title);
                      $op_i++;
                 }
                 echo "</ul></div></div></div></div>";
                 $main_array[] = array(
                          "title" => $pallet_title,
                          "operators" => $op_array2
                          );    
                }

              }

          ?>
        </div>
      </div>
      <div class="user-nav-slide"></div> 
      <div id="operator-main-div" class="">
		<div id="exTab1" class="container ">	
			<ul  class="nav nav-tabs">
			  <li>
			    <a href="#curr-op-parameters" data-toggle="tab">Parameters</a>
			  </li>
			  <li>
			    <a  href="#curr-op-result" data-toggle="tab">Result</a>
			  </li>
			</ul>
		 <div class="tab-content clearfix">
		    <div class="tab-pane" id="curr-op-parameters">
		    <!-- parameter area starts here-->
		          
        <div id="parameter-area">

          <div id="parameters-box">
            <form>
              <div class="form-group">
                <label>Title</label>
                <input type="text" class="form-control" name="titleOperator" id="titleOperator" placeholder="Operator title">
              </div>
              <div class="form-group">
                <label>Fill color</label>
                <input type="text" class="form-control" name="fillColor" id="fillColor" placeholder="Change color ie #bababa">
              </div>
              <div class="form-group" id="paraFilePath" style="display:none;">
                <label>Select Source</label>  
                <?php
          $query_source = new EntityFieldQuery();

          $query_source->entityCondition('entity_type', 'node')
            ->entityCondition('bundle', 'source')
            ->propertyCondition('status', 1);

          $result_source = $query_source->execute();

          if (!empty($result_source['node'])) {
            $source_nids = array_keys($result_source['node']);
           $source_options ='<option value="" selected>--Select Source File--</option>';
           foreach ($source_nids as $source_nid) {
              $source_node = node_load($source_nid, NULL, TRUE);
              $source_title = $source_node->title;
              $source_filename = $source_node->field_source_file['und'][0]['filename'];
              $source_options .= "<option value='".$source_filename."'>".$source_title."</option>";
              //drupal_set_message("<pre>".print_r($source_filename,true)."</pre>");
            }
          }
                ?>
              <select class="form-control" id="selectedFilename" >
                <?php echo $source_options;?>
                </select>
                <input type="button" style='margin-top: 5px; float:right;' class='btn btn-default' value="Modify" id="btn-modify">
                <img class="graphLoader-modify" src="/sites/default/files/graphloader.gif">
              </div>
              <div class="form-group" id="paraAttrName" style="display:none;">
                <button type="button" style='margin-top: 5px; float:right;' class="btn btn-default" data-toggle="modal" id="btn-sort-form">Sort data</button>
              </div>
              <div class="form-group" id="filterDiv" style="display:none;">
                <button type="button" style='margin-top: 5px; float:right;' class="btn btn-default" data-toggle="modal" id="btn-multifilter-form">Add Filters</button>
              </div>
              <div class="form-group" id="decisionTreeDiv" style="display:none;">
                <label>Criterion</label>
                <select class="form-control" id="">
                   <option>gain_ratio</option>
                   <option>information_gain</option>
                </select>
                <label>Maximal depth</label>
                <input type="text" class="form-control" name="" id="">
                <input type="checkbox" class="parameter-checkbox" name="" id="">
                <label>Apply Pruning</label><br>
                <label>Confidence</label>
                <input type="text" class="form-control" name="" id="">
                <input type="checkbox" class="parameter-checkbox" name="" id="">
                <label>Apply rePruning</label><br>
                <label>Minimal gain</label>
                <input type="text" class="form-control" name="" id="">
                <label>Minimal leaf size</label>
                <input type="text" class="form-control" name="" id="">
              </div>
              <div class="form-group" id="joinDiv" style="display:none;">
                <label>Join Type</label>
                <select class="form-control" id="joinTypeId" name="joinTypeId">
                   <option value="inner">inner</option>
                   <option value="full">outer</option>
                   <option value="left">left</option>
                   <option value="right">right</option>
                </select>
                <div class="form-group">
                <input type="checkbox" class="parameter-checkbox" name="" id="">
                <label>Use id attribute as key</label>
                <input type="button" style="margin-top: 5px; float:right;" class="btn btn-default" value="Modify" id="joinModify">
                <img class="graphLoader-modify" src="/sites/default/files/graphloader.gif">
                </div>
              </div>
              <input type="hidden" class="form-control" name="param-id" id="param-id" value="">
            </form>
          </div>
        <div id="parameters">
          <div>
          <p>Drag an operator from Operator's panel into Paper and click on it to see its parameters.</p>
          </div>
        </div>
        </div>
      <!--end here -->
			</div>
			<div class="tab-pane active" id="curr-op-result">
		       <div id="result-op-data">
                  <table id="currentResultTable" class="display table table-striped">
                    <thead>
                    </thead>
                    <tbody>                       
                    </tbody>
                </table>
                  </div>
			</div>
		 </div>
		</div>
  </div>
  </div>
  </div>
  <div class="row">
    <div>
    <!-- Modal export-->
      <div class="modal fade" id="expModal" role="dialog">
        <div class="modal-dialog">
        <!-- Modal content-->
          <div class="modal-content">
            <div class="modal-header">
              <h4 class="modal-title">Export JSON</h4>
            </div>
            <div class="modal-body">
              <div id="jsonOutId">
                <textarea id="expTxt" class="form-control"></textarea>
              </div>
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-default" id="copyToClip">Copy to clipboard</button>
            </div>
          </div>
              
        </div>
      </div>
    <!-- Modal Import-->
      <div class="modal fade" id="impModal" role="dialog">
        <div class="modal-dialog">
        <!-- Modal content-->
          <div class="modal-content">
            <div class="modal-header">
              <h4 class="modal-title">Import JSON</h4>
            </div>
            <div class="modal-body">
              <div id="jsonInId">
                <textarea id="impTxt" class="form-control"></textarea>
                <button id="btn-import-json" class="btn btn-default">Import</button>
              </div>
            </div>
          </div>
              
        </div>
      </div>
    <!-- Modal Save Graph-->
      <div class="modal fade" id="msgModal" role="dialog">
        <div class="modal-dialog">
        <!-- Modal content-->
          <div class="modal-content">
            <div class="modal-header">
              <h4 class="modal-title">Message!</h4>
            </div>
            <div class="modal-body">
              <div id="msgSaveId">
              <h6>Sketch saved successfully.</h6>
              </div>
            </div>
          </div>
              
        </div>
      </div>
        <div class="modal fade" id="csvModal" role="dialog">
            <div class="modal-dialog">
              <!-- Modal content-->
              <div class="modal-content">
                <div class="modal-body">
                  <div id="csvSaveId">
                  <table id="csvTableId" class="display table table-striped">
                    <thead>
                    </thead>
                    <tbody>                       
                    </tbody>
                </table>
                  </div>
                </div>
              </div>
            </div>
        </div>
        <div class="modal fade" id="sourceNotConnectedModal" role="dialog">
        <!-- Source not connected to ouptut operator-->
            <div class="modal-dialog">
              <div class="modal-content">
                <div class="modal-header">
                  <h4 class="modal-title">Message!</h4>
                </div>               
                <div class="modal-body">
                  <div id="sourceNotConnectedModalDiv">
                    <p>Please Connect to Output</p>
                  </div>
                </div>
              </div>
            </div>
        </div>
                <div class="modal fade" id="outNotConnectedModal" role="dialog">
        <!-- Output not connected to source operator-->
            <div class="modal-dialog">
              <div class="modal-content">
                <div class="modal-header">
                  <h4 class="modal-title">Message!</h4>
                </div>               
                <div class="modal-body">
                  <div id="outNotConnectedModalDiv">
                    <p>Please Connect to Source</p>
                  </div>
                </div>
              </div>
            </div>
        </div>
        <div class="modal fade" id="sourceConnectedFileAttachModal" role="dialog">
        <!-- Attach file to source-->
            <div class="modal-dialog">
              <div class="modal-content">
                <div class="modal-header">
                  <h4 class="modal-title">Message!</h4>
                </div>               
                <div class="modal-body">
                  <div id="sourceConnectedFileAttachModalDiv">
                    <p>Please click on Source Operator, select source file from dropdown and click modify to attach file to Source Operator.</p>
                  </div>
                </div>
              </div>
            </div>
        </div>
        <div class="modal fade" id="noRecodFoundModal" role="dialog">
        <!-- Source connected to filter but not to ouptut operator-->
            <div class="modal-dialog">
              <div class="modal-content">
                <div class="modal-header">
                  <h4 class="modal-title">Message!</h4>
                </div>               
                <div class="modal-body">
                  <div id="noRecodFoundModalDiv">
                    <p>No record found.</p>
                  </div>
                </div>
              </div>
            </div>
        </div>
        <!-- Multiple filter Modal -->
      <div class="modal fade" id="multifilterModal" role="dialog">
        <div class="modal-dialog">
        <!-- Modal content-->
          <div class="modal-content" style="width:670px">
            <div class="modal-header">
              <h4 class="modal-title">Create Filters</h4>
            </div>
            <div class="modal-body">
               <div class="filterForm">
                 <input type="hidden" name="global_filter_count" id="global_filter_count" value="1">
             <div id="data-view-filters">
                <div id="global_filter1" class="form-group form-inline filterDiv">
                  <select class="form-control filterColoumSel" id="global_filter_sel1" name="global_filter_sel1"> 
                  </select>
                <select class="form-control" id="global_filter_cond_sel1" name="global_filter_cond_sel1">
                  <option value="" selected>--Select operator--</option>
                  <option value="EQUAL">is equal to</option>
                  <option value="NOTEQUAL">is not equal to</option>
                  <option value="ISIN">is in</option>
                  <option value="ISNOTIN">is not in</option>
                  <option value="LESSTHAN">is less than</option>
                  <option value="GREATERTHAN">is greater than</option>
                  <option value="STARTWITH">start with</option>
                  <option value="ENDWITH">end with</option>
                </select>
                  <input type="text" class="form-control" id="global_filter_cond_val1" name="global_filter_cond_val1" placeholder="Value">
                </div>
             </div>
             <div class="form-group add-more-cont">
               <a id="global_addb" class="add-more" name="global_addmore" type="button">Add Condition</a>
             </div>
               </div>
            </div>
          <div class="modal-footer">
            <input id="submitFilters" class="btn btn-default" name="viewSubmit" value="Submit" type="submit" data-dismiss="modal">
            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
          </div>
          </div>
        </div>
      </div>
            <!-- Sorting data Modal-->
    <div class="modal fade" id="sortModal" role="dialog">
      <div class="modal-dialog">
          <!-- Modal content-->
        <div class="modal-content" style="width:670px">
          <div class="modal-header">
            <h4 class="modal-title">Sort Data</h4>
          </div>
          <div class="modal-body">
            <div id="sortForm" class="col-sm-12">
              <div class="col-sm-6">
              <select class="form-control" id="sortAttributes" name="sortAttributes">  
              </select>
              </div>
              <div class="col-sm-6">                
                <select class="form-control col-sm-6" id="sortType" name="sortType">
                  <option value="ASC">Ascending</option>
                  <option value="DESC">Descending</option>
                </select>
            </div>
            </div>
          </div>
          <div class="modal-footer">
            <input id="submitSorting" class="btn btn-default" name="submitSorting" value="Submit" type="submit" data-dismiss="modal">
            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
          </div>
        </div>
      </div>
    </div>
       <!-- Modal for Date format-->
      <div class="modal fade" id="dateFormat" role="dialog">
        <div class="modal-dialog">
        <!-- Modal content-->
          <div class="modal-content">
            <div class="modal-header">
              <h4 class="modal-title">Error</h4>
            </div>
            <div class="modal-body">
              <p>To sort a date, format should be dd/mm/yyyy (02/28/2017)<p>
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
          </div>
              
        </div>
      </div>



  <div>
    <input type="hidden" name="savedgraph" id="savedgraph"></div>                  
      <div id="paper">
      </div>
      <div id="navigator-container">
        <div id="navigator">
        </div>
      </div>
    </div>
  </div>
</div>
 <script type="text/javascript">
  //Function start
jQuery(function () {
//code for serach operator
jQuery('input[name="searchOperator"]').on('keyup', function() {

    var searchVal = jQuery(this).val();
    var mainArray = <?php echo (json_encode($main_array,true));?>;
    var myNewArray = [];
    var MyObj = {
      filter : function() {
          var tmpPalltets = [];
          var pallet = {};
          var operator = {};
          for(var i=0;i<this.pallets.length;i++) {
              pallet = this.pallets[i];
              if (pallet.title.search(new RegExp(searchVal, "i")) != -1) {
                  tmpPalltets.push(pallet);
                  continue;
              } else {
                for(var j=0;j<pallet.operators.length;j++) {
                  operator = pallet.operators[j];
                  if (operator.title.search(new RegExp(searchVal, "i")) != -1) {
                   tmpPalltets.push({"title": pallet.title, "operators": [{"op_nid":operator.op_nid, "pallet": operator.pallet, "title":operator.title}]});
                   break;
                  }
                }
              }
          }
          this.pallets = tmpPalltets;
        return this;
      },
      pallets: mainArray
  };
MyObj.filter();
var resultHtml = '';
for(var i=0;i<MyObj.pallets.length;i++) {
   resultHtml += '<div id="pallet"><div class="panel-group"><div class="panel panel-default"><div class="panel-heading"><h4 class="panel-title"><a data-toggle="collapse" href="#'+MyObj.pallets[i].title+'" class="bootstrap-collapse-processed" aria-expanded="true">'+MyObj.pallets[i].title+'</a></h4></div><div id="'+MyObj.pallets[i].title+'" class="panel-collapse collapse in" aria-expanded="true"><ul class="list-group">';
                            for(var j=0;j<MyObj.pallets[i].operators.length;j++) {
                              resultHtml += '<li class="list-group-item" id="'+MyObj.pallets[i].operators[j].op_nid+'" value="'+MyObj.pallets[i].operators[j].op_nid+'">'+MyObj.pallets[i].operators[j].title+'</li>';
                            }
                            resultHtml += '</ul></div></div></div></div>';
}
jQuery('#stencil').html(resultHtml);
});

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
  graph = new joint.dia.Graph,
  paper = new joint.dia.Paper({
    model: graph,
    width:2000,
    height:2000,
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
 
 //paperscroller.
var paperScroller = new joint.ui.PaperScroller({
  paper: paper
  //cursor:'grab'      
});
jQuery('#paper').append(paperScroller.render().el);
    //paper.on('blank:pointerdown', paperScroller.startPanning);
    /// Navigator object passing  property to the navigator constructor.
var nav = new joint.ui.Navigator({
  paperScroller: paperScroller,
  width: 300,
  height: 200,
  padding: 10,
  zoomOptions: { max: 2, min: 0.2 }
});
nav.$el.appendTo('#navigator');
nav.render();
//Selecting Area with mouse through Selectionview
var selection = new joint.ui.SelectionView({ paper: paper });
paper.on('blank:pointerdown', selection.startSelecting);
paper.on('element:pointerup', function(elementView, evt) {
    if (evt.ctrlKey || evt.metaKey) {
        selection.collection.add(elementView.model);
    }
});
selection.on('selection-box:pointerdown', function(elementView, evt) {
    if (evt.ctrlKey || evt.metaKey) {
        selection.collection.remove(elementView.model);
    }
});  
//graph output operator
joint.shapes.devs.MyImageModel = joint.shapes.devs.Model.extend({

  markup: '<g class="rotatable"><g class="scalable"><rect/><image/></g><text/></g>',

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
jQuery('#stencil').on('mousedown', 'li', function(e){

    jQuery('#stencil').on('mousemove', 'li', function(e){
      if (e.offsetX > 100) {
        drawShape(e, jQuery(this).attr("id"));
        jQuery('#stencil').off('mousemove' , 'li');
      }
    });
});

function drawShape(ev, id) {

  dInPorts = new Array();
  dOutPorts = new Array();
  jsArray =  new Array();
  jsIndexArray =  new Array();
  var dLabel, dInPorts, dOutPorts, imagePath, fillClr;
  var jsIndexArray = <?php echo (json_encode($opId));?>;
  var index = jsIndexArray.indexOf(id);
  var operatorId =  jsIndexArray[index];
  var jsArray = <?php echo (json_encode($op_array,true));?>;
  var dLabel = jsArray[id].dLabel;
  var inPortsArray = jsArray[id].dInPorts;
  var fillClr = jsArray[id].fillClr;
  //var filePath = jsArray[id].filePath;
  //console.log("test"+filePath);
  var imagePath =  jsArray[id].imagePath; 
    if (inPortsArray) {
      var dInPorts = inPortsArray.split(',');
    }
  var outPortsArray = jsArray[id].dOutPorts;
  if (outPortsArray) {
      var dOutPorts = outPortsArray.split(',');
    }
    if(imagePath == 'default.png'){
      imgPath = '/sites/default/files/default_images/default.png';
    }
    else{
      imgPath = '/sites/default/files/'+imagePath;
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
    inputData: '',
    outputData:'',
    operatorType:dLabel,
    settings:{parameters:{}},
    ports: {
        groups: {
            'in': {
                position: 'top',
                label: {
                  position: 'outside'
                },
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
                position: 'bottom',
                label: {
                  position: 'outside'
                },
                attrs: {
                    '.port-body': {
                        fill: '#ccd6d6',
                        r:5,
                        magnet: 'active',
                        stroke:'#a2a8a8'
                    },
                    '.port-label':{
                      'font-size': 9,
                    },
                    require:true,
                }
            },
        },
         
    },
    attrs: {
        '.label': { text: dLabel , 'ref-x': .5, 'ref-y': .2},
        text: { text: dLabel, fill: '#000000','ref-y': -30,'font-size': 9},
        rect: { fill: '#e8f1f0',stroke: '#a2a8a8',width: 100, height: 60},
        image: { 'xlink:href': imgPath,'ref-x': 25, 'ref-y': 20,ref: 'rect',width: 50, height: 20 }
    },
     
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
//paper blank click
paper.on('blank:pointerdown', function(cellView, evt) {
 // nav_slide_right_close();

	      jQuery("#curr-op-parameters").removeClass('active');
	      jQuery("#curr-op-result").removeClass('active'); 

});

////context-menu
paper.on('cell:contextmenu', function (cellView, evt) {
  showContextMenu(evt,cellView);
});

  var contextMenu = jQuery('' +
                      '<div class="context-menu">' +
                      '   <ul>' +      
                      '       <li id = "contextJsonData" contextOperatorId= "">Json Data' +
                      '       </li>' +
                      '       <li id = "contextTabularData" contextOperatorId= "">Tabular Data' +
                      '       </li>' +
                      '       <li id = "cancel">Cancel</li>' +
                      '   </ul>' +
                      '</div>');

function showContextMenu(evt,cellView)
{
  var contextOperatorId = cellView.model.id;
  jQuery(".jqx-item").attr('contextOperatorId',contextOperatorId);
  contextMenu.jqxMenu('open', evt.pageX, evt.pageY);
}
jQuery('#paper').append(contextMenu);
  contextMenu.jqxMenu({
  width: '200px',
  autoOpenPopup: false,
  animationShowDuration: 0,
  animationHideDuration: 0,
  mode: 'popup'
});

// jQuery(document).on('contextmenu', function (e) {
//   return false;
// });
//zooming
// jQuery('#paper').on('mousewheel', function(event) {
//   if(event.originalEvent.deltaY < 0){
//     paperScroller.zoom(0.2,{max:2});
//   }
//   else {
//     paperScroller.zoom(-0.2,{min:0.2});
//   }
// });
//CLICK ON A CELL TO SELECT IT   
paper.on('cell:pointerdown', function(cellView, evt, x, y) {
  console.log(cellView.model.attributes);
    if(cntrlIsPressed) {
      var select_el = cellView.el;
      V(select_el).toggleClass('selected');
      var ew = select_el.getAttribute("class");
      
      if(~ew.indexOf("selected")){
        selectedCells.push(graph.getCell(cellView.model.id));
      }
      else{
        selectedCells = selectedCells.filter(function(el) {
          return el.id !== cellView.model.id;
        });
      }
    }
    else{
      if (cellView.model.isLink()) {
	      jQuery("#curr-op-parameters").removeClass('active');
	      jQuery("#curr-op-result").removeClass('active');      
      }

    else{
      //change parameters
      var currOpId = cellView.model.id;
      var filePathVal = cellView.model.attributes.inputData;
      var opTypeVal = cellView.model.attributes.operatorType;
      //console.log(modelattr);
      var currTitle = cellView.model.attr('text/text');
      var currFillColor = cellView.model.attr('rect/fill');
      //jQuery(".user-nav-slide-right").animate({"right": "232px"});
      jQuery('a[href="#curr-op-parameters"]').parents('li').addClass('active');
      jQuery('a[href="#curr-op-result"]').parents('li').removeClass('active');
      jQuery("#curr-op-parameters").addClass('active');
      jQuery("#curr-op-result").removeClass('active');
      jQuery("#parameter-area").show('medium');
      jQuery("#parameters-box").show('medium');
      jQuery("#parameters").hide();
      jQuery("#parameter-rel").removeClass("closed");
      jQuery('#titleOperator').val(currTitle);
      jQuery('#fillColor').val(currFillColor);
      jQuery('#param-id').val(currOpId);
      jQuery('#selectedFilename').val(filePathVal);
      jQuery('input[name="titleOperator"]').on('keyup', function() {
        var paramHiddenId = document.getElementById("param-id").value;
        if(paramHiddenId == currOpId){
          var rel1 = document.getElementById("titleOperator").value;
          cellView.model.attr('text/text', rel1);
        }
      });
      jQuery('input[name="fillColor"]').on('keyup', function() {
        var paramHiddenId = document.getElementById("param-id").value;
        if(paramHiddenId == currOpId){
          var rel2 = document.getElementById("fillColor").value;
          cellView.model.attr('rect/fill', rel2);
        }
      });
      if(opTypeVal == 'Output'){
        jQuery('#paraAttrName, #filterDiv, #decisionTreeDiv, #joinDiv, #paraFilePath').hide();
      }
      else if(opTypeVal == 'Source'){
        jQuery('#paraFilePath').show();
        jQuery('#paraAttrName, #filterDiv, #decisionTreeDiv, #joinDiv').hide();
      }
      else if(opTypeVal == 'Sort'){
        jQuery('#paraAttrName').show();
        jQuery('#paraFilePath, #filterDiv, #decisionTreeDiv, #joinDiv').hide();
      }
      else if(opTypeVal == 'Filter'){
        jQuery('#filterDiv').show();
        jQuery('#paraFilePath, #paraAttrName, #decisionTreeDiv, #joinDiv').hide();

      }
      else if(opTypeVal == 'Decision Tree'){
        jQuery('#decisionTreeDiv').show();
        jQuery('#paraFilePath, #paraAttrName, #filterDiv, #joinDiv').hide();
      }
      else if(opTypeVal == 'Joins'){
        jQuery('#joinDiv').show();
          var hiddenId = jQuery("#parameters-box #param-id").val();
          var getJoinCellById = graph.getCell(hiddenId);
          var joinCellView = paper.findViewByModel(getJoinCellById);
          var joinType = joinCellView.model.attributes.settings.parameters.joinType;
          jQuery('select[name="joinTypeId"]').find('option[value="'+joinType+'"]').attr("selected",true);
          //jQuery('#joinTypeId option:selected').val(joinType);
        jQuery('#paraFilePath, #paraAttrName, #filterDiv, #decisionTreeDiv').hide();
      }
      else{
        jQuery('#paraFilePath, #paraAttrName, #filterDiv, #decisionTreeDiv,#joinDiv').hide();
      }

    }
    }
});

jQuery(document).keydown(function(event){
  //Delete cell
  if(event.which=="46") {
    jQuery('.selection-box').each(function (index, value) {   
      var cellId = jQuery(this).attr('data-model');
      console.log(cellId);
      removeCreatedFiles(cellId);
      var graphCell = graph.getCell(cellId);
      graphCell.remove();
      //console.log(graphCell);  
     });
  }
/* _.each(selectedCells, function(selectedCell) {
        var currentParentID = selectedCell.get('parent');     
        selectedCell.remove();
        if(currentParentID){
          var currentParentCell = graph.getCell(currentParentID);
          var numberOfChilds = currentParentCell.getEmbeddedCells({deep: true});
          if(numberOfChilds === undefined || numberOfChilds.length == 0) {
            currentParentCell.remove();
          }
        }
        selectedCells = [];
  });*/
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
jQuery('#btn-group').click(function () {
    if(jQuery(".selection-box").length == 0){
        console.log("Nothing selected.");
        return;
    }
    var parent = element(rect);
    jQuery('.selection-box').each(function (index, value) {
       var cellId = jQuery(this).attr('data-model');
        cellids = graph.getCell(cellId);
        console.log(cellids);
         parent.embed(cellids); 
        parent.fitEmbeds({deep:true,
        padding:10});
        parent.toFront({ deep: true });
    jQuery(".joint-theme-default, .joint-selection, .selected div").remove(".selection-wrapper,.selection-box");    
    });
});

//ungrouping  
jQuery('#btn-ungroup').click(function () {
  if(jQuery(".selection-box").length == 0){
    console.log("Nothing selected for UnGrouping");
    return;
  }
  jQuery('.selection-box').each(function (index, value) { 
    var cellId = jQuery(this).attr('data-model');
    cellids = graph.getCell(cellId);
    var numberOfChilds = cellids.getEmbeddedCells({deep: true});
    if(numberOfChilds.length > 0){
      _.each(numberOfChilds, function(children) {
        cellids.unembed(children);   
      });
      cellids.remove();
    }
  });
});
    
//Export
jQuery('#btn-export').click(function () {
  jQuery("#expModal").modal("show");
  var jsonOut =JSON.stringify(graph);
  jQuery('#expTxt').empty();
  jQuery('#expTxt').append(jsonOut);
});
jQuery('#copyToClip').click(function () {
   jQuery("#expTxt").select();
    document.execCommand('copy');
});
jQuery('#expTxt').focus(function() {
    var $this = jQuery(this);
    $this.select();
    $this.mouseup(function() {
        $this.unbind("mouseup");
        return false;
    });
});
jQuery('#btn-import').click(function () {
  jQuery("#impModal").modal("show");
});
jQuery('#btn-import-json').click(function () {
  var rel = document.getElementById("impTxt").value;
  graph.fromJSON(JSON.parse(rel));
  jQuery("#impModal").modal("hide");
     var graphelements = graph.getElements();
    _.each(graphelements, function(el) {
      var elOpType = el.attributes.operatorType;
      if(elOpType == 'Output'){
        paper.findViewByModel(graph.getCell(el.id)).options.interactive = false;
      }
    });
});
// Detecting changes in graph
var showMessage = graph.on('all', function(eventName, cell) {
  if(arguments[0] != "reset"){ 
    if(arguments.length != 0)
    console.log("Testing...");
    jQuery("#page-header").append("<div class='alert alert-warning'><strong>Warning!</strong> Indicates a warning that might need attention.</div>");
  }
});
console.log(showMessage);
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
jQuery("#btn-modify").click(function(){
  var hiddenId = document.getElementById("param-id").value;
  var getCellById = graph.getCell(hiddenId);
  var updateFilename = jQuery('#selectedFilename option:selected').val();
  var cellView = paper.findViewByModel(getCellById);
  cellView.model.attributes.inputData = updateFilename;
  var filename = cellView.model.attributes.inputData;
  if(filename.length >0){
    read_source_file(filename,hiddenId);
  }
});
// Joins modify 
jQuery("#joinModify").click(function(){
  var hiddenId = document.getElementById("param-id").value;
  var getCellById = graph.getCell(hiddenId);
  var currJoins = paper.findViewByModel(getCellById);
  var joinType = jQuery('#joinTypeId option:selected').val();
  var fileData1 = currJoins.model.attributes.settings.parameters.leftSource;
  var fileData2 = currJoins.model.attributes.settings.parameters.rightSource;
  currentJoinType = joinType;
  joinType = '["'+joinType+'"]';
  currJoins.model.attributes.settings.parameters.joinType = currentJoinType;
  if((fileData1.length>0) && (fileData2.length>0)){
    var sourceJsonData1 = fileData1;
    var sourceJsonData2 = fileData2;
    applied_joins(sourceJsonData1, sourceJsonData2, joinType, getCellById, hiddenId);
  }
  else{
    console.log("Please attach data to both ports");
  }
});
//filter form 
jQuery("#btn-multifilter-form").on('click',function(){
  var hiddenId = document.getElementById("param-id").value;
  var getCellById = graph.getCell(hiddenId);
  var currFilter = paper.findViewByModel(getCellById);
  var sourceJsonData = currFilter.model.attributes.inputData;
  var sourceJsonDataLength = Object.keys(sourceJsonData).length;
  var parameters = currFilter.model.attributes.settings.parameters;
  var parametersLength = Object.keys(parameters).length;
  if(parametersLength == 0){
    console.log('no parameter');
    var selectedFilters = {"selectedFilters":{}};
    currFilter.model.attributes.settings.parameters = selectedFilters;
  }
  var selectedFiltersVar = currFilter.model.attributes.settings.parameters.selectedFilters;

  if(sourceJsonDataLength > 0){
    update_filter_attr(sourceJsonData,selectedFiltersVar);
  }
  else{
      var filterCounter = jQuery('#global_filter_count').val();
      for(i=2; i <= filterCounter; i++){
       jQuery('#global_filter'+i).remove();
      }
      jQuery('#global_filter_sel1').empty('');
      jQuery('#global_filter_cond_val1').val('');
      jQuery('select[name="global_filter_cond_sel1"]').find('option[value=""]').attr("selected",true);
  }
  jQuery("#multifilterModal").modal("show");
});
//sorting Form
jQuery("#btn-sort-form").on('click',function(){
  var hiddenId = document.getElementById("param-id").value;
  var getCellById = graph.getCell(hiddenId);
  var currSort = paper.findViewByModel(getCellById);
  var sourceJsonData = currSort.model.attributes.inputData;
  //var sourceJsonDataLength = Object.keys(sourceJsonData).length;
  var parameters = currSort.model.attributes.settings.parameters;
  console.log("=============");
  console.log(sourceJsonData);
  console.log("=============");
   if(sourceJsonData){
    update_sort_attr(sourceJsonData,parameters);
   }
  else{
    jQuery('#sortAttributes').empty();
    jQuery('select[name="sortType"]').find('option[value="ASC"]').attr("selected",true);
  }
  jQuery("#sortModal").modal("show");
});
//current data
jQuery("#contextTabularData").click(function(){
  var hiddenId = jQuery('#contextTabularData').attr("contextOperatorId");
  var getCellById = graph.getCell(hiddenId);
  var currContextData = paper.findViewByModel(getCellById);
  current_data(currContextData);

});
jQuery("a[href='#curr-op-result']").click(function(){

  jQuery("#exTab1").addClass("operator-main-div-container-class");
  jQuery("#operator-main-div").addClass('operator-main-div-class');
	if(jQuery('#param-id').val() == ''){
		//return "no current data";
	    jQuery('a[href="#curr-op-parameters"]').parents('li').removeClass('active');
	    jQuery('a[href="#curr-op-result"]').parents('li').addClass('active');
	    jQuery("#curr-op-parameters").removeClass('active');
	    jQuery("#curr-op-result").addClass('active');
	    return jQuery("#currentResultTable tbody").html('No operator data available');
	}
  var hiddenId = jQuery('#param-id').val();
  //console.log(hiddenId);
  var getCellById = graph.getCell(hiddenId);
  var currContextData = paper.findViewByModel(getCellById);
  current_data(currContextData);

});
jQuery("a[href='#curr-op-parameters']").click(function(){

      if(jQuery("#exTab1").hasClass("operator-main-div-container-class")){
        jQuery("#exTab1").removeClass("operator-main-div-container-class");
      }
    if(jQuery("#operator-main-div").hasClass('operator-main-div-class')){
  jQuery("#operator-main-div").removeClass('operator-main-div-class');
}

});


graph.on('change:source change:target', function(link) {
    var sourceId = link.get('source').id;
    var targetId = link.get('target').id;
    var getSourceCell = graph.getCell(sourceId);
    getSourceCell.prop('ports/groups/out/attrs/.port-body/fill', 'yellow');

    if(targetId){
      var getTargetCell = graph.getCell(targetId);
      var getSourceCellType = getSourceCell.attributes.operatorType;
      var getTargetCellType = getTargetCell.attributes.operatorType;
      getSourceCell.prop('ports/groups/out/attrs/.port-body/fill', '#ccd6d6');
      getSourceCell.prop('ports/groups/out/attrs/.port-body/magnet', 'passive');
        //update target operator by connecting to source operator
      var sourceJsonData = getSourceCell.attributes.outputData;
      getTargetCell.attributes.inputData = sourceJsonData;
      getTargetCell.attributes.outputData = sourceJsonData;
      var sourceJsonUpData = getTargetCell.attributes.outputData;
      var outboundLinks = graph.getConnectedLinks(getTargetCell, { outbound: true, deep: true });
      recursion_source(outboundLinks,sourceJsonUpData);

      if(getTargetCellType == 'Joins'){
	    //getTargetCell.attributes.settings.parameters = {"leftSource":{}, "rightSource":{}};
        if(link.get('target').port == 'lef'){
          var leftSource = getSourceCell.attributes.outputData;
          getTargetCell.attributes.settings.parameters.leftSource = leftSource;

        }
	    if(link.get('target').port == 'rig'){
	      var rightSource = getSourceCell.attributes.outputData;
	      getTargetCell.attributes.settings.parameters.rightSource = rightSource;
	    }
	  }
	if(getSourceCellType == 'Joins'){
		if(getSourceCell.attributes.settings.parameters.joinType == undefined){
			console.log('Plz select join type');
			  var joinType = '["inner"]';
      		  var currJoins = paper.findViewByModel(getSourceCell);
			  var fileData1 = currJoins.model.attributes.settings.parameters.leftSource;
			  var fileData2 = currJoins.model.attributes.settings.parameters.rightSource;
			  currJoins.model.attributes.settings.parameters.joinType = joinType;
			  if((fileData1.length>0) && (fileData2.length>0)){
			  	console.log('filedata1 and 2');
			    var sourceJsonData1 = JSON.stringify(fileData1);
			    var sourceJsonData2 = JSON.stringify(fileData2);
			    var getCellById = getSourceCell;
			    applied_joins(sourceJsonData1, sourceJsonData2, joinType, getCellById);
			  }
			  else{
			    console.log("Please attach data to both ports");
			  }

		}

	}   
    }

});
//link removed
graph.on('remove', function(cell, collection, opt) {
   if (cell.isLink()) {
      // a link was removed  (cell.id contains the ID of the removed link)
      var removedSourceId = cell.get('source').id;
      var removedTargetId = cell.get('target').id;
      var getRemovedSourceCell = graph.getCell(removedSourceId);
      //if source link removed its port is active again
       getRemovedSourceCell.prop('ports/groups/out/attrs/.port-body/magnet', 'active');
       ////code for link removed between operators 
      var removedTargetCell = graph.getCell(removedTargetId);
      var removedTargetCellView = paper.findViewByModel(removedTargetCell);
      removedTargetCellView.model.attributes.inputData = '';
      removedTargetCellView.model.attributes.outputData = '';
      if(removedTargetCellView.model.attributes.operatorType == 'Joins'){
      	if(cell.get('target').port == 'lef'){
      		removedTargetCellView.model.attributes.settings.parameters.leftSource = '';
      	}
      	if(cell.get('target').port == 'rig'){
      		removedTargetCellView.model.attributes.settings.parameters.rightSource = '';
      	}
      }
      else{
        removedTargetCellView.model.attributes.settings.parameters = {};
      }
      //if removed link target element has outbound links
      var removedTargetCellOutLinks = graph.getConnectedLinks(removedTargetCell, { outbound: true });
      //recursive function for removing data from target connected operators
      recursion_target(removedTargetCellOutLinks);
   }
})
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
   save_graph(graph_nid, jsonSave);
});
//Process run
jQuery('#btn-run').click(function () {
  var graphelements = graph.getElements();
    //check all elements type
     _.each(graphelements, function(el) {
      var elOpType = el.attributes.operatorType;
      if(elOpType == 'Output'){
        var outputElementCell = graph.getCell(el.id);
        var outputInboundLink = graph.getConnectedLinks(outputElementCell, { inbound: true });
      if(outputInboundLink.length > 0){
      _.each(outputInboundLink, function(outputInboundLink) {
          var OutputElement = outputInboundLink.getTargetElement();
          var getOutput= graph.getCell(OutputElement);
          var sourceJsonData = getOutput.attributes.inputData;
          var sourceJsonDataLength = Object.keys(sourceJsonData).length;
          if(sourceJsonDataLength > 0 ){
            result_data(sourceJsonData);
          }
          else{
            jQuery("#sourceConnectedFileAttachModal").modal("show");
          }

      });
      }
        else{
        jQuery("#outNotConnectedModal").modal("show");
        }

      }

      if(elOpType == 'Source'){

        var outputElementCell = graph.getCell(el.id);
        var outputInboundLink = graph.getConnectedLinks(outputElementCell, { outbound: true });
        if(outputInboundLink.length == 0){
          jQuery("#sourceNotConnectedModal").modal("show");
        }
      }
    });
});

//current graph data from database
var current_graph_data = '<?php echo $current_graph_data;?>';
//console.log(current_graph_data);
if(current_graph_data){
jQuery('#savedgraph').val(current_graph_data);
  var graphelements = graph.getElements();
  //graph.fromJSON(JSON.parse(current_graph_data));
  var graphrel = document.getElementById("savedgraph").value;
  graph.fromJSON(JSON.parse(graphrel));
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
          jQuery(".graphLoader-save").css('visibility','visible');
        },
        type : 'post',
        success : function(data) {
          jQuery(".graphLoader-save").css('visibility','hidden');
          jQuery("#msgModal").modal("show");
        },
        error : function(xhr, status, error) {
          // executed if something went wrong during call
          if (xhr.status > 0)
            alert('got error: ' + status);
        }
      });
};

// Ajax request for removing files
function removeCreatedFiles(hiddenId){
  jQuery.ajax({
        url : '/removing-created-files',
        data : {
          hiddenId : hiddenId
        },
        beforeSend : function() {
          
        },
        type : 'post',
        success : function(data) {
          console.log(data);
        },
        error : function(xhr, status, error) {
          // executed if something went wrong during call
          if (xhr.status > 0)
            alert('got error: ' + status);
        }

  });
};
//Ajax request for reading Source
 function read_source_file(filename,hiddenId) {

  jQuery.ajax({
        url : '/reading-source',
        data : {
          filename : filename,
          hiddenId : hiddenId
        },
        beforeSend : function() {
          jQuery(".graphLoader-modify").css('visibility','visible');
          jQuery("#btn-run").addClass("disabled");
        },
        type : 'post',
        success : function(data) {
          console.log(data);
          var sourceElement = graph.getCell(hiddenId);
          var sourceCellView = paper.findViewByModel(sourceElement);
          
          sourceCellView.model.attributes.outputData = data;
          var outboundLinks = graph.getConnectedLinks(sourceElement, { outbound: true, deep: true });
          //if(outboundLinks.length >0){
          var sourceJsonUpData = sourceCellView.model.attributes.outputData;
          recursion_source(outboundLinks,sourceJsonUpData);
          jQuery("#btn-run").removeClass("disabled");
          jQuery(".graphLoader-modify").css('visibility','hidden');
        },
        error : function(xhr, status, error) {
          // executed if something went wrong during call
          if (xhr.status > 0)
            alert('got error: ' + status);
        }
      });
};
//Function for showing source read data
 function result_data(sourceJsonData) {
    if(sourceJsonData[0] == "No Records Found !!"){
      jQuery("#noRecodFoundModal").modal("show");
    }
    else{
    var headVar = sourceJsonData[0];
    var thData = "";
    for (var keys in headVar) {
      thData += "<th>"+keys+"</th>";
    }
    var trthData = "<tr>"+thData+"</tr>";
    jQuery("#csvTableId thead").html(trthData);
    var trData = "";
      for (var key in sourceJsonData) {
        var parseDataValue = sourceJsonData[key];
        var tdData = "";
        for (var key2 in parseDataValue) {
          var tdbody = parseDataValue[key2];
          tdData += "<td>"+tdbody+"</td>";
        }
      trData += "<tr>"+tdData+"</tr>";
      }
    jQuery("#csvTableId tbody").html(trData);
    jQuery("#csvModal").modal("show");
  }
};
//Function for showing current read data
//  function current_data(currContextData) {
//   var sourceJsonData = currContextData.model.attributes.outputData;
//   console.log(sourceJsonData);
//   if(Object.keys(sourceJsonData).length == 0){
//     console.log('currently no data available');

//    }
//    else{
//     if(sourceJsonData[0] == "No Records Found !!"){
//       jQuery("#noRecodFoundModal").modal("show");
//     }
//     else{
//     var headVar = sourceJsonData[0];
//     var thData = "";
//     for (var keys in headVar) {
//       thData += "<th>"+keys+"</th>";
//     }
//     var trthData = "<tr>"+thData+"</tr>";
//     jQuery("#csvTableId thead").html(trthData);
//     var trData = "";
//       for (var key in sourceJsonData) {
//         var parseDataValue = sourceJsonData[key];
//         var tdData = "";
//         for (var key2 in parseDataValue) {
//           var tdbody = parseDataValue[key2];
//           tdData += "<td>"+tdbody+"</td>";
//         }
//       trData += "<tr>"+tdData+"</tr>";
//       }
//     jQuery("#csvTableId tbody").html(trData);
//     jQuery("#csvModal").modal("show");
//     }
//   }
// };

///////
 function current_data(currContextData) {
  var sourceJsonFile = currContextData.model.attributes.outputData;
  var sourceJsonData = read_json_file(sourceJsonFile);
  
  console.log(sourceJsonData);
  if(Object.keys(sourceJsonData).length == 0){
    console.log('currently no data available');

   }
   else{
    if(sourceJsonData[0] == "No Records Found !!"){
      jQuery("#noRecodFoundModal").modal("show");
    }
    else{
    var headVar = sourceJsonData[0];
    var thData = "";
    for (var keys in headVar) {
      thData += "<th>"+keys+"</th>";
    }
    var trthData = "<tr>"+thData+"</tr>";
    jQuery("#currentResultTable thead").html(trthData);
    var trData = "";
      for (var key in sourceJsonData) {
        var parseDataValue = sourceJsonData[key];
        var tdData = "";
        for (var key2 in parseDataValue) {
          var tdbody = parseDataValue[key2];
          tdData += "<td>"+tdbody+"</td>";
        }
      trData += "<tr>"+tdData+"</tr>";
      }
    jQuery("#currentResultTable tbody").html(trData);

  
      jQuery("#operator-area").hide('medium');
      //jQuery(".user-nav-slide").animate({"left": "0px"}, "medium");
      jQuery("#operator-rel").addClass("closed");

    jQuery('a[href="#curr-op-parameters"]').parents('li').removeClass('active');
    jQuery('a[href="#curr-op-result"]').parents('li').addClass('active');
    jQuery("#curr-op-parameters").removeClass('active');
    jQuery("#curr-op-result").addClass('active');
    }
  }
};
// Read Json file
function read_json_file(jsonFile) {
        var json = null;
        jQuery.ajax({
            'async': false,
            'global': false,
            'url': "/sites/default/files/operator_files/"+jsonFile,
            'dataType': "json",
            'success': function (data) {
                json = data;
            }
        });
        return json;
}

/////
//Ajax request for reading headers
 function update_filter_attr(sourceJsonData,selectedFiltersVar) {
  var jsonData = read_json_file(sourceJsonData);
  console.log(jsonData);
  var selectedFiltersVarLength = Object.keys(selectedFiltersVar).length;
  if(selectedFiltersVarLength > 0){
    var colAttributes = selectedFiltersVar[0];
    var colOperators = selectedFiltersVar[1];
    var colRequiredValues = selectedFiltersVar[2];
    if((colAttributes.length >0) && (colOperators.length >0) && (colRequiredValues.length >0)){
      console.log('operators has the values');
      var parseData =  jsonData[0];
      var globalfilersCount = jQuery('#global_filter_count').val(colAttributes.length);
      jQuery("#data-view-filters").empty();
              var options = '';
              for (var key in parseData) {
                  var option = '<option value="'+key+'">'+key+'</option>';
                  options += option;
              }
              options += '<option value="">--Select operator--</option>';
      for (var i = colAttributes.length; i >=1; i--) {
        var j = i-1;
        var remBtn ='';

      if(i>1){
        remBtn = '<input id="global_'+i+'" class="btn btn-danger remove-me exist" value="x" type="button">';
      }

        var operatorsHtml = '<option value="">--Select operator--</option><option value="EQUAL">is equal to</option><option value="NOTEQUAL">is not equal to</option><option value="ISIN">is in</option><option value="ISNOTIN">is not in</option><option value="LESSTHAN">is less than</option><option value="GREATERTHAN">is greater than</option><option value="STARTWITH">start with</option><option value="ENDWITH">end with</option>';

        var dynamicSelect = '<div id="global_filter'+i+'" class="form-group form-inline filterDiv"><select class="form-control filterColoumSel" id="global_filter_sel'+i+'" name="global_filter_sel'+i+'">'+options+'</select><select class="form-control" id="global_filter_cond_sel'+i+'" name="global_filter_cond_sel'+i+'">'+operatorsHtml+'</select><input type="text" class="form-control" id="global_filter_cond_val'+i+'" name="global_filter_cond_val'+i+'" placeholder="Value" value="'+colRequiredValues[j]+'">'+remBtn+'</div>';
        jQuery("#data-view-filters").prepend(dynamicSelect);
        jQuery('select[name="global_filter_sel'+i+'"]').find('option[value="'+colAttributes[j]+'"]').attr("selected",true);
        jQuery('select[name="global_filter_cond_sel'+i+'"]').find('option[value="'+colOperators[j]+'"]').attr("selected",true);

      }
    }
    else{
      console.log('Filter has no value');
    }

  }
  else if(selectedFiltersVarLength == 0){
    var filterCounter = jQuery('#global_filter_count').val();
    if(filterCounter > 1){
      for(i=2; i <= filterCounter; i++){
       jQuery('#global_filter'+i).remove();
       jQuery('#and_global_filter'+i).remove();       
      }
    }
    var parseData =  jsonData[0];
    jQuery('#global_filter_sel1').empty('');
    jQuery('#global_filter_cond_val1').val('');
    var selectfilter = document.getElementById("global_filter_sel1");
    for (var key in parseData) {
      var option = document.createElement("option");
        option.setAttribute("value", key);
        option.text = key;
        selectfilter.appendChild(option); 
    }
    jQuery("#global_filter_sel1").prepend("<option value='' selected='selected'>--Select attribute--</option>");
}

};
//update sort attributes
function update_sort_attr(sourceJsonData,parameters){
  var sourceJson = read_json_file(sourceJsonData);
  var parametersLength = Object.keys(parameters).length;
  if(parametersLength > 0){
    jQuery('#sortAttributes').empty();
    var parseData =  sourceJson[0];
    var sortAttributes = document.getElementById("sortAttributes");
    for (var key in parseData) {
      var option = document.createElement("option");
        option.setAttribute("value", key);
        option.text = key;
        sortAttributes.appendChild(option); 
    }
    jQuery("#sortAttributes").prepend("<option value=''>--Select attribute--</option>");
    jQuery('select[name="sortAttributes"]').find('option[value="'+parameters[0]+'"]').attr("selected",true);
    jQuery('select[name="sortType"]').find('option[value="'+parameters[1]+'"]').attr("selected",true);
  }
  else{
  var parseData =  sourceJson[0];
    jQuery('#sortAttributes').empty('');
    var sortAttributes = document.getElementById("sortAttributes");
    for (var key in parseData) {
      var option = document.createElement("option");
        option.setAttribute("value", key);
        option.text = key;
        sortAttributes.appendChild(option); 
    }
    jQuery("#sortAttributes").prepend("<option value='' selected='selected'>--Select attribute--</option>");
 }
}
//Ajax request for applying filter
 function applied_filter(sourceJsonData, operators, colnames, requiredValues, filterCellView, getCellFilterById, hiddenId) {
  //console.log(sourceJsonData);
  jQuery.ajax({
        url : '/multiplefiltered-csv',
        data : {
          sourceJsonData : sourceJsonData,
          operators: operators,
          colnames: colnames,
          required_values: requiredValues,
          hiddenId : hiddenId
        },
        beforeSend : function() {
          jQuery(".graphLoader-modify").css('visibility','visible');
          jQuery("#btn-run").addClass("disabled");
        },
        type : 'post',
        success : function(data) {
          jQuery(".graphLoader-modify").css('visibility','hidden');
          console.log("filter applied");
          filterCellView.model.attributes.outputData = data;
          var sourceJsonUpData = filterCellView.model.attributes.outputData;
          var outboundLinks = graph.getConnectedLinks(getCellFilterById, { outbound: true, deep: true });
          if(outboundLinks.length > 0){
            recursion_source(outboundLinks,sourceJsonUpData);
          }
           console.log(filterCellView.model.attributes);
           jQuery("#btn-run").removeClass("disabled");
        },
        error : function(xhr, status, error) {
          // executed if something went wrong during call
          if (xhr.status > 0)
            alert('got error: ' + status);
        }
      });
};
//Ajax request for sorting data.
 function applied_sort(sourceJsonData, colNames, attrValue, sortCellView, getSortCellById, hiddenId) {
  //console.log(sourceJsonData);
  jQuery.ajax({
        url : '/sorting-data',
        data : {
          sourceJsonData : sourceJsonData,
          colNames: colNames,
          attrValue: attrValue,
          hiddenId : hiddenId
        },
        beforeSend : function() {
          jQuery(".graphLoader-modify").css('visibility','visible');
          jQuery("#btn-run").addClass("disabled");
        },
        type : 'post',
        success : function(data) {
          console.log(data);
          jQuery(".graphLoader-modify").css('visibility','hidden');
          console.log("filter applied");
          if(data == '["Date Format is not Correct"]'){
            console.log("Date format is not correct");
            jQuery("#dateFormat").modal("show");
            return;
          }
          //var parseData =  JSON.parse(data);

          sortCellView.model.attributes.outputData = data;
          var sourceJsonUpData = sortCellView.model.attributes.outputData;
          var outboundLinks = graph.getConnectedLinks(getSortCellById, { outbound: true, deep: true });
          if(outboundLinks.length > 0){
            recursion_source(outboundLinks,sourceJsonUpData);
          }
          jQuery("#btn-run").removeClass("disabled");
        },
        error : function(xhr, status, error) {
          // executed if something went wrong during call
          if (xhr.status > 0)
            alert('got error: ' + status);
        }
      });
};

//Ajax request for joins data.
 function applied_joins(sourceJsonData1, sourceJsonData2, joinType, getCellById, hiddenId) {
  //console.log(sourceJsonData);
  jQuery.ajax({
        url : '/joins-data',
        data : {
          sourceJsonData1 : sourceJsonData1,
          sourceJsonData2: sourceJsonData2,
          joinType: joinType,
          hiddenId : hiddenId
        },
        beforeSend : function() {
          jQuery(".graphLoader-modify").css('visibility','visible');
          jQuery("#btn-run").addClass("disabled");
        },
        type : 'post',
        success : function(data) {
          console.log(data);
          //var parseData =  JSON.parse(data);
          getCellById.attributes.outputData = '';
	        getCellById.attributes.inputData = '';
          getCellById.attributes.inputData = data;
          getCellById.attributes.outputData = data;
          var sourceJsonUpData = getCellById.attributes.outputData;
          var outboundLinks = graph.getConnectedLinks(getCellById, { outbound: true, deep: true });
          if(outboundLinks.length > 0){
            recursion_source(outboundLinks,sourceJsonUpData);
          }
          jQuery(".graphLoader-modify").css('visibility','hidden');
          jQuery("#btn-run").removeClass("disabled");
        },
        error : function(xhr, status, error) {
          // executed if something went wrong during call
          if (xhr.status > 0)
            alert('got error: ' + status);
        }
      });
};
////////////////Multiple Filters/////////////////////////
// Add more button
jQuery(".add-more").click(function (e) {
  e.preventDefault();

  var btnName = jQuery(this).attr('name');
  if (btnName == "global_addmore") {
    var next = jQuery('#global_filter_count').val();
    next++;
    var getDom = jQuery("#global_filter_sel1").html();
    var getDom1 = jQuery("#global_filter_cond_sel1").html();
    var newAnd = '<div id="and_global_filter' + next + '" class="form-group form-inline andDiv">';
    var Andcont = '<span>And</span>';
    var newAndend = '</div>';
    var newfilterdiv = '<div id="global_filter' + next + '" class="form-group form-inline filterDiv">';
    var newglobal_filter_sel = '<select class="form-control filterColoumSel" id="global_filter_sel' + next + '" name="global_filter_sel' + next + '">';
    newglobal_filter_sel += getDom + '</select>';
    var newglobal_filter_cond_sel = '<select class="form-control" id="global_filter_cond_sel' + next + '" name="global_filter_cond_sel' + next + '">';
    newglobal_filter_cond_sel += getDom1 + '</select>';
    var newIn = '<input type="text" class="form-control" id="global_filter_cond_val' + next + '" name="global_filter_cond_val' + next + '" placeholder="Value">';
    var newfilterdivEnd = '</div>';
    var complDiv = newAnd + Andcont + newAndend + newfilterdiv + newglobal_filter_sel + newglobal_filter_cond_sel + newIn + newfilterdivEnd;
    var removebtn = '<input type="button" value="x" id="global_' + next + '" class="btn btn-danger remove-me" />';
    //$('#csvViewsModal .filterDiv').last().after(complDiv);
    jQuery('.filterDiv').last().after(complDiv);
    jQuery('#global_filter' + next).find("select option").removeAttr("selected");
    jQuery('#global_filter' + next).find("select:first").focus();
    //$('#global_filter' + next).focus();
    jQuery('#global_filter' + next).append(removebtn);
    jQuery('#global_filter_count').val(next);

  }
});
  
// remove more button 
jQuery('#data-view-filters').on('click', '.remove-me', function () {
  var rmvbtnID = jQuery(this).attr('id');
  var splitres = rmvbtnID.split("_");
  if (splitres[0] == 'global') {
    jQuery('#and_global_filter' + splitres[1]).remove();
    jQuery('#global_filter' + splitres[1]).remove();
    var filter_val = jQuery('#global_filter_count').val();
    if (filter_val > 1) {
      filter_val = filter_val - 1;
      jQuery('#global_filter_count').val(filter_val);
    }
  }
});
  
  // Getting values of filter form
jQuery("#submitFilters").on('click', function (){

  var hiddenId = jQuery("#parameters-box #param-id").val();
     var operatorsArr = [];
     var colnamesArr = [];
     var requiredValuesArr = [];

  var globalfilersCount = jQuery('#global_filter_count').val();
  var filters = '';
  var globalFilterJson = '';
  for (var i = 1; i <= globalfilersCount; i++) {
    if (jQuery('#global_filter' + i).length == 0) {
      continue;
    }
    if (jQuery('input[name="global_filter_cond_val' + i + '"]').val() == "") {
      continue;
    }
    var colname = jQuery('select[name="global_filter_sel' + i + '"]').val();
    var operator = jQuery('select[name="global_filter_cond_sel' + i + '"]').val();
    var requiredVal = jQuery('input[name="global_filter_cond_val' + i + '"]').val();
    filters += colname + ' ' + operator + ' ' + requiredVal + '||';
    if (i > 1) {
      operatorsArr.push(operator);
      colnamesArr.push(colname);
      requiredValuesArr.push(requiredVal);
    } else {
      operatorsArr.push(operator);
      colnamesArr.push(colname);
      requiredValuesArr.push(requiredVal);
    }
  }
  var operators = JSON.stringify(operatorsArr);
  var colnames = JSON.stringify(colnamesArr);
  var requiredValues = JSON.stringify(requiredValuesArr);
  var getCellFilterById = graph.getCell(hiddenId);
  var filterCellView = paper.findViewByModel(getCellFilterById);
  var sourceJsonDataArr = filterCellView.model.attributes.inputData;
  filterCellView.model.attributes.settings.parameters.selectedFilters = [colnamesArr,operatorsArr,requiredValuesArr];
  console.log(filterCellView.model.attributes.settings.parameters);
  filterCellView.model.attributes.outputData = {};
  var sourceJsonData = sourceJsonDataArr;
  console.log("-------------");
  console.log(sourceJsonData);
  console.log(operators);
  console.log(colnames);
  console.log(requiredValues);
  console.log(filterCellView);
  console.log(getCellFilterById);

  console.log("-------------");
  applied_filter(sourceJsonData, operators,colnames, requiredValues,filterCellView,getCellFilterById,hiddenId);

});

  // Getting values of sort form
jQuery("#submitSorting").on('click', function (){
  var hiddenId = jQuery("#parameters-box #param-id").val();
  var colNamesVal = jQuery('#sortAttributes option:selected').val();
  var attrValueVal = jQuery('#sortType option:selected').val();
  var getSortCellById = graph.getCell(hiddenId);
  var sortCellView = paper.findViewByModel(getSortCellById);
  var sourceJsonDataArr = sortCellView.model.attributes.inputData;
  sortCellView.model.attributes.settings.parameters = [colNamesVal,attrValueVal];
  sortCellView.model.attributes.outputData = '';
  var colNames = JSON.stringify(colNamesVal);
  var attrValue = JSON.stringify(attrValueVal);
  applied_sort(sourceJsonDataArr, colNames, attrValue, sortCellView, getSortCellById, hiddenId);

});
    
});
/////jquery for left panel
jQuery(".user-nav-slide").click(function () {

    if (jQuery("#operator-rel").hasClass("closed")) {
      jQuery('a[href="#curr-op-parameters"]').parents('li').removeClass('active');
      jQuery('a[href="#curr-op-result"]').parents('li').removeClass('active');
      jQuery("#curr-op-parameters").removeClass('active');
      jQuery("#curr-op-result").removeClass('active');
      if(jQuery("#exTab1").hasClass("operator-main-div-container-class")){
        jQuery("#exTab1").removeClass("operator-main-div-container-class");
      }
      jQuery("#operator-main-div").removeClass('operator-main-div-class');
      jQuery("#operator-area").show('medium');
            //jQuery(".user-nav-slide").animate({"left": "232px"}, "medium");
      jQuery("#operator-rel").removeClass("closed");
    } else {
  
      jQuery("#operator-area").hide('medium');
      //jQuery(".user-nav-slide").animate({"left": "0px"}, "medium");
      jQuery("#operator-rel").addClass("closed");
    }
});
//jquery for right panel
// jQuery(".user-nav-slide-right").click(function () {

//     if (jQuery("#parameter-rel").hasClass("closed")) {
//       nav_slide_right_open();   
//        jQuery("#parameters").show('medium');
//     }
//     else {
//       nav_slide_right_close();
//     }
// });
// //function to close right panel
// function nav_slide_right_close(){
//   //jQuery(".user-nav-slide-right").animate({"top": "0px"});
//   jQuery("#parameter-area").hide('medium');
//   jQuery("#parameter-rel").addClass("closed");
// }
// //function to open right panel
// function nav_slide_right_open(){
//   //jQuery(".user-nav-slide-right").animate({"top": "232px"});
//   jQuery("#parameters-box").hide();
//   jQuery("#parameter-area").show('medium');
//   jQuery("#parameter-rel").removeClass("closed");
// }
//All connected operators will read sourcejson data
function recursion_source(outboundLinks, sourceJsonUpData){
  _.each(outboundLinks, function(outboundLink) {
    var outboundTargetElement = outboundLink.getTargetElement();
    var outboundTargetCell = graph.getCell(outboundTargetElement);
    outboundTargetCell.attributes.inputData = '';
    outboundTargetCell.attributes.outputData = '';
    console.log(outboundLink);
    if(outboundTargetCell.attributes.operatorType == 'Joins'){
       if(outboundLink.get('target').port == 'lef'){
     	outboundTargetCell.attributes.settings.parameters.leftSource = '';
        outboundTargetCell.attributes.settings.parameters.leftSource = sourceJsonUpData;
        var joinType = jQuery('#joinTypeId option:selected').val();
        outboundTargetCell.attributes.settings.parameters.joinType = '';
        outboundTargetCell.attributes.settings.parameters.joinType = joinType;
      }
     if(outboundLink.get('target').port == 'rig'){
     	outboundTargetCell.attributes.settings.parameters.rightSource = '';
        outboundTargetCell.attributes.settings.parameters.rightSource = sourceJsonUpData;
        var joinType = jQuery('#joinTypeId option:selected').val();
        outboundTargetCell.attributes.settings.parameters.joinType = '';
        outboundTargetCell.attributes.settings.parameters.joinType = joinType;
      }
    }
    else{
      outboundTargetCell.attributes.settings.parameters = {};
    }
    outboundTargetCell.attributes.inputData = sourceJsonUpData;  
    outboundTargetCell.attributes.outputData = sourceJsonUpData;
    var outboundNewLinks = graph.getConnectedLinks(outboundTargetElement, { outbound: true});
    if(Object.keys(outboundNewLinks).length > 0){
      recursion_source(outboundNewLinks, sourceJsonUpData);
    }
    else{
      return true;
    }  
  });
}
//if link is remved and target operators is connected to other operators 
//All connected operators will be updated
function recursion_target(removedTargetCellOutLinks){
  _.each(removedTargetCellOutLinks, function(remTargetOutLink) {
    var outboundTargetElement = remTargetOutLink.getTargetElement();
    var outboundTargetCell = graph.getCell(outboundTargetElement);
    outboundTargetCell.attributes.inputData = '';
    outboundTargetCell.attributes.outputData = '';
    outboundTargetCell.attributes.settings.parameters = {};
    var outboundNewLinks = graph.getConnectedLinks(outboundTargetElement, { outbound: true});
    if(Object.keys(outboundNewLinks).length > 0){
      recursion_target(outboundNewLinks);
    }
    else{
      return true;
    }
     
  });
}
</script>
<?php } ?>