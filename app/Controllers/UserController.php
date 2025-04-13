<?php

namespace App\Controllers;
use App\Models\UserModel;

class UserController extends BaseController
{
    public function dashboard()
    {
        $name = session()->get('name');
       $profile_pic = session()->get('profile_pic');

        return view('user/dashboard', [
            'name' => $name,
            'profile_pic' => $profile_pic
        ]);
    }

    public function profile()
    {
        helper(['form']);
        $model = new UserModel();
        $user = $model->find(session('user_id'));
        $userId = session()->get('user_id');
        // print_r($user); 
        // print_r($userId); die;
        if ($this->request->getMethod() == 'POST') {

            $newEmail = $this->request->getPost('email');
          
            // Check if email exists for another user
            $existingUser = $model->where('email', $newEmail)
                                ->where('id !=', $userId)
                                ->first();
            // echo "asdfads";
            // print_r($existingUser); die;
            if ($existingUser) {
                return redirect()->back()->with('error', 'Email is already exist.')->withInput();
            }

            $data = [
                'name' => $this->request->getPost('name'),
                'email' => $this->request->getPost('email'),
            ];

            if ($this->request->getFile('profile_pic')->isValid()) {
                $file = $this->request->getFile('profile_pic');
                $fileName = $file->getRandomName();
                $file->move('uploads/', $fileName);
                $data['profile_pic'] = $fileName;
                session()->set('profile_pic', $fileName);
            }

            if ($this->request->getPost('password')) {
                $data['password'] = password_hash($this->request->getPost('password'), PASSWORD_DEFAULT);
            }

            // $model->update(session('user_id'), $data);
            // session()->set('name', $data['name']);
            // return redirect()->to('/dashboard');

            $model->update($userId, $data);
            session()->set('name', $data['name']);
            session()->setFlashdata('success', 'Profile updated successfully.');
            return redirect()->to('/profile'); // back to profile to show message
        }

        return view('user/profile', ['user' => $user]);
    }

    public function search()
    {
        // $images = [];
        // $query = '';
        // // echo $this->request->getMethod();
        // if ($this->request->getMethod() == 'GET') {
        //     echo $query = $this->request->getPost('query');
        //     $apiKey = '49717346-210cab991e11f3ad9571aa4a4';
        //     $url = "https://pixabay.com/api/?key={$apiKey}&q=" . urlencode($query) . "&image_type=photo&video_type=film";
           
        //     $client = \Config\Services::curlrequest();
        //     $response = $client->get($url);
        //     $result = json_decode($response->getBody(), true);

        //     $images = $result['hits'] ?? [];
        //     // print_r($images);
        // }
        
        return view('user/search');
    }


    public function ajaxSearch()
    {
        $query = $this->request->getPost('query');
        $images = [];

        if ($query) {
            $apiKey = '49717346-210cab991e11f3ad9571aa4a4';
            $url = "https://pixabay.com/api/?key={$apiKey}&q=" . urlencode($query) . "&image_type=photo&video_type=film";

            $client = \Config\Services::curlrequest();
            $response = $client->get($url);
            $result = json_decode($response->getBody(), true);

            $images = $result['hits'] ?? [];
        }

        return $this->response->setJSON($images);
    }

}
