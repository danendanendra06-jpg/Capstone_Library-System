<?php

namespace App\Controllers;

use App\Models\BorrowModel;
use App\Models\ReturnModel;
use App\Models\FineModel;
use App\Models\BookModel;
use App\Models\UserModel;

class Borrows extends BaseController
{
    protected $borrowModel;
    protected $returnModel;
    protected $fineModel;
    protected $bookModel;
    protected $userModel;

    public function __construct()
    {
        $this->borrowModel = new BorrowModel();
        $this->returnModel = new ReturnModel();
        $this->fineModel   = new FineModel();
        $this->bookModel   = new BookModel();
        $this->userModel   = new UserModel();
    }

    // List all Borrow Transactions
    public function index()
    {
        $search = $this->request->getVar('search') ?? '';
        $transData = $this->borrowModel->getTransactionsWithDetailsPaginated($search, 10);

        $data = [
            'title'        => 'Borrows',
            'transactions' => $transData['transactions'],
            'pager'        => $transData['pager'],
            'search'       => $search
        ];
        return view('borrows/index', $data);
    }

    // List all Return Transactions
    public function returns()
    {
        $search = $this->request->getVar('search') ?? '';
        $returnData = $this->returnModel->getReturnsWithDetailsPaginated($search, 10);

        $data = [
            'title'   => 'Return Transactions',
            'returns' => $returnData['returns'],
            'pager'   => $returnData['pager'],
            'search'  => $search
        ];
        return view('borrows/returns', $data);
    }

    // Detail view for a transaction
    public function show($id = null)
    {
        $borrow = $this->borrowModel->select('borrows.*, users.username as member_name, books.title as book_title')
                                    ->join('users', 'users.id = borrows.user_id')
                                    ->join('books', 'books.id = borrows.book_id')
                                    ->where('borrows.id', $id)
                                    ->first();

        if (!$borrow) {
            return redirect()->to('/borrows')->with('error', 'Borrow record not found.');
        }

        $data = [
            'title'  => 'Transaction Details',
            'borrow' => $borrow
        ];
        return view('borrows/show', $data);
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

                $borrowDate = $this->request->getVar('borrow_date');
                $dueDate = $this->request->getVar('due_date');

                if ($dueDate < $borrowDate) {
                    return redirect()->back()->withInput()->with('error', 'Due Date cannot be earlier than Borrow Date.');
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

                return redirect()->to('/borrows')->with('success', 'Borrow record created successfully.');
            } else {
                $data['validation'] = $this->validator;
            }
        }

        $data['title'] = 'Create Borrow';
        // Get all members for dropdown
        $data['users'] = $this->userModel->where('role', 'member')->findAll();
        // Get available books
        $data['books'] = $this->bookModel->where('stock >', 0)->findAll();

        return view('borrows/create', $data);
    }

    // Process a Return
    public function processReturn($id)
    {
        $borrow = $this->borrowModel->find($id);
        if (!$borrow || $borrow['status'] === 'returned') {
            return redirect()->to('/borrows')->with('error', 'Invalid record.');
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
        $lateFine = $overdueDays * 2000;

        helper(['form']);
        if (strtolower((string)$this->request->getMethod()) === 'post') {
            $condition = $this->request->getPost('condition') ?? 'good';
            $additionalFine = (float)($this->request->getPost('additional_fine') ?? 0);

            $totalFine = $lateFine + $additionalFine;
            $reason = "Overdue by {$overdueDays} days.";
            if ($condition === 'damaged') {
                $reason .= " Book was returned damaged.";
            } elseif ($condition === 'lost') {
                $reason .= " Book was lost.";
            }

            $returnCondition = strtoupper($condition);

            // Update borrow status without fine_amount
            $this->borrowModel->update($id, [
                'status' => $condition === 'lost' ? 'LOST' : ($condition === 'damaged' ? 'DAMAGED' : 'RETURNED'),
                'return_condition' => $returnCondition,
                'late_days' => $overdueDays
            ]);

            // Add audit log
            $auditModel = new \App\Models\AuditLogModel();
            $auditModel->save([
                'admin_username' => session()->get('username'),
                'action' => 'RETURN_BOOK',
                'details' => 'Processed return for borrow ID ' . $id . '. Condition: ' . $returnCondition . '. Total Fines Issued: Rp' . $totalFine
            ]);

            // Update stock (only if not lost)
            if ($condition !== 'lost') {
                $book = $this->bookModel->find($borrow['book_id']);
                $this->bookModel->update($book['id'], ['stock' => $book['stock'] + 1]);
            }

            // Create fine for Late (if any)
            if ($lateFine > 0) {
                $this->fineModel->save([
                    'user_id'     => $borrow['user_id'],
                    'borrow_id'   => $id,
                    'amount'      => $lateFine,
                    'reason'      => "Overdue by {$overdueDays} days.",
                    'payment_status' => 'UNPAID',
                    'fine_type'   => 'LATE'
                ]);
            }

            // Create fine for Damaged / Lost (if any)
            if ($additionalFine > 0) {
                $fineType = ($condition === 'lost') ? 'LOST' : 'DAMAGED';
                
                $reasonStr = 'Book was returned damaged.';
                if ($condition === 'lost') {
                    $reasonStr = 'Book was lost.';
                } elseif ($condition === 'damaged') {
                    $dmgType = $this->request->getPost('damage_type') ?? 'Umum';
                    $dmgNote = $this->request->getPost('damage_note') ?? '';
                    $reasonStr = "Rusak ({$dmgType})";
                    if (!empty(trim($dmgNote))) {
                        $reasonStr .= " - " . trim($dmgNote);
                    }
                }
                $this->fineModel->save([
                    'user_id'     => $borrow['user_id'],
                    'borrow_id'   => $id,
                    'amount'      => $additionalFine,
                    'reason'      => $reasonStr,
                    'payment_status' => 'UNPAID',
                    'fine_type'   => $fineType
                ]);
            }

            // Create Notification if there is any fine
            if ($totalFine > 0) {
                $notifModel = new \App\Models\NotificationModel();
                $notifModel->save([
                    'user_id' => $borrow['user_id'],
                    'title'   => 'Fine Issued',
                    'message' => 'You have been issued fines totaling Rp' . number_format($totalFine, 0, ',', '.') . '. Silakan lakukan pembayaran melalui Cash, Kartu ATM, atau Digital (DANA, GoPay, dll) di meja admin.',
                    'is_read' => 0,
                    'sent_at' => date('Y-m-d H:i:s')
                ]);

                return redirect()->to('/borrows/returns')->with('success', "Book returned successfully. Fines totaling Rp{$totalFine} have been issued.");
            }

            return redirect()->to('/borrows/returns')->with('success', 'Book returned successfully with no fine.');
        }

        // Show confirmation screen
        $data = [
            'title'       => 'Process Return',
            'borrow'      => $borrow,
            'overdueDays' => $overdueDays,
            'fineAmount'  => $lateFine,
            'currentDate' => $currentDate,
        ];
        return view('borrows/process_return', $data);
    }
}
