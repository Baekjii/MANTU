<?php

namespace App\Controllers;

use App\Models\UserModel;

class ProfileController extends BaseController
{
    public function index()
    {
        $userModel = new UserModel();
        $data['user'] = $userModel->find(session()->get('id'));
        return view('profile/index', $data);
    }

    public function update()
    {
        $userModel = new UserModel();
        $id = session()->get('id');

        $data = [
            'nama' => $this->request->getPost('nama'),
            'email' => $this->request->getPost('email')
        ];

        // Optional password update
        $password = $this->request->getPost('password');
        if (!empty($password)) {
            $data['password'] = password_hash($password, PASSWORD_DEFAULT);
        }

        $userModel->update($id, $data);

        // Update session
        session()->set([
            'nama' => $data['nama'],
            // 'email' => $data['email']
        ]);

        return redirect()->to('/profile')->with('success', 'Profile updated successfully');
    }
}
