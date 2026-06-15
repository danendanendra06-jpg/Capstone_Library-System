<?php

namespace App\Controllers;

use App\Models\BookModel;
use App\Models\UserModel;
use App\Models\BorrowModel;
use App\Models\FineModel;

class Admin extends BaseController
{
    public function index()
    {
        $bookModel   = new BookModel();
        $userModel   = new UserModel();
        $borrowModel = new BorrowModel();
        $fineModel   = new FineModel();

        $sumFines = $fineModel->where('payment_status', 'UNPAID')->selectSum('amount')->first();

        // 1. Buku Populer (Top 5)
        $popularBooks = $borrowModel->select('books.title, COUNT(borrows.id) as borrow_count')
                                    ->join('books', 'books.id = borrows.book_id')
                                    ->groupBy('books.id')
                                    ->orderBy('borrow_count', 'DESC')
                                    ->limit(5)->findAll();

        // 2. Member Aktif (Top 5)
        $activeMembers = $borrowModel->select('users.username, COUNT(borrows.id) as borrow_count')
                                     ->join('users', 'users.id = borrows.user_id')
                                     ->groupBy('users.id')
                                     ->orderBy('borrow_count', 'DESC')
                                     ->limit(5)->findAll();

        // 3. Buku Terlambat
        $lateBooks = $borrowModel->where('status', 'BORROWED')
                                 ->where('due_date <', date('Y-m-d'))
                                 ->countAllResults();

        // 4. Stok Menipis
        $lowStock = $bookModel->where('stock <', 3)->countAllResults();

        // 5. Members with Unpaid Fines
        $db = \Config\Database::connect();
        $membersPenalizedResult = $db->query("
            SELECT COUNT(DISTINCT user_id) as count 
            FROM fines 
            WHERE payment_status = 'UNPAID'
        ")->getRow();
        $membersPenalized = $membersPenalizedResult->count;

        // 6. Monthly Chart (Borrows per month this year)
        $monthlyQuery = $db->query("
            SELECT MONTH(borrow_date) as m, COUNT(*) as c 
            FROM borrows 
            WHERE YEAR(borrow_date) = YEAR(CURDATE()) 
            GROUP BY MONTH(borrow_date)
        ")->getResultArray();

        $monthlyData = array_fill(1, 12, 0);
        foreach ($monthlyQuery as $mq) {
            $monthlyData[(int)$mq['m']] = (int)$mq['c'];
        }

        $data = [
            'title'          => 'Admin Dashboard',
            'total_books'    => $bookModel->countAll(),
            'total_users'    => $userModel->where('role', 'member')->countAllResults(),
            'active_loans'   => $borrowModel->where('status', 'BORROWED')->countAllResults(),
            'total_fines'    => $sumFines['amount'] ?? 0,
            
            'popular_books'  => $popularBooks,
            'active_members' => $activeMembers,
            'late_books'     => $lateBooks,
            'low_stock'      => $lowStock,
            'members_penalized' => $membersPenalized,
            'monthly_data'   => array_values($monthlyData),
            
            'recent_borrows' => $borrowModel->getTransactionsWithDetails(5)
        ];

        return view('admin/dashboard', $data);
    }
}
