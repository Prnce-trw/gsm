<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <style>
        .content {
            max-width: 800px;
            width: 100%;
            margin: 0px auto;
            margin-bottom: 60px;
        }

        /* Outer */
        .modal {
            width: 100%;
            height: 100%;
            display: none;
            position: fixed;
            top: 0px;
            left: 0px;
            right: 0;
            bottom: 0;
            background: rgba(0, 0, 0, 0.2);
            z-index: 99999;
        }

        /* Inner */
        .modal-inner {
            width: 500px;
            position: relative;
            margin: 10% auto;
            -webkit-border-radius: 5px;
            -moz-border-radius: 5px;
            border-radius: 5px;
            background: #fff;
        }

        /* Close Button */
        .modal-close {
            width: 30px;
            height: 30px;
            padding-top: 4px;
            display: inline-block;
            position: absolute;
            top: 0px;
            right: 0px;
            transition: ease 0.25s all;
            -webkit-transform: translate(50%, -50%);
            transform: translate(50%, -50%);
            border-radius: 1000px;
            background: rgba(0, 0, 0, 0.8);
            font-family: Arial, Sans-Serif;
            font-size: 20px;
            text-align: center;
            line-height: 100%;
            color: #fff;
        }

        .modal-close:hover {
            -webkit-transform: translate(50%, -50%) rotate(180deg);
            transform: translate(50%, -50%) rotate(180deg);
            background: rgba(0, 0, 0, 1);
            text-decoration: none;
        }
    </style>
</head>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
<div class="container" style="width:700px;">
    <h3 align="center">PHP Ajax Update MySQL Data Through Bootstrap Modal</h3>
    <br />
    <div class="table-responsive">
        <div align="right">
            <button type="button" name="add" id="add" data-toggle="modal" data-modal-open="add_data_modal"
                class="btn btn-warning">Add</button>
        </div>
        <br />
        <div id="employee_table">
            <table class="table table-bordered">
                <tr>
                    <th width="70%">Employee Name</th>
                    <th width="15%">Edit</th>
                    <th width="15%">View</th>
                </tr>
                <?php  
                               while ($stmt->fetch()) {
                               ?>
                <tr>
                    <td><?php echo $name; ?></td>
                    <td><input type="button" name="edit" value="Edit" id="<?php echo $id; ?>"
                            class="btn btn-info btn-xs edit_data" /></td>
                    <td><input type="button" name="view" value="view" id="<?php echo $id; ?>"
                            class="btn btn-info btn-xs view_data" /></td>
                </tr>
                <?php  
                               }  
                               ?>
            </table>
        </div>
    </div>
</div>
</body>

</html>

<div id="dataModal" class="modal" data-modal="empdetail">
    <div class="modal-inner">
        <div class="modal-content">
            <div class="modal-header">
                <a class="modal-close" data-modal-close="empdetail" href="#">x</a>
                <h4 class="modal-title">Employee Details</h4>
            </div>
            <div class="modal-body" id="employee_detail">
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-modal-close="empdetail">Close</button>

            </div>
        </div>
    </div>
</div>
<div id="add_data_Modal" class="modal" data-modal="add_data_modal">
    <div class="modal-inner">
        <div class="modal-content">
            <div class="modal-header">
                <a class="modal-close" data-modal-close="add_data_modal" href="#">x</a>

                <h4 class="modal-title">PHP Ajax Update MySQL Data Through Bootstrap Modal</h4>
            </div>
            <div class="modal-body">
                <form method="post" id="insert_form">
                    <label>Enter Employee Name</label>
                    <input type="text" name="name" id="name" class="form-control" />
                    <br />
                    <label>Enter Employee Address</label>
                    <textarea name="address" id="address" class="form-control"></textarea>
                    <br />
                    <label>Select Gender</label>
                    <select name="gender" id="gender" class="form-control">
                        <option value="Male">Male</option>
                        <option value="Female">Female</option>
                    </select>
                    <br />
                    <label>Enter Designation</label>
                    <input type="text" name="designation" id="designation" class="form-control" />
                    <br />
                    <label>Enter Age</label>
                    <input type="text" name="age" id="age" class="form-control" />
                    <br />
                    <input type="hidden" name="employee_id" id="employee_id" />
                    <input type="submit" name="insert" id="insert" value="Insert" class="btn btn-success" />
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-modal-close="add_data_modal" href="#">Close</button>
            </div>
        </div>
    </div>
</div>

<body>
    <script>
        $(function() {
  //----- OPEN
  $('[data-modal-open]').on('click', function(e)  {
    $('#insert_form')[0].reset();
    var targeted_modal_class = jQuery(this).attr('data-modal-open');

    $('[data-modal="' + targeted_modal_class + '"]').fadeIn(350);
    e.preventDefault();
  });

  //----- CLOSE
  $('[data-modal-close]').on('click', function(e)  {
    var targeted_modal_class = jQuery(this).attr('data-modal-close');
    $('[data-modal="' + targeted_modal_class + '"]').fadeOut(350);

    e.preventDefault();
  });
});
  $(document).ready(function(){  
      $('#add').click(function(){  

                     $('#employee_id').val("");
                     $('#insert').val("Insert");
           $('#insert_form')[0].reset();  
      });  
      $(document).on('click', '.edit_data', function(){ 

           var employee_id = $(this).attr("id");  
           $.ajax({  
                url:"fetch.php",  
                method:"POST",  
                data:{employee_id:employee_id},  
                dataType:"json"})  
                .done(function(data){  
                     $('#name').val(data.name);  
                     $('#address').val(data.address);  
                     $('#gender').val(data.gender);  
                     $('#designation').val(data.designation);  
                     $('#age').val(data.age);  
                     $('#employee_id').val(data.id);  
                     $('#insert').val("Update");  
                     $('[data-modal="add_data_modal"]').fadeIn(350);

      
                }).fail(function() {
    				alert( "Ajax Request fail" );
  				})

            
      });  
      $('#insert_form').on("submit", function(event){  
           event.preventDefault();  

            
           if($('#name').val() == "")  
           {  
                alert("Name is required");  
           }  
           else if($('#address').val() == '')  
           {  
                alert("Address is required");  
           }  
           else if($('#designation').val() == '')  
           {  
                alert("Designation is required");  
           }  
           else if($('#age').val() == '')  
           {  
                alert("Age is required");  
           }  
           else  
           {  
                $.ajax({  
                     url:"insert.php",  
                     method:"POST",  
                     data:$('#insert_form').serialize(), 
                     beforeSend:function(){  
                          $('#insert').val("Inserting");  
                     }
                     })  
                     .done(function(data){  
                         
                          $('#insert_form')[0].reset();  
                              $('[data-modal="add_data_modal"]').fadeOut(350);

                          $('#employee_table').html(data);      
                     })  
                     .fail(function() {
   						 alert( "Insert ajax request fail" );
  								}) 
                  
                  
           }
      });  
      $(document).on('click', '.view_data', function(){  
           var employee_id = $(this).attr("id");  
           if(employee_id != '')  
           {  
                $.ajax({  
                     url:"select.php",  
                     method:"POST",  
                     data:{employee_id:employee_id}})  
                     .done(function(data){
                            $('[data-modal="empdetail"]').fadeIn(350);
                            $('#employee_detail').html(data);  
      
                     }) .fail(function() {
   						 alert( "Select ajax request fail" );
  								}) 
                     
           }            
      });  
 });
    </script>
</body>

</html>