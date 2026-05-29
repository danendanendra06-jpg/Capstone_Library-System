<?php

namespace App\Controllers;

use App\Models\BorrowTransactionModel;
use App\Models\ReturnTransactionModel;
use App\Models\FineModel;
use App\Models\BookModel;
use App\Models\UserModel;

class Transactions extends BaseController
{
    protected $borrowModel;
    protected $returnModel;
    protected $fineModel;
    protected $bookModel;
    protected $userModel;

    public function __construct()
    {
        $this->borrowModel = new BorrowTransactionModel();
        $this->returnModel = new ReturnTransactionModel();
        $this->fineModel   = new FineModel();
        $this->bookModel   = new BookModel();
        $this->userModel   = new UserModel();
    }

    // List all Borrow Transactions
    public function index()
    {
        $data = [
            'title'        => 'Borrow Transactions',
            'transactions' => $this->borrowModel->getTransactionsWithDetails(),
        ];
        return view('transactions/index', $data);
    }

    // List all Return Transactions
    public function returns()
    {
        $data = [
            'title'   => 'Return Transactions',
            'returns' => $this->returnModel->getReturnsWithDetails(),
        ];
        return view('transactions/returns', $data);
    }

    // Create a new manual borrow
    public function create()
    {
        helper(['form']);
        if (strtolower((string)$this->request->getMethod()) === 'post') {
            $rules = [
                'user_id'     => 'required|is_natural_no_zero',
                'book_id'     => 'required|is_natural_no_zero',
                'borrow_date' => 'required|valid_date',
                'due_date'    => 'required|valid_date',
            ];

            if ($this->validate($rules)) {
                $bookId = $this->request->getVar('book_id');
                $book = $this->bookModel->find($bookId);
                
                if ($book['stock'] <= 0) {
                    return redirect()->back()->withInput()->with('error', 'Book is out of stock!');
                }

                $this->borrowModel->save([
                    'user_id'     => $this->request->getVar('user_id'),
                    'book_id'     => $bookId,
                    'borrow_date' => $this->request->getVar('borrow_date'),
                    'due_date'    => $this->request->getVar('due_date'),
                    'status'      => 'borrowed'
                ]);

                // Decrease stock
                $this->bookModel->update($bookId, ['stock' => $book['stock'] - 1]);

                return redirect()->to('/transactions')->with('success', 'Borrow transaction created successfully.');
            } else {
                $data['validation'] = $this->validator;
            }
        }

        $data['title'] = 'Create Borrow Transaction';
        // Get all members for dropdown
        $data['users'] = $this->userModel->where('role', 'member')->findAll();
        // Get available books
        $data['books'] = $this->bookModel->where('stock >', 0)->findAll();

        return view('transactions/create', $data);
    }

    // Process a Return
    public function processReturn($id)
    {
        $borrow = $this->borrowModel->find($id);
        if (!$borrow || $borrow['status'] === 'returned') {
            return redirect()->to('/transactions')->with('error', 'Invalid transaction.');
        }

        $currentDate = date('Y-m-d');
        $dueDate     = $borrow['due_date'];
        
        // Calculate overdue days
        $overdueDays = 0;
        if ($currentDate > $dueDate) {
            $diff = strtotime($currentDate) - strtotime($dueDate);
            $overdueDays = floor($diff / (60 * 60 * 24));
        }

        // Fine Logic: Rp2000 per day
        $fineAmount = $overdueDays * 2000;

        helper(['form']);
        if (strtolower((string)$this->request->getMethod()) === 'post') {
            // Proceed with return
            $this->returnModel->save([
                'borrow_id'   => $id,
                'return_date' => $currentDate,
                'fine_amount' => $fineAmount,
            ]);

            // Update borrow status
            $this->borrowModel->update($id, ['status' => 'returned']);

            // Increase stock
            $book = $this->bookModel->find($borrow['book_id']);
            $this->bookModel->update($book['id'], ['stock' => $book['stock'] + 1]);

            // Create fine if applicable
            if ($fineAmount > 0) {
                $this->fineModel->save([
                    'user_id'     => $borrow['user_id'],
                    'borrow_id'   => $id,
                    'fine_amount' => $fineAmount,
                    'reason'      => "Overdue by {$overdueDays} days",
                    'status'      => 'unpaid'
                ]);
                return redirect()->to('/transactions/returns')->with('success', "Book returned successfully. A fine of Rp{$fineAmount} has been issued.");
            }

            return redirect()->to('/transactions/returns')->with('success', 'Book returned successfully with no fine.');
        }

        // Show confirmation screen
        $data = [
            'title'       => 'Process Return',
            'borrow'      => $borrow,
            'overdueDays' => $overdueDays,
            'fineAmount'  => $fineAmount,
            'currentDate' => $currentDate,
        ];
        return view('transactions/process_return', $data);
    }
}
