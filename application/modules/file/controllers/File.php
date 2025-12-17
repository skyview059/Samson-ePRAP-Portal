<?php defined('BASEPATH') or exit('No direct script access allowed');

/* Author: Khairul Azam
 * Date : 2020-07-30
 */

class File extends Admin_controller
{
    function __construct()
    {
        parent::__construct();
        $this->load->model('File_model');
        $this->load->library('form_validation');
    }

    public function index()
    {
        $q = urldecode_fk($this->input->get('q', TRUE));
        $page = intval($this->input->get('p'));
        $limit = 25;
        $start = startPointOfPagination($limit, $page);
        $target = build_pagination_url(Backend_URL . 'file', 'p', true);

        $total_rows = $this->File_model->total_rows($q);
        $files = $this->File_model->get_limit_data($limit, $start, $q);

        $data = array(
            'files' => $files,
            'q' => $q,
            'pagination' => getPaginator($total_rows, $page, $target, $limit),
            'total_rows' => $total_rows,
            'start' => $start,
        );
        $this->viewAdminContent('file/file/index', $data);
    }
          
    public function delete($id)
    {
        $row = $this->File_model->get_by_id($id);

        if ($row) {
            removeImage($row->file);
            $this->File_model->delete($id);
            $this->session->set_flashdata('message', '<p class="ajax_success">File Deleted Successfully</p>');
            redirect(site_url(Backend_URL . 'file'));
        } else {
            $this->session->set_flashdata('message', '<p class="ajax_error">File Not Found</p>');
            redirect(site_url(Backend_URL . 'file'));
        }
    }

    public function _menu()
    {
        return add_main_menu('File/Document', 'admin/file', 'file', 'fa-hand-o-right');
    }

    public function _rules()
    {
        $this->form_validation->set_rules('student_id', 'student id', 'trim|required|numeric');
        $this->form_validation->set_rules('title', 'title', 'trim|required');
        $this->form_validation->set_rules('file', 'file', 'trim|required');

        $this->form_validation->set_rules('id', 'id', 'trim');
        $this->form_validation->set_error_delimiters('<span class="text-danger">', '</span>');
    }

    public function change_status()
    {
        ajaxAuthorized();
        $id = intval($this->input->post('id'));
        $row = $this->File_model->get_by_id($id);
        if ($row->status == 'Locked') {
            $new_status = 'Unlock';
        } else {
            $new_status = 'Locked';
        }
        $this->db->update('files', ['status' => $new_status], ['id' => $id]);
        echo ajaxRespond('OK', $new_status);
    }

}