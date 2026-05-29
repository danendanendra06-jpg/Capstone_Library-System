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

            $this->fineModel->update($id, ['status' => 'paid']);
            return redirect()->back()->with('success', 'Fine marked as paid.');
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

            $this->fineModel->update($id, ['status' => 'unpaid']);
            return redirect()->back()->with('success', 'Fine marked as unpaid.');
        }
        return redirect()->to('/fines');
    }
}
