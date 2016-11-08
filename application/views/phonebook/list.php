<!-- Main content -->
<section class="content">
    <div class="row">
        <div class="col-xs-12">
            <div class="box">
                <div class="box-header">
                    <h3 class="box-title">
                        <?php echo menu_title($this->uri->segment(1), $this->uri->segment(2)); ?>
                        <?php echo anchor($this->uri->segment(1) . '/add', '<i class="fa fa-pencil-square-o"></i> Tambah Data', array('class' => 'btn btn-danger btn-sm')); ?>
                        <!-- Button trigger modal -->
                        <button type="button" class="btn btn-primary btn-sm" data-toggle="modal" data-target="#myModal">
                            Import Data
                        </button>

                    </h3>
                </div><!-- /.box-header -->
                <div class="box-body">
                    <table id="mytable" class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th>NO</th>
                                <th width='100'>NAMA</th>
                                <th>NO HP</th>
                                <th  width='100'>GROUP</th>
                                <th width="104"></th>
                            </tr>
                    </table>
                </div><!-- /.box-body -->
            </div><!-- /.box -->
        </div><!-- /.col -->
    </div><!-- /.row -->
</section><!-- /.content -->

<script src="<?php echo base_url('tutor/js/jquery-1.11.2.min.js') ?>"></script>
<script src="<?php echo base_url('tutor/datatables/jquery.dataTables.js') ?>"></script>
<script src="<?php echo base_url('tutor/datatables/dataTables.bootstrap.js') ?>"></script>
<script type="text/javascript">
    $(document).ready(function () {

        $.fn.dataTableExt.oApi.fnPagingInfo = function (oSettings)
        {
            return {
                "iStart": oSettings._iDisplayStart,
                "iEnd": oSettings.fnDisplayEnd(),
                "iLength": oSettings._iDisplayLength,
                "iTotal": oSettings.fnRecordsTotal(),
                "iFilteredTotal": oSettings.fnRecordsDisplay(),
                "iPage": Math.ceil(oSettings._iDisplayStart / oSettings._iDisplayLength),
                "iTotalPages": Math.ceil(oSettings.fnRecordsDisplay() / oSettings._iDisplayLength)
            };
        };

        var t = $('#mytable').DataTable({
            "processing": true,
            "serverSide": true,
            "ajax": '<?php echo site_url($this->uri->segment(1) . '/data'); ?>',
            "columns": [
                {
                    "data": null,
                    'width': 20,
                    "class": "text-center",
                    "orderable": false
                },
                {"data": "Name", width: 700},
                {"data": "Number"},
                {"data": "GroupName", "width": 60},
                {
                    "class": "text-center",
                    "data": "aksi",
                    'width': 50
                }
            ],
            "order": [[3, 'desc']],
            "rowCallback": function (row, data, iDisplayIndex) {
                var info = this.fnPagingInfo();
                var page = info.iPage;
                var length = info.iLength;
                var index = page * length + (iDisplayIndex + 1);
                $('td:eq(0)', row).html(index);
            }
        });
    });
</script>

<!-- Modal -->
<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog" role="document">
        <?php echo form_open_multipart('phonebook/import'); ?>
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="myModalLabel">Import Data Kontak</h4>
            </div>
            <div class="modal-body">
                <table class="table table-bordered">
                    <tr><td>Pilih File Excel</td><td><input type="file" name="file"></td></tr>
                    <tr><td>Nama Group</td><td><input type="text" name="group" class="form-control" placeholder="Nama Group baru"></td></tr>
                    <tr><td></td><td><?php echo anchor(base_url() . 'template.xlsx', 'Download Template Excel', "target='New'") ?></td></tr>
                </table>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-info" data-dismiss="modal">Tutup</button>
                <button type="submit" class="btn btn-danger">Upload Data</button>
            </div>
        </div>
        </form>
    </div>
</div>
