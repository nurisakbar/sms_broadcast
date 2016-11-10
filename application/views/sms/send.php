<!-- Main content -->
<section class="content">
    <div class="row">
        <div class="col-xs-12">
            <div class="box">
                <div class="box-header">
                    <h3 class="box-title">
                        <?php echo menu_title($this->uri->segment(1), $this->uri->segment(2)); ?>
                        <?php //echo anchor($this->uri->segment(1) . '/add', '<i class="fa fa-pencil-square-o"></i> Tambah Data', array('class' => 'btn btn-danger btn-sm')); ?>

                    </h3>
                </div><!-- /.box-header -->
                <div class="box-body">
                    <div class="box box-primary">
                        <?php 
                        echo form_open($this->uri->segment(1) . '/send');
                        ?>
                        <div class="box-body">
                            <table class="table table-bordered">
                                <tr><td width="150">Group</td><td><?php echo cmb_dinamis('GroupID', 'pbk_groups', 'Name', 'ID', ''); ?></td></tr>
                                <tr><td width="150">Isi Pesan</td><td><?php echo form_textarea('message', null, 'required class="form-control" placeholder="isi pesan"'); ?></td></tr>
                                <tr><td></td><td><button type="submit" name="submit" class="btn btn-primary btn-sm"><i class="fa fa-floppy-o"></i> Kirim Pesan</button>
                                        <?php echo anchor($this->uri->segment(1), '<i class="fa fa-sign-out"></i> Kembali</a>', array('class' => 'btn btn-primary btn-sm')); ?></td></tr>
                            </table>
                        </div>
                        </form>
                        <!-- /.box-body -->
                    </div><!-- /.box -->
                </div><!-- /.col -->
            </div><!-- /.row -->
            </section><!-- /.content -->