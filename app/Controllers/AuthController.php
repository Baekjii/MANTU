<?php

namespace App\Controllers;

use App\Models\UserModel;
use CodeIgniter\Controller;

class AuthController extends BaseController
{
    public function login()
    {
        helper(['form']);
        return view('auth/login');
    }

    public function processLogin()
    {
        $session = session();
        $model = new UserModel();
        $email = $this->request->getVar('email');
        $password = $this->request->getVar('password');

        $data = $model->where('email', $email)->first();

        if ($data) {
            $pass = $data['password'];
            $verify_pass = password_verify($password, $pass);
            if ($verify_pass) {
                $ses_data = [
                    'id'       => $data['id'],
                    'nama'     => $data['nama'],
                    'email'    => $data['email'],
                    'logged_in'=> TRUE
                ];
                $session->set($ses_data);
                return redirect()->to('/projects');
            } else {
                $session->setFlashdata('msg', 'Password salah.');
                return redirect()->to('/login');
            }
        } else {
            $session->setFlashdata('msg', 'Email tidak ditemukan.');
            return redirect()->to('/login');
        }
    }

    public function register()
    {
        helper(['form']);
        return view('auth/register');
    }

    public function processRegister()
    {
        helper(['form']);
        $rules = [
            'nama'          => 'required|min_length[3]|max_length[100]',
            'email'         => 'required|min_length[6]|max_length[100]|valid_email|is_unique[users.email]',
            'password'      => 'required|min_length[6]',
            'confpassword'  => 'matches[password]'
        ];

        if($this->validate($rules)){
            $model = new UserModel();
            $data = [
                'nama'     => $this->request->getVar('nama'),
                'email'    => $this->request->getVar('email'),
                'password' => password_hash($this->request->getVar('password'), PASSWORD_DEFAULT)
            ];
            $model->save($data);
            $session = session();
            $session->setFlashdata('msg', 'Registrasi berhasil, silakan login.');
            return redirect()->to('/login');
        } else {
            $data['validation'] = $this->validator;
            return view('auth/register', $data);
        }
    }

    public function logout()
    {
        $session = session();
        $session->destroy();
        return redirect()->to('/login');
    }
}
