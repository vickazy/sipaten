<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Create_surat extends Sipaten 
{
	public $now_date;

	public function __construct()
	{
		parent::__construct();

		$this->breadcrumbs->unshift(1, 'Surat Keterangan', "create_surat/index/{$this->uri->segment(3)}");

		$this->load->model('mcreate_surat', 'create_surat');

		$this->now_date = date('Y-m-d');

		if($this->uri->segment(2) == FALSE)
			show_404();
	}

	public function index($param = 0)
	{
		if($this->create_surat->surat_category($param, 'non perizinan') == FALSE)
			show_404();

		$this->load->js(base_url("public/app/requirment_surat.js"));

		$surat = $this->create_surat->surat_category($param, 'non perizinan');

		$this->page_title->push('Surat', $surat->nama_kategori);

		$this->breadcrumbs->unshift(2, $surat->nama_kategori, "create_surat/index/{$param}");

		$this->data = array(
			'title' => $surat->nama_kategori, 
			'breadcrumb' => $this->breadcrumbs->show(),
			'page_title' => $this->page_title->show(),
			'pegawai' => $this->create_surat->pegawai(),
			'syarat' => $this->create_surat->get_syarat($surat->syarat),
			'get' => $surat
		);

		$this->template->view('create-surat/insert-requerment', $this->data);
	}

	/**
	 * Menghapus Log Syarat Pengajuan Surat
	 * Menjadi log_surat 
	 *
	 * @return string
	 **/
	public function delete_history($param = '', $category = 0)
	{
		$this->create_surat->delete_history($param, $category);

		redirect("create_surat/index/{$category}");
	}

	/**
	 * Menghapus Syarat pada checkbox Pengajuan Surat 
	 *
	 * @param Integer (id_syarat)
	 * @return string
	 **/
	public function delete_syarat($param = 0)
	{
		$this->create_surat->delete_syarat($param);
	}	

	/**
	 * Check Log Surat 
	 * Apakah sudah pernah buat sebelumnya
	 *
	 * @param Integer (nik) penduduk
	 * @return string
	 **/
	public function insert_log_surat()
	{
		if(is_array($this->input->post('syarat')))
		{
			foreach($this->input->post('syarat') as $key => $value)
			{
				if($this->create_surat->log_surat_check_syarat($this->input->post('nik'), $this->input->post('kategori-surat'), $value))
				{
					continue;
				} else {
					$log_surat = array(
						'nik' => $this->input->post('nik'),
						'tanggal' => date('Y-m-d'),
						'kategori' => $this->input->post('kategori-surat'),
						'syarat' => $value,
						'nomor_surat' => 0
					);

					$this->db->insert('log_surat', $log_surat);
				}
			}

			if($this->create_surat->valid_requirement_check($this->input->post('nik'), $this->input->post('kategori-surat')) )
			{
				$this->data = array(
					'status' => true
				);
			} else {
				$this->data = array(
					'status' => false
				);
			}

			$this->output->set_content_type('application/json')->set_output(json_encode($this->data));
		}
	}

	public function create($param = 0, $ID = 0)
	{
		if($this->create_surat->surat_category($param) == FALSE)
			show_404();

		$penduduk = $this->create_surat->get_penduduk($ID);

		/* Apabila syarat kosong */
		if( $this->create_surat->valid_requirement_check($penduduk->nik, $param) == FALSE)
			show_404();

		$this->create_surat->create_surat($penduduk->nik, $param);

		$surat = $this->create_surat->surat_category($param);

		$this->page_title->push('Surat Keterangan', $surat->nama_kategori);

		$this->breadcrumbs->unshift(2, $surat->nama_kategori, "create_surat/create/{$param}");

		/* Get Validation Rules from parent controller */
		parent::get_surat_validation($surat->slug);

		if($this->form_validation->run() == TRUE)
		{
			$this->create_surat->update_surat($penduduk->nik, $param);
			redirect("create_surat/index/{$param}");

			/*
			echo json_encode($this->input->post('isi'), JSON_PRETTY_PRINT);
			exit;
			*/
		}

		$this->data = array(
			'title' => $surat->nama_kategori, 
			'breadcrumb' => $this->breadcrumbs->show(),
			'page_title' => $this->page_title->show(),
			'pegawai' => $this->create_surat->pegawai(),
			'syarat' => $this->create_surat->get_syarat($surat->syarat),
			'penduduk' => $penduduk,
			'surat' => $surat
		);

		$this->template->view("create-surat/form/{$surat->slug}", $this->data);
	}

}

/* End of file Surat_keterangan.php */
/* Location: ./application/controllers/Surat_keterangan.php */