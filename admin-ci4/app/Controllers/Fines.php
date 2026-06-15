<?php

namespace App\Controllers;

use App\Models\FineModel;

class Fines extends BaseController
{
    protected $fineModel;

    public function __construct()
    {
        $this->fineModel = new FineModel();
    }

    public function index()
    {
        $search = $this->request->getVar('search') ?? '';
        
        $fineData = $this->fineModel->getFinesPaginated($search, 10);

        $data = [
            'title'  => 'Manage Fines',
            'fines'  => $fineData['fines'],
            'pager'  => $fineData['pager'],
            'search' => $search
        ];

        return view('fines/index', $data);
    }

    public function show($id = null)
    {
        $fine = $this->fineModel->getFineDetail($id);

        if (!$fine) {
            return redirect()->to('/fines')->with('error', 'Fine not found.');
        }

        $data = [
            'title' => 'Fine Details',
            'fine'  => $fine
        ];

        return view('fines/show', $data);
    }

    public function markPaid($id = null)
    {
        if (strtolower((string)$this->request->getMethod()) === 'post') {
            $fine = $this->fineModel->find($id);
            if (!$fine) {
                return redirect()->to('/fines')->with('error', 'Fine not found.');
            }

            $method = $this->request->getPost('payment_method') ?? 'CASH';
            $amountReceived = (float)$this->request->getPost('amount_received');
            $change = 0;

            if ($method === 'CASH') {
                if ($amountReceived < $fine['amount']) {
                    return redirect()->back()->with('error', 'Amount received is less than the fine amount.');
                }
                $change = $amountReceived - $fine['amount'];
            }

            $this->fineModel->update($id, ['payment_status' => 'PAID']);
            
            // Add audit log
            $auditModel = new \App\Models\AuditLogModel();
            $auditModel->save([
                'admin_username' => session()->get('username'),
                'action' => 'UPDATE_FINE',
                'details' => 'Marked fine ID ' . $id . ' as PAID via ' . $method . '.'
            ]);

            $successMsg = 'Fine marked as paid via ' . $method . '.';
            if ($method === 'CASH' && $change > 0) {
                $successMsg .= ' Kembalian: Rp' . number_format($change, 0, ',', '.');
            }

            return redirect()->back()->with('success', $successMsg);
        }
        return redirect()->to('/fines');
    }

    public function markUnpaid($id = null)
    {
        if (strtolower((string)$this->request->getMethod()) === 'post') {
            $fine = $this->fineModel->find($id);
            if (!$fine) {
                return redirect()->to('/fines')->with('error', 'Fine not found.');
            }

            $this->fineModel->update($id, ['payment_status' => 'UNPAID']);
            
            // Add audit log
            $auditModel = new \App\Models\AuditLogModel();
            $auditModel->save([
                'admin_username' => session()->get('username'),
                'action' => 'UPDATE_FINE',
                'details' => 'Marked fine ID ' . $id . ' as UNPAID.'
            ]);

            return redirect()->back()->with('success', 'Fine marked as unpaid.');
        }
        return redirect()->to('/fines');
    }
}
