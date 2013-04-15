<?php

/**
 *
 */
class Store_locator extends CI_Controller
{

    function __construct()
    {
        parent::__construct();
    }

    public function index()
    {

        if ($_POST) {

            if ($_POST['action'] == "order" && $_POST['id'] != "") { //	Save Order.
                $this->_save_order();
            } else if ($_POST['action'] == "remove" && $_POST['id'] != "") { //	Remove Item.
                $this->_remove($_POST['id']);
            } else if ($_POST['action'] == "publish" && $_POST['id'] != "") { // Publish or unPublish
                $this->_publish($_POST['id']);
            } else if ($_POST['action'] == "update" && $_POST['id'] != "") { // Change Update Mode
                $this->_update($_POST['id']);
                return;
            } else if ($_POST['action'] == "update_save" && $_POST['id'] != "") { //	Save Update Data.
                if ($this->_update_save() == false) {
                    return;
                }
            }
        }

        $result = $this->db->query("SELECT * FROM markers WHERE is_delete = 0 ORDER BY id asc");
        $this->markers = $result->result();

        $this->load->view('store_locator');

    }

    public function addnew()
    {
        $this->load->view('store_locator_addnew');
    }

    private function _submit_validate()
    {
        $this->form_validation->set_rules('publish', 'Publish');
        $this->form_validation->set_rules('name', 'Store Name', 'required');
        $this->form_validation->set_rules('address', 'Address', 'required');
        $this->form_validation->set_rules('lat', 'lat', 'required');
        $this->form_validation->set_rules('lng', 'lng', 'required');
        return $this->form_validation->run();
    }

    public function save()
    {
        if ($this->_submit_validate() === FALSE) {
            $this->load->view('store_locator_addnew');
            return;
        }

        $Store_Locator = array(
            'name' => $this->input->post('name'),
            'address' => $this->input->post('address'),
            'phone' => $this->input->post('phone'),
            'lat' => $this->input->post('lat'),
            'lng' => $this->input->post('lng'),
            'publish' => $this->input->post('publish')
        );

        $this->db->trans_start();
        $this->db->insert('markers', $Store_Locator);
        $this->db->trans_complete();

        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
            log_message('error', 'Store_locator->save() 交易錯誤');
        }

        redirect('store_locator/success', 'refresh');

    }

    public function success()
    {
        $this->message = "Successfully Saved Store Locator";
        $this->index();
    }

    public function _update($ndx)
    {
        $result = $this->db->query("SELECT * FROM markers WHERE is_delete = 0 and id = ?", $ndx);
        $this->markers = $result->result();
        $this->load->view('store_locator_update');
    }

    public function _update_save()
    {

        if ($this->_submit_validate() === FALSE) {
            $this->_update($_POST['id']);
            return false;
        }

        $Store_Locator = array(
            'name' => $this->input->post('name'),
            'address' => $this->input->post('address'),
            'phone' => $this->input->post('phone'),
            'lat' => $this->input->post('lat'),
            'lng' => $this->input->post('lng'),
            'publish' => $this->input->post('publish')
        );

        $this->db->trans_start();
        $this->db->where('id', $this->input->post('id'));
        $this->db->update('markers', $Store_Locator);
        $this->db->trans_complete();

        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
            log_message('error', 'Store_locator->_update_save() 交易錯誤');
        }

        $this->update_message = "1 records updated";
        return true;
    }

    public function _publish()
    {
        $Publish = array('publish' => $this->input->post('publish_state'));
        $this->db->where('id', $_POST['id']);
        $this->db->update('markers', $Publish);
        $numrows = $this->db->affected_rows();
        $this->message = $numrows . " records updated";
    }

    public function _remove($ndx)
    {
        $numrows = 0;
        if (!empty($ndx)) {
            $ids = explode(',', $ndx);
            if (is_array($ids) && (count($ids) >= 1)) {
                $this->db->trans_start();
                foreach ($ids as $id) {
                    $this->db->query('delete from markers where id = ?', $id);
                    $numrows += $this->db->affected_rows();
                }

                $this->db->trans_complete();

                if ($this->db->trans_status() === FALSE) {
                    $this->error_message = "發生了異常的動作, 系統取消了上次的動作";
                    $this->db->trans_rollback();
                } else {
                    if ($numrows != 0) {
                        $this->message = $numrows . " records deleted";
                    }
                }
            }
        }
    }

    public function import()
    {
        if ($_POST) {
            $config['upload_path'] = 'excel/';
            $config['allowed_types'] = 'xls|xlsx';

            $this->load->library('upload', $config);

            if (!$this->upload->do_upload()) {

                $this->error = $this->upload->display_errors();
                $this->load->view('store_locator_import');

                return;
            } else {
                $data = $this->upload->data();

                ini_set('memory_limit', '256M');
                $objPHPExcel = IOFactory::load($data['full_path']);
                $objPHPExcel->setActiveSheetIndex(0);
                $aSheet = $objPHPExcel->getActiveSheet();

                $i = 1;

                foreach ($aSheet->getRowIterator() as $row) {
                    $name    = $aSheet->getCell("A$i")->getValue();
                    $address = $aSheet->getCell("B$i")->getValue();
                    $phone   = $aSheet->getCell("C$i")->getValue();
                    $lat     = $aSheet->getCell("D$i")->getValue();
                    $lng     = $aSheet->getCell("E$i")->getValue();

                    $this->db->insert("markers", array(
                        'name' => $name,
                        'address' => $address,
                        'phone' => $phone,
                        'lat' => $lat,
                        'lng' => $lng
                    ));

                    $i++;
                }

                $this->info = ($i-1). ' item(s) were successfully imported.';
                $this->load->view('store_locator_import');
            }
        } else {
            $this->load->view('store_locator_import');
        }

    }

}