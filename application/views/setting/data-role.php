<div class="row">
	<div class="col-md-8 col-md-offset-2 col-xs-12"><?php echo $this->session->flashdata('alert'); ?></div>
	<div class="col-md-12">
		<div class="box box-primary">
			<div class="box-header with-border">
				<div class="col-md-7">
					<h3 class="box-title">Hak Akses Pengguna Sistem</h3>
				</div>
			</div>
			<div class="box-body">
				<?php if(count($this->role->get_role()) <= 10) : ?>
				<div class="btn-group col-md-2">
					<a href="<?php echo site_url('role/create') ?>" class="btn btn-default btn-flat btn-sm"><i class="fa fa-plus"></i> Tambah Baru</a>	
				</div>
				<?php endif; ?>
<?php  
// End form pencarian
echo form_close();

/**
 * Start Form Multiple Action
 *
 * @return string
 **/
echo form_open(site_url('user/bulk_action'));
?>
				<table class="table table-hover table-bordered col-md-12" style="margin-top: 10px;">
					<thead class="bg-silver">
						<tr>
							<th class="text-center">No.</th>
							<th class="text-center">Grup Pengguna</th>
							<th class="text-center">Deskripsi</th>
							<th class="text-center">Jumlah Pengguna</th>
							<th></th>
						</tr>
					</thead>
					<tbody>
				<?php  
				/**
				 * Loop Role Data
				 *
				 * @var string
				 **/
				foreach($this->role->get_role() as $key => $value) :
				?>
					<tr>
						<td class="text-center"><?php echo ++$key; ?>.</td>
						<td class="text-center"><?php echo $value->role_name; ?></td>
						<td class="text-center"><small><?php echo $value->role_description; ?></small></td>
						<td class="text-center"><?php echo $this->db->get_where('users', array('role_id' => $value->role_id))->num_rows(); ?></td>
						<td class="text-center" width="80">
					<?php  
					if($value->role_id > 1) :
					?>
							<a href="<?php echo site_url("role/update/{$value->role_id}") ?>" class="icon-button text-blue" data-toggle="tooltip" data-placement="top" title="Sunting"><i class="fa fa-pencil"></i></a>
							<a class="icon-button text-red get-delete-role" data-id="<?php echo $value->role_id; ?>" data-toggle="tooltip" data-placement="top" title="Hapus"><i class="fa fa-trash-o"></i></a>
					<?php  
					endif;
					?>
						</td>
					</tr>
				<?php  
				endforeach;
				?>
					</tbody>

				</table>

				<div class="modal animated fadeIn modal-danger" id="modal-delete-user-multiple" tabindex="-1" data-backdrop="static" data-keyboard="false">
				    <div class="modal-dialog modal-sm">
				        <div class="modal-content">
				           	<div class="modal-header">
				                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				                <h4 class="modal-title"><i class="fa fa-question-circle"></i> Hapus!</h4>
				                <span>Hapus pengguna ini dari sistem?</span>
				           	</div>
				           	<div class="modal-footer">
				                <button type="button" class="btn btn-outline pull-left" data-dismiss="modal">Tidak</button>
				                <button type="submit" name="action" value="delete" id="btn-delete" class="btn btn-outline"> Hapus </button>
				           	</div>
				        </div>
				    </div>
				</div>
<?php  
// End Form Multiple Action
echo form_close();
?>
			</div>
			<div class="box-footer text-center">
				<?php echo $this->pagination->create_links(); ?>
			</div>
		</div>
	</div>
</div>



<div class="modal animated fadeIn modal-danger" id="modal-delete-role" tabindex="-1" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog modal-sm">
        <div class="modal-content">
           	<div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title"><i class="fa fa-question-circle"></i> Hapus!</h4>
                <span>Hapus pengguna ini dari sistem?</span>
           	</div>
           	<div class="modal-footer">
                <button type="button" class="btn btn-outline pull-left" data-dismiss="modal">Tidak</button>
                <a href="#" id="btn-delete" class="btn btn-outline"> Hapus </a>
           	</div>
        </div>
    </div>
</div>