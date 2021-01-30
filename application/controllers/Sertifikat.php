<?php
defined('BASEPATH') or exit('No direct script access allowed');

use chriskacerguis\RestServer\RestController;

class Sertifikat extends RestController
{

    function __construct()
    {
        // Construct the parent class
        parent::__construct();
    }

    public function users_get()
    {
        // Users from a data store e.g. database
        $users = [
            ['id' => 0, 'name' => 'John', 'email' => 'john@example.com'],
            ['id' => 1, 'name' => 'Jim', 'email' => 'jim@example.com'],
        ];

        $id = $this->get('id');

        if ($id === null) {
            // Check if the users data store contains users
            if ($users) {
                // Set the response and exit
                $this->response($users, 200);
            } else {
                // Set the response and exit
                $this->response([
                    'status' => false,
                    'message' => 'No users were found'
                ], 404);
            }
        } else {
            if (array_key_exists($id, $users)) {
                $this->response($users[$id], 200);
            } else {
                $this->response([
                    'status' => false,
                    'message' => 'No such user found'
                ], 404);
            }
        }
    }

    // GET Method
    function index_get()
    {
        $pegawai_id = $this->get('pegawai_id');
        if ($pegawai_id == '') {
            $record = $this->db->get('pegawai')->result();
        } else {
            $this->db->where('pegawai_id', $pegawai_id);
            $record = $this->db->get('pegawai')->result();
        }
        $this->response($record, 200);
    }

    // POST Method
    function index_post()
    {
        $data = array(
            'pegawai_id'    => $this->post('pegawai_id'),
            'nip'           => $this->post('nip'),
            'nama_pegawai'  => $this->post('nama_pegawai')
        );
        $insert = $this->db->insert('pegawai', $data);
        if ($insert) {
            $this->response($data, 200);
        } else {
            $this->response(array('status' => 'fail', 502));
        }
    }

    // PUT Method
    function index_put()
    {
        $pegawai_id = $this->put('pegawai_id');
        $data = array(
            //            'pegawai_id'    => $this->put('pegawai_id'),
            'nip'           => $this->put('nip'),
            'nama_pegawai'  => $this->put('nama_pegawai')
        );
        $this->db->where('pegawai_id', $pegawai_id);
        $update = $this->db->update('pegawai', $data);
        if ($update) {
            $this->response($data, 200);
        } else {
            $this->response(array('status' => 'fail', 502));
        }
    }

    // DELETE Method
    function index_delete()
    {
        $pegawai_id = $this->delete('pegawai_id');
        $this->db->where('pegawai_id', $pegawai_id);
        $delete = $this->db->delete('pegawai');
        if ($delete) {
            $this->response(array('status' => 'success'), 201);
        } else {
            $this->response(array('status' => 'fail', 502));
        }
    }
}
