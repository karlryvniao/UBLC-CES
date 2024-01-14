<?php
    session_start();
    include "../../connection/connection.php";
    include '../../session.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<link rel="stylesheet" href="../../style/bootstrap-5.3.2-dist/css/bootstrap.css">
<link rel="stylesheet" href="../../style/bootstrap-5.3.2-dist/css/sidebar.css">
<link rel="stylesheet" href="./../style/bootstrap-5.3.2-dist/css/font-awesome.min.css">
<link rel="stylesheet" href="../../style/bootstrap-5.3.2-dist/css/line-awesome.min.css">
<link rel="stylesheet" href="../../style/bootstrap-5.3.2-dist/css/select2.min.css">
<link rel="stylesheet" href="../../style/morris/morris.css">
<script src="https://kit.fontawesome.com/6b23de7647.js" crossorigin="anonymous"></script>
<title>Document</title>
</head>
<body>
<!-- Main Wrapper -->
<div class="main-wrapper">
        <?php include_once("includes/header.php");?>
        <?php include_once("includes/sidebar.php");?>
        <div class="page-wrapper">
    

        <div class="content container-fluid">
      
        <!-- Page Header -->
        <div class="page-header">
          <div class="row align-items-center">
            <div class="col">
              <h3 class="page-title">Community Extension Project Plan 2022/2023</h3>
              <ul class="breadcrumb">
                <li class="breadcrumb-item"><a href="index.php">Dashboard</a></li>
                <li class="breadcrumb-item active">Clients</li>
              </ul>
            </div>
          </div>
        </div>
        <!-- /Page Header -->
        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                    </div>
                </div>
            </div><!-- /.container-fluid -->
        </section>

        <!-- Main content -->
        <section class="content">
        <div class="container-fluid">
	<div class="row">
		<div class="col-md-4">
			<div class="card card-info card-primary">
				<div class="card-header">
					<b>Question Form</b>
				</div>
				<div class="card-body">
					<form action="" id="manage-question">
						<input type="hidden" name="academic_id" value="<?php echo isset($id) ? $id : '' ?>">
						<input type="hidden" name="id" value="">
						<div class="form-group">
							<label for="">Criteria</label>
							<select name="criteria_id" id="criteria_id" class="custom-select custom-select-sm select2">
								<option value=""></option>
							<?php 
								$criteria = $conn->query("SELECT * FROM criteria_list order by abs(order_by) asc ");
								while($row = $criteria->fetch_assoc()):
							?>
							<option value="<?php echo $row['id'] ?>"><?php echo $row['criteria'] ?></option>
							<?php endwhile; ?>
							</select>
						</div>
						<div class="form-group">
							<label for="">Question</label>
							<textarea name="question" id="question" cols="30" rows="4" class="form-control" required=""><?php echo isset($question) ? $question : '' ?></textarea>
						</div>
					</form>
				</div>
				<div class="card-footer">
					<div class="d-flex justify-content-end w-100">
						<button class="btn btn-sm btn-primary btn-flat bg-gradient-primary mx-1" form="manage-question">Save</button>
						<button class="btn btn-sm btn-flat btn-secondary bg-gradient-secondary mx-1" form="manage-question" type="reset">Cancel</button>
					</div>
				</div>
			</div>
		</div>
		<div class="col-md-8">
			<div class="card card-outline card-info">
				<div class="card-header">
					<b>Evaluation Questionnaire for Academic: <?php echo $year.' '.(ordinal_suffix($semester)) ?> </b>
					<div class="card-tools">
						<button class="btn btn-sm btn-flat btn-primary bg-gradient-primary mx-1" id="eval_restrict" type="button">Evaluation Restriction</button>
						<button class="btn btn-sm btn-flat btn-success bg-gradient-success mx-1" form="order-question">Save Order</button>
					</div>
				</div>
				<div class="card-body">
					<fieldset class="border border-info p-2 w-100">
					   <legend  class="w-auto">Rating Legend</legend>
					   <p>5 = Strongly Agree, 4 = Agree, 3 = Uncertain, 2 = Disagree, 1 = Strongly Disagree</p>
					</fieldset>
					<form id="order-question">
					<div class="clear-fix mt-2"></div>
					<?php 
							$q_arr = array();
						$criteria = $conn->query("SELECT * FROM criteria_list order by abs(order_by) asc ");
						while($crow = $criteria->fetch_assoc()):
					?>
					<table class="table table-condensed">
						<thead>
							<tr class="bg-gradient-secondary">
								<th colspan="2" class=" p-1"><b><?php echo $crow['criteria'] ?></b></th>
								<th class="text-center">5</th>
								<th class="text-center">4</th>
								<th class="text-center">3</th>
								<th class="text-center">2</th>
								<th class="text-center">1</th>
							</tr>
						</thead>
						<tbody class="tr-sortable">
							<?php 
							$questions = $conn->query("SELECT * FROM question_list where criteria_id = {$crow['id']} and academic_id = $id order by abs(order_by) asc ");
							while($row=$questions->fetch_assoc()):
							$q_arr[$row['id']] = $row;
							?>
							<tr class="bg-white">
								<td class="p-1 text-center" width="5px">
									<span class="btn-group dropright">
									  <span type="button" class="btn" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
									   <i class="fa fa-ellipsis-v"></i>
									  </span>
									  <div class="dropdown-menu">
									     <a class="dropdown-item edit_question" href="javascript:void(0)" data-id="<?php echo $row['id'] ?>">Edit</a>
					                      <div class="dropdown-divider"></div>
					                     <a class="dropdown-item delete_question" href="javascript:void(0)" data-id="<?php echo $row['id'] ?>">Delete  </a>
									  </div>
									</span>
								</td>
								<td class="p-1" width="40%">
									<?php echo $row['question'] ?>
									<input type="hidden" name="qid[]" value="<?php echo $row['id'] ?>">
								</td>
								<?php for($c=0;$c<5;$c++): ?>
								<td class="text-center">
									<div class="icheck-success d-inline">
				                        <input type="radio" name="qid[<?php echo $row['id'] ?>][]" id="qradio<?php echo $row['id'].'_'.$c ?>">
				                        <label for="qradio<?php echo $row['id'].'_'.$c ?>">
				                        </label>
			                      </div>
								</td>
								<?php endfor; ?>
							</tr>
							<?php endwhile; ?>
						</tbody>
					</table>
					<?php endwhile; ?>
					</form>
				</div>
			</div>
		</div>
	</div>
</div>

        </section>

            
    <!-- /Page Wrapper -->
    
      </div>
    <script src="../../style/bootstrap-5.3.2-dist/js/jquery-3.2.1.min.js"></script>
    <script src="../../style/bootstrap-5.3.2-dist/js/popper.min.js"></script>
    <script src="../../style/bootstrap-5.3.2-dist/js/bootstrap.bundle.js"></script>
    <script src="../../style/bootstrap-5.3.2-dist/js/jquery.slimscroll.min.js"></script>
    <script src="../../style/morris/morris.min.js"></script>
    <script src="../../style/bootstrap-5.3.2-dist/js/nav.js"></script>
    <script src="../../style/notification/notification.js"></script>

</body>
</html>