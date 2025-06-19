<?php

class Login extends Controller {
    public function index() {
        $data['title'] = 'Halaman Login';
        $this->view('login/login', $data);
    }

    public function prosesLogin() {
        if (empty($_POST['username']) || empty($_POST['password'])) {
            header('Location: ' . base_url . '/login');
            exit;
        }

        $loginModel = $this->model('LoginModel');
        $row = $loginModel->checkLogin($_POST);
      
        if ($row > 0) {
            $_SESSION['id_user'] = $row['id_user'];
            $_SESSION['username'] = $row['username'];
            $_SESSION['session_login'] = 'sudah_login';

            $username = $row['username'];
            
            if (in_array($username, ['wardi','herman','devita'])) {
                $_SESSION['level'] = 'superadmin';
              
            }elseif (in_array($username, ['satryos'])) {
                $_SESSION['level'] = 'admin';
                //header('Location: ' . base_url . '/videostore');
            }else {
                $_SESSION['level'] = 'user';
                //header('Location: ' . base_url . '/videostore/listplay');
            }
            header('Location: ' . base_url . '/home');
          
        } else {
            Flasher::setMessage('Username / Password', 'salah.', 'danger');
            header('Location: ' . base_url . '/login');
            exit;
        }
    }
}
