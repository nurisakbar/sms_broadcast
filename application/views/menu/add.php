<!-- Main content -->
<section class="content">
    <div class="row">
        <div class="col-xs-12">
            <div class="box">
                <div class="box-header">
                </div><!-- /.box-header -->
                <div class="box-body">
                    <div class="box box-primary">
                        <?php echo form_open($this->uri->segment(1).'/add'); ?>
                        <div class="box-body">
                            <table class="table table-bordered">
                                <tr><td width="150">Judul Menu</td><td><?php echo form_input('judul_menu', null, 'required class="form-control" placeholder="Judul Menu"'); ?></td></tr>
                                <tr><td width="150">Link</td><td><?php echo form_input('link', null, 'required class="form-control" placeholder="Link"'); ?></td></tr>
                                <tr><td width="150">Icon</td><td><?php echo form_input('icon', null, 'required class="form-control" placeholder="Icon"'); ?></td></tr>
                                <tr><td width="150">Icon</td><td><?php echo cmb_dinamis('is_main_menu', 'tabel_menu', 'judul_menu', 'id', ''); ?></td></tr>
                                <tr><td width="150">Publish</td><td><?php echo form_dropdown('publish',array(1=>'Ya',0=>'Tidak'),'',"class='form-control'"); ?></td></tr>
                                <tr><td></td><td><button type="submit" name="submit" class="btn btn-primary btn-sm"><i class="fa fa-floppy-o"></i> Simpan</button>
                                        <?php echo anchor($this->uri->segment(1), '<i class="fa fa-sign-out"></i> Kembali</a>', array('class' => 'btn btn-primary btn-sm')); ?></td></tr>
                            </table>
                        </div>
                        </form>
                        <!-- /.box-body -->
                    </div><!-- /.box -->
                </div><!-- /.col -->
            </div><!-- /.row -->
            </section><!-- /.content -->