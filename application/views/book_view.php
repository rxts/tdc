<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Thư viện gia đình</title>
        <link href="<?php echo base_url('assests/bootstrap/css/bootstrap.min.css') ?>" rel="stylesheet">
        <link href="<?php echo base_url('assests/datatables/css/dataTables.bootstrap.css') ?>" rel="stylesheet">
        <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
        <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
        <!--[if lt IE 9]>
          <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
          <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
        <![endif]-->
    </head>
    <body>


        <div class="container">
            <h1>Tủ sách của Vũ</h1>
        </center>
        <h3>Có những quyển như sau</h3>
        <br />
        <button class="btn btn-success" onclick="add_book()"><i class="glyphicon glyphicon-plus"></i> Thêm sách</button>
        <br />
        <br />
        <table id="table_id" class="table table-striped table-bordered" cellspacing="0" width="100%">
            <thead>
                <tr>
                    <th>STT</th>
                    <th>ISBN</th>
                    <th>Tựa</th>
                    <th>Tác giả</th>
                    <th>Thể loại</th>

                    <th style="width:125px;">Thao tác
                        </p></th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($books as $book) { ?>
                    <tr>
                        <td><?php echo $book->book_id; ?></td>
                        <td><?php echo $book->book_isbn; ?></td>
                        <td class="book-title" book_id="<?php echo $book->book_id; ?>"><?php echo $book->book_title; ?></td>
                        <td><?php echo $book->book_author; ?></td>
                        <td><?php echo $book->book_category; ?></td>
                        <td>
                            <button class="btn btn-warning" onclick="edit_book(<?php echo $book->book_id; ?>)"><i class="glyphicon glyphicon-pencil"></i></button>
                            <button class="btn btn-danger" onclick="confirm_delete(<?php echo $book->book_id; ?>)"><i class="glyphicon glyphicon-remove"></i></button>


                        </td>
                    </tr>
                <?php } ?>



            </tbody>

            <tfoot>
                <tr>
                    <th>STT</th>
                    <th>ISBN</th>
                    <th>Tựa</th>
                    <th>Tác giả</th>
                    <th>Thể loại</th>
                </tr>
            </tfoot>
        </table>

    </div>

    <script src="<?php echo base_url('assests/jquery/jquery-3.1.0.min.js') ?>"></script>
    <script src="<?php echo base_url('assests/bootstrap/js/bootstrap.min.js') ?>"></script>
    <script src="<?php echo base_url('assests/datatables/js/jquery.dataTables.min.js') ?>"></script>
    <script src="<?php echo base_url('assests/datatables/js/dataTables.bootstrap.js') ?>"></script>


    <script type="text/javascript">
        $(document).ready(function () {
            $('#table_id').DataTable();
        });
        var save_method; //for save method string
        var table;


        function add_book()
        {
            save_method = 'add';
            $('#form')[0].reset(); // reset form on modals
            $('#modal_form').modal('show'); // show bootstrap modal
            //$('.modal-title').text('Add Person'); // Set Title to Bootstrap modal title
        }

        function edit_book(id)
        {
            save_method = 'update';
            $('#form')[0].reset(); // reset form on modals

            //Ajax Load data from ajax
            $.ajax({
                url: "<?php echo site_url('index.php/book/ajax_edit/') ?>/" + id,
                type: "GET",
                dataType: "JSON",
                success: function (data)
                {

                    $('[name="book_id"]').val(data.book_id);
                    $('[name="book_isbn"]').val(data.book_isbn);
                    $('[name="book_title"]').val(data.book_title);
                    $('[name="book_author"]').val(data.book_author);
                    $('[name="book_category"]').val(data.book_category);


                    $('#modal_form').modal('show'); // show bootstrap modal when complete loaded
                    $('.modal-title').text('Chỉnh sửa'); // Set title to Bootstrap modal title

                },
                error: function (jqXHR, textStatus, errorThrown)
                {
                    alert('Error get data from ajax');
                }
            });
        }

        function get_book_title(id) {
            var the_title;
            $('.book-title').each(function () {
                if ($(this).attr('book_id') == id) {
                    the_title = $(this).text();
                }
            });
            return the_title;
        }

        function save()
        {
            var url;
            if (save_method == 'add')
            {
                url = "<?php echo site_url('index.php/book/book_add') ?>";
            } else
            {
                url = "<?php echo site_url('index.php/book/book_update') ?>";
            }

            // ajax adding data to database
            $.ajax({
                url: url,
                type: "POST",
                data: $('#form').serialize(),
                dataType: "JSON",
                success: function (data)
                {
                    //if success close modal and reload ajax table
                    $('#modal_form').modal('hide');
                    location.reload();// for reload a page
                },
                error: function (jqXHR, textStatus, errorThrown)
                {
                    alert('Error adding / update data');
                }
            });
        }


        function confirm_delete(id) {
            save_method = 'delete';
            $('#form')[0].reset(); // reset form on modals
            $('#modal_form').modal('show'); // show bootstrap modal
            $('.modal-title').text('Delete this book');
            $('.form-body').text('Do you want to delete: "' + get_book_title(id) + '"');
            $('.modal-footer').html('<button type="button" id="btnConfirmDelete" class="btn btn-danger">Delete</button><button type="button" class="btn btn-primary" data-dismiss="modal">Cancel</button>');
            $('#btnConfirmDelete').on('click', function () {
                delete_book(id);
            });
        }
        function delete_book(id)
        {
            $.ajax({
                url: "<?php echo site_url('index.php/book/book_delete') ?>/" + id,
                type: "POST",
                dataType: "JSON",
                success: function (data)
                {
                    $('#modal_delete').modal('hide');
                    location.reload();
                },
                error: function (jqXHR, textStatus, errorThrown)
                {
                    alert('Error deleting data');
                }
            });
        }
    </script>

    <!-- Bootstrap modal -->
    <div class="modal fade" id="modal_form" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h3 class="modal-title">Thông tin sách</h3>
                </div>
                <div class="modal-body form">
                    <form action="#" id="form" class="form-horizontal">
                        <input type="hidden" value="" name="book_id"/>
                        <div class="form-body">
                            <div class="form-group">
                                <label class="control-label col-md-3">ISBN</label>
                                <div class="col-md-9">
                                    <input name="book_isbn" placeholder="Book ISBN" class="form-control" type="text">
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="control-label col-md-3">Tựa</label>
                                <div class="col-md-9">
                                    <input name="book_title" placeholder="Book_title" class="form-control" type="text">
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="control-label col-md-3">Tác giả</label>
                                <div class="col-md-9">
                                    <input name="book_author" placeholder="Book Author" class="form-control" type="text">

                                </div>
                            </div>
                            <div class="form-group">
                                <label class="control-label col-md-3">Thể loại</label>
                                <div class="col-md-9">
                                    <input name="book_category" placeholder="Book Category" class="form-control" type="text">

                                </div>
                            </div>

                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" id="btnSave" onclick="save()" class="btn btn-primary">Lưu lại</button>
                    <button type="button" class="btn btn-danger" data-dismiss="modal">Thoát</button>
                </div>
            </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </div><!-- /.modal -->
    <!-- End Bootstrap modal -->
    
    
    
    
    <!-- Bootstrap modal -->
    <div class="modal fade" id="modal_delete" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h3 class="modal-title">Do you want to delete this book?</h3>
                </div>
                <div class="modal-footer">
                    <button type="button" id="btnConfirmDelete" class="btn btn-danger">Delete</button>
                    <button type="button" class="btn btn-danger" data-dismiss="modal">Cancel</button>
                </div>
            </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </div><!-- /.modal -->
    <!-- End Bootstrap modal -->

</body>
</html>
