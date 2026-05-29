<?php

namespace App\Controllers;

use App\Models\BookModel;
use App\Models\UserModel;
use App\Models\BorrowTransactionModel;
use App\Models\FineModel;

class Admin extends BaseController
{
    public function index()
    {
        $bookModel   = new BookModel();
        $userModel   = new UserModel();
        $borrowModel = new BorrowTransactionModel();
        $fineModel   = new FineModel();

        $sumFines = $fineModel->where('status', 'unpaid')->selectSum('fine_amount')->first();

        $data = [
            'title'        => 'Admin Dashboard',
            'total_books'  => $bookModel->countAll(),
            'total_users'  => $userModel->where('role', 'member')->countAllResults(),
            'active_loans' => $borrowModel->whereIn('status', ['borrowed', 'overdue'])->countAllResults(),
            'total_fines'  => $sumFines['fine_amount'] ?? 0,
            
            // Recent Borrows for the quick view table
            'recent_borrows' => $borrowModel->getTransactionsWithDetails()
        ];

        return view('admin/dashboard', $data);
    }
}
