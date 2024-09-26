<?php

namespace App\Controllers;

use App\Models\UserModel;
use CodeIgniter\RESTful\ResourceController;

class UserController extends ResourceController
{
    protected $modelName = 'App\Models\UserModel';
    protected $format    = 'json';

    // [POST] /users: Create
    // Endpoint: 
    public function create()
    {
        $data = [
            'username' => $this->request->getPost('username'),
            'email'    => $this->request->getPost('email'),
            'password' => password_hash($this->request->getPost('password'), PASSWORD_DEFAULT),
        ];

        if ($this->model->insert($data)) {
            $userId = $this->model->getInsertID();
            $user = $this->model->find($userId);

            $response = [
                'status'  => 'sukses',
                'message' => 'Akun Anda sudah dibuat!',
                'data'    => [
                    'username' => $user['username'],
                    'email'    => $user['email'],
                    'password' => $user["password"],
                    'waktu dibuat' => $user["created_at"],
                ]
            ];

            return $this->respondCreated(json_decode(json_encode($response), true));
        } else {
            $response = [
                'status'  => 'gagal',
                'message' => 'Validasi gagal, data tidak valid!',
                'errors'  => $this->model->errors()
            ];

            return $this->failValidationErrors(json_decode(json_encode($response), true));
        }
    }

    // [GET] /users: Show
    public function show($id = null)
    {
        $user = $this->model->find($id);

        if ($user) {
            $response = [
                'status'  => 'sukses',
                'message' => 'Data user ditemukan!',
                'data'    => [
                    'username' => $user['username'],
                    'email'    => $user['email'],
                    'password' => $user['password'],
                    'waktu dibuat' => $user["created_at"],
                ]
            ];

            return $this->respond(json_decode(json_encode($response), true), 200);
        } else {
            $response = [
                'status'  => 'gagal',
                'message' => 'User tidak ditemukan!'
            ];

            return $this->respond(json_decode(json_encode($response), true), 404);
        }
    }
}
