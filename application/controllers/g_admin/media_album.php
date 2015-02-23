<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

ini_set('display_errors', true);

class Media_album extends CI_Controller {

	public function __construct () {
        parent::__construct(TRUE);

        $this->load->model('Mediaalbum_model');
    }

    public function index($offset=0) {
        $this->load->library('pagination');

        $albums = $this->Mediaalbum_model->gets($offset);
        $data['albums'] = $albums['data'];
        $data['total_albums'] = $albums['count'];//$this->Mediamanage_model->count();

        $config['base_url'] = site_url('g_admin/media_album/index/');
        $config['total_rows'] = $data['total_albums'];
        $config['per_page'] = $this->Mediaalbum_model->limit;

        $this->pagination->initialize($config);
        $data['main'] = 'g_admin/media_manager/album_list';

        $this->load->view('g_admin/layout', $data);
    }

    public function create () {
        $this->load->library('form_validation');
        $this->form_validation->set_rules('album_name', 'Judul album', 'required');
        if($_POST AND $this->form_validation->run() == TRUE){
    	$now = new Datetime('now');
    	$name = $this->input->post('album_name');

    	$data = array(
	    	'label' => $name,
	    	'upload_at' => $now->format('Y-m-d H:i:s')
    	);


    	$result = $this->Mediaalbum_model->add($data);

    	redirect('g_admin/media_album');
        }else{
            $this->session->set_flashdata('error', 'Judul album tidak boleh kosong');
            redirect('g_admin/media_album');
        }
    }

    public function listAjax ($offset=1) {
    	$this->load->library('pagination');
    	$keyword = '';
    	$this->Mediamanage_model->limit = $limit = 20;
    	$offset_ex = ($offset - 1) * $limit;

    	$images = $this->Mediaalbum_model->gets($offset_ex);

    	$data['images'] = $images['data'];
    	$data['total_images'] = $total = $images['count'];//$this->Mediamanage_model->count();
    	$data['type']	= 'album';

    	$this->output
    	->set_content_type('application/json')
    	->set_output(json_encode($data));

    }

}
