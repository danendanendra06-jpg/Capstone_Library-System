<?php
namespace App\Controllers;
use App\Models\UserModel;

class Users extends BaseController
{
    protected $userModel;
    public function __construct() { $this->userModel = new UserModel(); }

    public function index() {
        return view('users/index', ['title' => 'Manage Members', 'users' => $this->userModel->paginate(10), 'pager' => $this->userModel->pager]);
    }
    public function edit($id = null) {
        return view('users/form', ['title' => 'Edit Member', 'user' => $this->userModel->find($id)]);
    }
    public function update($id = null) {
        $this->userModel->update($id, ['role' => $this->request->getPost('role')]);
        return redirect()->to('/users')->with('success', 'User updated.');
    }
    public function delete($id = null) {
        $this->userModel->delete($id);
        return redirect()->to('/users')->with('success', 'User deleted.');
    }
}
