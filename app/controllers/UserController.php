<?php
// app/controllers/UserController.php

require_once __DIR__ . '/../models/User.php';
require_once __DIR__ . '/../../includes/helper.php'; // Untuk fungsi view() dan redirect()

class UserController
{
    private $userModel;

    public function __construct()
    {
        $this->userModel = new User();
    }

    /**
     * Menampilkan daftar semua pengguna.
     */
    public function index()
    {
        $users = $this->userModel->getAllUsers();
        view('users/index', ['users' => $users]);
    }

    /**
     * Menampilkan form untuk membuat pengguna baru.
     */
    public function create()
    {
        view('users/create');
    }

    /**
     * Menyimpan pengguna baru ke database.
     */
    public function store()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $name = $_POST['name'] ?? '';
            $email = $_POST['email'] ?? '';
            $password = $_POST['password'] ?? '';

            // Validasi sederhana
            if (empty($name) || empty($email) || empty($password)) {
                // Handle error, misalnya tampilkan pesan ke view
                view('users/create', ['error' => 'Semua kolom harus diisi.']);
                return;
            }

            // Hash password sebelum menyimpan
            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

            $data = [
                'name' => $name,
                'email' => $email,
                'password' => $hashedPassword,
                'created_at' => date('Y-m-d H:i:s'), // Tambahkan timestamp
                'updated_at' => date('Y-m-d H:i:s'),
            ];

            if ($this->userModel->createUser($data)) {
                header('Location: /users'); // Redirect ke daftar pengguna
                exit();
            } else {
                // Handle error penyimpanan
                view('users/create', ['error' => 'Gagal menyimpan pengguna.']);
            }
        }
    }

    /**
     * Menampilkan detail pengguna berdasarkan ID.
     * @param int $id ID pengguna.
     */
    public function show($id)
    {
        $user = $this->userModel->getUserById($id);
        view('users/show', ['user' => $user]);
    }

    /**
     * Menampilkan form untuk mengedit pengguna.
     * @param int $id ID pengguna.
     */
    public function edit($id)
    {
        $user = $this->userModel->getUserById($id);
        view('users/edit', ['user' => $user]);
    }

    /**
     * Memperbarui data pengguna di database.
     * @param int $id ID pengguna.
     */
    public function update($id)
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $name = $_POST['name'] ?? '';
            $email = $_POST['email'] ?? '';
            $password = $_POST['password'] ?? ''; // Opsional

            // Validasi sederhana
            if (empty($name) || empty($email)) {
                view('users/edit', ['user' => $this->userModel->getUserById($id), 'error' => 'Nama dan Email harus diisi.']);
                return;
            }

            $data = [
                'name' => $name,
                'email' => $email,
                'updated_at' => date('Y-m-d H:i:s'),
            ];

            if (!empty($password)) {
                $data['password'] = password_hash($password, PASSWORD_DEFAULT);
            }

            if ($this->userModel->updateUser($id, $data)) {
                header('Location: /users/' . $id); // Redirect ke detail pengguna
                exit();
            } else {
                view('users/edit', ['user' => $this->userModel->getUserById($id), 'error' => 'Gagal memperbarui pengguna.']);
            }
        }
    }

    /**
     * Menghapus pengguna dari database.
     * @param int $id ID pengguna.
     */
    public function destroy($id)
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if ($this->userModel->deleteUser($id)) {
                header('Location: /users'); // Redirect ke daftar pengguna
                exit();
            } else {
                // Handle error penghapusan
                // Redirect kembali dengan pesan error atau tampilkan di halaman
                header('Location: /users');
                exit();
            }
        }
    }
}