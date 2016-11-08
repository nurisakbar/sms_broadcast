<!-- Main content -->
<section class="content">
    <div class="row">
        <div class="col-xs-12">
            <div class="box">
                <div class="box-header">
                </div><!-- /.box-header -->
                <div class="box-body">
                    <div class="box box-primary">
                        <?php echo form_open($this->uri->segment(1) . '/edit'); ?>
                        <?php echo form_hidden('id', $row['ID']); ?>
                        <div class="box-body">
                            <table class="table table-bordered">
                                <tr><td width="150">Nama</td><td><?php echo form_input('Name', $row['Name'], 'required class="form-control" placeholder="Nama"'); ?></td></tr>
                                <tr><td width="150">No HP</td><td><?php echo form_input('Number', $row['Number'], 'required class="form-control" placeholder="NO HP"'); ?></td></tr>
                                <tr><td width="150">Group</td><td><?php echo cmb_dinamis('GroupID', 'pbk_groups', 'Name', 'ID', $row['GroupID']); ?></td></tr>
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