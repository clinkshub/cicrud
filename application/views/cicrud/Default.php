<!DOCTYPE html>
<html lang="en">
<head>
  <title>Bootstrap Example</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js"></script>
  <style>
	  td,th{
		  line-height: 14px !important;
		  font-size: 12px !important;
		  padding-bottom: 2px 5px !important;
	  }

  </style>
</head>
<body> 
 
<div class="container">
	<h2 class="border-bottom text-danger">Codeigniter Resources Generator</h2>
	<div class="row">
		<div class="col-md-4">
		<div class="card card-info">
			<div class="card-header">
				Navigations
			</div>
			<div class="card-body">
				<h4 class="border-bottom">DB Table Structure</h4>
				<hr>
				<div class="list-group">
					<?php
                        foreach ($tables as $key => $val) {
                            echo '<a href="#" class="list-group-item mytable" data-table="'.$val['myTables'].'">'.$val['myTables'].'</a>';
                        }
                    ?>
				</div>
			</div>
		</div>
		</div>
		<div class="col-md-8">
			<div class="card">
				<div class="card-header bg-info">
					Actions Area
				</div>
				<div class="card-body">
					<h4 class="text-success">Selected Table : "<strong id="tablename"></strong>"</h4>
					<div class="card mb-sm-3">
						<div class="card-header bg-warning">
							Generate Views
						</div>
						<div class="card-body">
							<table class="table">
								<thead>
									<tr>
										<th>SR</th>
										<th>Column Name</th>
										<th>Form Element</th>
										<th>Element Type</th>
										<th>Mandatory</th>
									</tr>
								</thead>
								<tbody id="loadformdata"></tbody>
							</table>
							<div>
								<button disabled="disabled" class="btn btn-outline-success btn-sm" id="createbtn">Create</button>
								<button disabled="disabled" class="btn btn-outline-success btn-sm" id="editbtn">Edit</button>
								<button disabled="disabled" class="btn btn-outline-success btn-sm" id="allbtn">All</button>
								<button disabled="disabled" class="btn btn-outline-success btn-sm" id="showbtn">Show</button>
							</div>
						</div>
						<div class="card-footer text-right">
							<button class="btn btn-primary btn-sm createbtns">Generate Views</button>
						</div>
					</div>
					<div class="card mb-sm-3">
						<div class="card-header bg-warning">
							Generate Controller
						</div>
						<div class="card-body">
							Controller Option Will goes here
						</div>
						<div class="card-footer text-right">
							<button class="btn btn-primary btn-sm createbtns">Generate Controller</button>
						</div>
					</div>
					<div class="card mb-sm-3">
						<div class="card-header bg-warning">
							Generate Model
						</div>
						<div class="card-body">
							Model Option Will goes here
						</div>
						<div class="card-footer text-right">
							<button class="btn btn-primary btn-sm createbtns" id="save_model_request">Generate Model</button>
						</div>
					</div>
				</div>
				<div class="card-footer text-right">
					<button class="btn btn-primary btn-sm createbtns">Generate All Resources</button>
				</div>
			</div>
		</div>
	</div>
	
</div>
<script type="text/javascript">
	function start_process(classid){
		$(classid).attr("disabled","");
	}
	function end_process(classid){
		$(classid).removeAttr("disabled");
	}
	$(document).ready(function(){
		$(".createbtns").attr('disabled','');
	});
	$(document).on("click","#save_model_request",function(e){
		start_process("#save_model_request");
		var tablename=$("#tablename").text();
		$.ajax({
			'type': 'POST',
			'dataType': 'JSON',
			'url': 'cicrud/save_model_request',
			'data':{'tablename':tablename}
		})
		.done(function(data){
			end_process("#save_model_request");
		});
	});
	$(document).on("click",".mytable",function(e){
		e.preventDefault();
		$(".createbtns").attr('disabled','');
		var tablename=$(this).attr('data-table');
		$("#tablename").text(tablename);
		$('.mytable').removeClass('active');
		$(this).addClass('active');
		$.ajax({
			'type': 'GET',
			'dataType': 'JSON',
			'url': 'cicrud/get_attributes/'+tablename
		})
		.done(function(data){
			$(".createbtns").removeAttr('disabled');
			var tbodyy='';
			var sr=0;
			$.each(data['mdata'],function(index,element){
				sr++;
				tbodyy+='<tr>';
				tbodyy+='<td>'+sr+'</td>';
				tbodyy+='<td>'+element.column_text+'</td>';
				
				if(element.form_field=="PRIMARY"){
					tbodyy+='<td>PRIMARY<input type="hidden" name="formfield[]" value="" /></td>';
					tbodyy+='<td>INT<input type="hidden" name="fieldtype[]" value="" /></td>';
					tbodyy+='<td>AUTO INCREMENT<input type="hidden" name="required[]" value="" /></td>';
				}
				else if(element.form_field=="DEFAULT"){
					tbodyy+='<td><input type="hidden" name="formfield[]" value="" /></td>';
					tbodyy+='<td><input type="hidden" name="fieldtype[]" value="" /></td>';
					tbodyy+='<td><input type="hidden" name="required[]" value="" /></td>';
				}
				else{
					if(element.required!=""){
						tbodyy+='<td><select name="formfield[]"><option selected>Yes</option><option>No</option></select></td>';
					}
					else{
						tbodyy+='<td><select name="formfield[]"><option>Yes</option><option selected>No</option></select></td>';
					}
					tbodyy+='<td><select name="fieldtype[]">'+"\n";
					$.each(data['fieldtypes'], function(ind,ele){
						if(ele==element.form_field){
							tbodyy+="\n"+'<option selected>'+ele+'</option>';
						}
						else{
							tbodyy+="\n"+'<option>'+ele+'</option>';
						}
						
					});
					tbodyy+='</select></td>';

					if(element.required!=""){
						tbodyy+='<td><select name="required[]"><option selected>Yes</option><option>No</option></select></td>';
					}
					else{
						tbodyy+='<td><select name="required[]"><option>Yes</option><option selected>No</option></select></td>';
					}
				}
				
				tbodyy+='</tr>';
			});
			$("#loadformdata").html(tbodyy);
		});
	});
</script>
</body>
</html>
