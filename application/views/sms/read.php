<!-- Main content -->
<section class="content">
    <div class="row">
        <div class="col-xs-12">
            <div class="box">
                <div class="box-header">
                </div><!-- /.box-header -->
                <div class="box-body">
                    <div class="box box-primary">
                        <?php echo form_open($this->uri->segment(1).'/read'); ?>
                        <?php 
                        echo form_hidden('no_hp',$row['SenderNumber']);
                        ?>
                        <div class="box-body">
                            <table class="table table-bordered">
                                <tr><td width="150">Pengirim</td><td><?php echo $row['SenderNumber']; ?></td></tr>
                                <tr><td width="150">Pesan</td><td><?php echo $row['TextDecoded']; ?></td></tr>

                                <tr><td colspan="2"><textarea name="pesan" class="form-control" placeholder="Tulis Balasan"></textarea></td></tr>
                                <tr><td colspan="2"><button type="submit" name="submit" class="btn btn-primary btn-sm"><i class="fa fa-floppy-o"></i> Kirim Balasan</button>
                                        <?php echo anchor($this->uri->segment(1), '<i class="fa fa-sign-out"></i> Kembali</a>', array('class' => 'btn btn-primary btn-sm')); ?></td></tr>
                            </table>
                        </div>
                        </form>
                        <!-- /.box-body -->
                    </div><!-- /.box -->
                </div><!-- /.col -->
            </div><!-- /.row -->
            </section><!-- /.content -->