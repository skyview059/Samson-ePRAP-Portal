<?php

defined('BASEPATH') or exit('No direct script access allowed');

/**
 * Public JSON API consumed by external applications (e.g. the Laravel project).
 *
 * Endpoints:
 *   GET /api/packages              -> active courses (flat list) + active subscriptions (grouped by exam)
 *   GET /api/packages?exam_id=3    -> limit the subscriptions to a single exam
 */
class Api extends MX_Controller
{
    public function __construct()
    {
        parent::__construct();

        header('Access-Control-Allow-Origin: *');
        header('Access-Control-Allow-Methods: GET, OPTIONS');
        header('Access-Control-Allow-Headers: Content-Type, Authorization');

        // Pre-flight request from a browser based client.
        if ($this->input->method(true) === 'OPTIONS') {
            $this->output->set_status_header(204);
            exit;
        }
    }

    /**
     * Active courses and active subscription packages (grouped by exam), with name & pricing.
     */
    public function packages()
    {
        if ($this->input->method(true) !== 'GET') {
            return $this->respond(['status' => false, 'message' => 'Method not allowed'], 405);
        }

        $exam_id  = (int) $this->input->get('exam_id');
        $currency = $this->config->item('stripe_currency') ?: 'GBP';

        return $this->respond([
            'status'        => true,
            'currency'      => $currency,
            'courses'       => $this->activeCourses(),
            'subscriptions' => $this->activeSubscriptions($exam_id),
        ]);
    }

    /**
     * Active courses as a flat list.
     */
    private function activeCourses()
    {
        $this->db->select('id, name, price, duration');
        $this->db->from('courses');
        $this->db->where('status', 'Active');
        $this->db->order_by('id', 'ASC');
        $rows = $this->db->get()->result();

        $courses = [];
        foreach ($rows as $row) {
            $days      = (int) $row->duration;
            $courses[] = [
                'id'       => (int) $row->id,
                'name'     => $row->name,
                'price'    => $this->money($row->price),
                'duration' => $days . ($days === 1 ? ' day' : ' days'),
            ];
        }

        return $courses;
    }

    /**
     * Active subscription packages grouped by exam.
     */
    private function activeSubscriptions($exam_id = 0)
    {
        $this->db->select('pp.id, pp.exam_id, e.name AS exam_name, pp.title, pp.description, pp.price, pp.duration, pp.scenario_type');
        $this->db->from('practice_packages AS pp');
        $this->db->join('exams AS e', 'e.id = pp.exam_id', 'LEFT');
        $this->db->where('pp.status', 'Active');
        if ($exam_id > 0) {
            $this->db->where('pp.exam_id', $exam_id);
        }
        $this->db->order_by('pp.exam_id', 'ASC');
        $this->db->order_by('pp.price', 'ASC');
        $rows = $this->db->get()->result();

        $groups = [];
        foreach ($rows as $row) {
            $key = (int) $row->exam_id;
            if (!isset($groups[$key])) {
                $groups[$key] = [
                    'exam_id'   => $key,
                    'exam_name' => $row->exam_name,
                    'packages'  => [],
                ];
            }
            $groups[$key]['packages'][] = [
                'id'            => (int) $row->id,
                'name'          => $row->title,
                'description'   => $row->description,
                'price'         => $this->money($row->price),
                'duration'      => $row->duration,
                'scenario_type' => $row->scenario_type,
            ];
        }

        return array_values($groups);
    }

    /**
     * Format a price as 99.99
     */
    private function money($amount)
    {
        return number_format((float) $amount, 2, '.', '');
    }

    /**
     * Send a JSON response.
     */
    private function respond(array $payload, $code = 200)
    {
        $this->output
            ->set_status_header($code)
            ->set_content_type('application/json', 'utf-8')
            ->set_output(json_encode($payload, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES));
    }
}
