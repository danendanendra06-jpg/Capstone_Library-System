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
        if ($this->request->getVar('ajax') == 1) {
            $suggestions = [];
            $q = strtolower(trim($search));
            foreach($data['users'] as $u) {
                if ($q === '' || strpos(strtolower($u['username']), $q) !== false) {
                    $suggestions[] = $u['username'];
                }
            }
            return $this->response->setJSON(array_values(array_unique($suggestions)));
        }

        return view('users/index', $data);
    }

    public function suspend($id = null) {
        $user = $this->userModel->find($id);
        if ($user && $user['role'] !== 'admin') {
            $this->userModel->update($id, ['status' => 'SUSPENDED']);
            
            // Add audit log
            $auditModel = new \App\Models\AuditLogModel();
            $auditModel->save([
                'admin_username' => session()->get('username'),
                'action' => 'SUSPEND_MEMBER',
                'details' => 'Suspended member: ' . $user['username']
            ]);
        }
        return redirect()->to('/users')->with('success', 'User suspended successfully.');
    }

    public function activate($id = null) {
        $user = $this->userModel->find($id);
        if ($user) {
            $this->userModel->update($id, ['status' => 'ACTIVE']);
            
            // Add audit log
            $auditModel = new \App\Models\AuditLogModel();
            $auditModel->save([
                'admin_username' => session()->get('username'),
                'action' => 'ACTIVATE_MEMBER',
                'details' => 'Activated member: ' . $user['username']
            ]);
        }
        return redirect()->to('/users')->with('success', 'User activated successfully.');
    }

    public function delete($id = null) {
        $user = $this->userModel->find($id);
        if ($user && $user['role'] !== 'admin') {
            $this->userModel->delete($id);
        } else {
            return redirect()->to('/users')->with('error', 'Cannot delete admin user.');
        }
        return redirect()->to('/users')->with('success', 'User deleted successfully.');
    }
}
