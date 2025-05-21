<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

function init_pagination($base_url, $total_rows, $per_page = 10, $uri_segment = 3, $query_params = []) {
    $CI =& get_instance();
    $CI->load->library('pagination');

    $config['base_url'] = $base_url;
    $config['total_rows'] = $total_rows;
    $config['per_page'] = $per_page;
    $config['uri_segment'] = $uri_segment;
    $config['reuse_query_string'] = true;
    $config['use_page_numbers'] = true;

    if (!empty($query_params)) {
        $query_string = http_build_query($query_params);
        if ($query_string !== '') {
            $config['suffix'] = '?' . $query_string;
            $config['first_url'] = $base_url . '?' . $query_string;
        }
    }

    $config['full_tag_open'] = '<div class="pagination">';
    $config['full_tag_close'] = '</div>';
    $config['attributes'] = ['class' => 'page-link'];

    $CI->pagination->initialize($config);
}

?>