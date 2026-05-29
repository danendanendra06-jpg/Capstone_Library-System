<?php
namespace App\Controllers;
use App\Models\UserModel;

class Users extends BaseController
{
    protected $userModel;
    public function __construct() { $this->userModel = new UserModel(); }

    public function index() {
        $search = $this->request->getVar('search') ?? '';
        
        if (!empty($search)) {
            $this->userModel->groupStart()
                            ->like('username', $search)
                            ->orLike('email', $search)
                            ->groupEnd();
        }

        $data = [
            'title'  => 'Manage Members',
            'users'  => $this->userModel->paginate(10),
            'pager'  => $this->userModel->pager,
            'search' => $search
        ];
        return view('users/index', $data);
    }

    public function edit($id = null) {
        $data = [
            'title' => 'Edit Member Role', 
            'user'  => $this->userModel->find($id)
        ];
        if (!$data['user']) throw new \CodeIgniter\Exceptions\PageNotFoundException('User not found');
        return view('users/form', $data);
    }

    public function update($id = null) {
        $this->userModel->update($id, ['role' => $this->request->getPost('role')]);
        return redirect()->to('/users')->with('success', 'User role updated successfully.');
    }

    public function delete($id = null) {
        $this->userModel->delete($id);
        return redirect()->to('/users')->with('success', 'User deleted successfully.');
    }
}
