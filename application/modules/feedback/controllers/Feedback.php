<?php defined('BASEPATH') OR exit('No direct script access allowed');

/* SCA Feedback Domains & Statements manager
 * Modal (AJAX) based CRUD
 */

class Feedback extends Admin_controller{

    public function __construct(){
        parent::__construct();
        $this->load->model('Feedback_domain_model', 'domains');
        $this->load->model('Feedback_statement_model', 'statements');
        $this->load->helper('text');
    }

    public function index(){
        $data = array(
            'domains'    => $this->domains->get_all_with_counts(),
            'statements' => $this->statements->get_all_ordered(),
        );        
        $this->viewAdminContent('feedback/feedback/index', $data);
    }

    /* ---------------- Domain (AJAX) ---------------- */

    public function domain_save(){
        $id         = (int) $this->input->post('id');
        $name       = trim((string) $this->input->post('name', TRUE));
        $standard   = (string) $this->input->post('standard', TRUE);
        $sort_order = (int) $this->input->post('sort_order');

        if ($name === '') {
            return $this->json_out(false, 'Domain name is required');
        }

        $data = array(
            'name'       => $name,
            'standard'   => $standard,
            'sort_order' => $sort_order,
        );

        if ($id > 0) {
            if (!$this->domains->get_by_id($id)) {
                return $this->json_out(false, 'Domain not found');
            }
            $this->domains->update($id, $data);
            return $this->json_out(true, 'Domain updated successfully');
        }

        $this->domains->insert($data);
        return $this->json_out(true, 'Domain created successfully');
    }

    public function domain_delete($id = 0){
        $id  = (int) $id;
        $row = $this->domains->get_by_id($id);
        if (!$row) {
            return $this->json_out(false, 'Domain not found');
        }
        // statements are removed too via FK ON DELETE CASCADE
        $this->domains->delete($id);
        return $this->json_out(true, 'Domain and its statements deleted');
    }

    /* ---------------- Statement (AJAX) ---------------- */

    public function statement_save(){
        $id          = (int) $this->input->post('id');
        $domain_id   = (int) $this->input->post('domain_id');
        $sl_no       = (int) $this->input->post('sl_no');
        $subject     = trim((string) $this->input->post('subject', TRUE));
        $description = (string) $this->input->post('description', TRUE);

        if ($domain_id <= 0 || !$this->domains->get_by_id($domain_id)) {
            return $this->json_out(false, 'Please select a valid domain');
        }
        if ($sl_no <= 0) {
            return $this->json_out(false, 'Statement No. must be greater than 0');
        }
        if ($subject === '') {
            return $this->json_out(false, 'Subject is required');
        }
        if ($this->statements->sl_no_exists($domain_id, $sl_no, $id)) {
            return $this->json_out(false, 'Statement No. ' . $sl_no . ' already exists in this domain');
        }

        $data = array(
            'domain_id'   => $domain_id,
            'sl_no'       => $sl_no,
            'subject'     => $subject,
            'description' => $description,
        );

        if ($id > 0) {
            if (!$this->statements->get_by_id($id)) {
                return $this->json_out(false, 'Statement not found');
            }
            $this->statements->update($id, $data);
            return $this->json_out(true, 'Statement updated successfully', $domain_id);
        }

        $this->statements->insert($data);
        return $this->json_out(true, 'Statement created successfully', $domain_id);
    }

    public function statement_delete($id = 0){
        $id  = (int) $id;
        $row = $this->statements->get_by_id($id);
        if (!$row) {
            return $this->json_out(false, 'Statement not found');
        }
        $this->statements->delete($id);
        return $this->json_out(true, 'Statement deleted', $row->domain_id);
    }

    /* ---------------- Helpers ---------------- */

    private function json_out($success, $message, $domain_id = 0){
        $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode(array(
                'status'    => $success ? 'success' : 'error',
                'message'   => $message,
                'domain_id' => (int) $domain_id,
            )));
    }
}
