<?php
defined('BASEPATH') OR exit('No direct script access allowed');

use chriskacerguis\RestServer\RestController;

class Api extends RestController {

    function __construct()
    {
        // Construct the parent class
        parent::__construct();
        $this->db_balai = $this->load->database('default', true);
    }

    public function users_get()
    {
        // Users from a data store e.g. database
        $users = [
            ['id' => 0, 'name' => 'John', 'email' => 'john@example.com'],
            ['id' => 1, 'name' => 'Jim', 'email' => 'jim@example.com'],
        ];

        $id = $this->get( 'id' );

        if ( $id === null )
        {
            // Check if the users data store contains users
            if ( $users )
            {
                // Set the response and exit
                $this->response( $users, 200 );
            }
            else
            {
                // Set the response and exit
                $this->response( [
                    'status' => false,
                    'message' => 'No users were found'
                ], 404 );
            }
        }
        else
        {
            if ( array_key_exists( $id, $users ) )
            {
                $this->response( $users[$id], 200 );
            }
            else
            {
                $this->response( [
                    'status' => false,
                    'message' => 'No such user found'
                ], 404 );
            }
        }
    }

    public function sertifikat_get2(){
        $id = $this->get( 'id' );
        $no_uji = $this->get( 'no_uji' );
        $npwp = $this->get('npwp');

        $no_uji = $this->get('no_uji');
        if ($no_uji == '') {
            $record = $this->db->get('pegawai')->result();
        } else {
            $this->db->where('vNo_pengujian', $no_uji);
            $record = $this->db->get('pegawai')->result();
        }
        $this->response($record, 200);
        
    }
    public function sertifikat_get(){
        $id = $this->get('id');
        $no_uji = $this->get( 'no_uji' );
        $npwp = $this->get('npwp');

        $where = " ";

        if ($id != '') {
            $where .= " and a.id = '" . $id . "'";
        }
        if ($no_uji != '') {
            $where .= " and c.vNo_pengujian = '" . $no_uji . "'";
        }
         if ($npwp != '') {
             $where .= " and d.vNpwp = '".$npwp."' ";
         }
        
        $sql = 'SELECT 
                #a.*,b.*,
                #d.*,
                ifnull(d.vNpwp,"-") as "NPWP Perusahaan",
                d.vName_company as "Nama Perusahaan",
                d.vAddress_company "Alamat Perusahaan",
                #d.cNip,b.iMt01,
                c.vNo_pengujian as "No Pengujian"
                ,a.vNo_atas as "No Sertifikat"
                ,b.vNama_sample as "Nama Sample"
                FROM sihapsoh.sertifikat a 
                JOIN sihapsoh.mt01 b ON b.iMt01=a.iMt01
                JOIN sihapsoh.mt03 c ON c.iMt01=b.iMt01
                JOIN hrd.employee d ON d.cNip=b.iCustomer
                WHERE a.lDeleted=0
                ';

        $fullQuery = $sql.$where;
        // echo '<pre>'.$fullQuery;
        // exit;
        $query = $this->db_balai->query($fullQuery);
        // echo $query->num_rows();
        // exit;

        if($query->num_rows() > 0){
            $datas = $query->result_array();
            $this->response( $datas, 200 );
        }else{
            $this->response( [
                'status' => false,
                'message' => 'No such user found'
            ], 404 );
        }
        
    }
}