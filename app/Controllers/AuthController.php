<?php

namespace App\Controllers;
use App\Models\UserModel;
use CodeIgniter\Controller;

class AuthController extends BaseController
{
    public function index(){
        return redirect()->to('/login');
    }
    public function register()
    {
      
        if ($this->request->getMethod() == 'POST') {
            $rules = [
                'name'            => 'required',
                'email' => [
                                'rules' => 'required|valid_email|is_unique[users.email]',
                                'errors' => [
                                    'is_unique' => 'This email is already registered.'
                                ]
                            ],
                'password'        => 'required|min_length[6]',
                'profile_pic' => 'uploaded[profile_pic]|is_image[profile_pic]|mime_in[profile_pic,image/jpg,image/jpeg,image/png]'
            ];

            if (!$this->validate($rules)) {
                log_message('error', print_r($this->validator->getErrors(), true));
                return view('auth/register', ['validation' => $this->validator]);
            }

            $image = $this->request->getFile('profile_pic');
            $imageName = $image->getRandomName();
            $image->move('uploads/', $imageName);
        
            $model = new UserModel();

            $data = [
                'name'        => $this->request->getPost('name'),
                'email'       => $this->request->getPost('email'),
                'password'    => password_hash($this->request->getPost('password'), PASSWORD_DEFAULT),
                'profile_pic' => $imageName
            ];
            if ($model->save($data)) {
                return redirect()->to('register')->with('success', 'Registered successfully! <a href="/login">Click here to login</a>.');
            } else {
                return view('register', [
                    'validation' => $model->errors()
                ]);
            }

            return redirect()->to('/register')->with('success', 'Registered successfully!');
        }

        return view('auth/register');

    }

    public function login()
    {
      
       

        // Auto-redirect if already logged in
        
        if (session()->get('isLoggedIn')) {
            return redirect()->to('dashboard');
        }

        if ($this->request->getMethod() == 'POST') {
            // echo "test";
            $model = new UserModel();
            $user = $model->where('email', $this->request->getPost('email'))->first();
            // print_r($user); die;
            if ($user && password_verify($this->request->getPost('password'), $user['password'])) {
                session()->set([
                    'user_id' => $user['id'],
                    'name' => $user['name'],
                    'profile_pic' => $user['profile_pic'],
                    'isLoggedIn' => true,
                ]);
                return redirect()->to('/dashboard');
            } else {
                return view('auth/login', ['error' => 'Invalid login']);
            }
        }
        return view('auth/login');
    }

    public function logout()
    {
        session()->destroy();
        return redirect()->to('/login');
    }
}



