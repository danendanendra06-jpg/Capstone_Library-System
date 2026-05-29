<?php

namespace App\Models;

use CodeIgniter\Model;

class BorrowTransactionModel extends Model
{
    protected $table = 'borrow_transactions';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $useSoftDeletes = false;
    protected $allowedFields = ['user_id', 'book_id', 'borrow_date', 'due_date', 'status'];

    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    public function getTransactionsWithDetails()
    {
        return $this->select('borrow_transactions.*, users.username as member_name, books.title as book_title')
                    ->join('users', 'users.id = borrow_transactions.user_id')
                    ->join('books', 'books.id = borrow_transactions.book_id')
                    ->orderBy('borrow_transactions.id', 'DESC')
                    ->findAll();
    }
}
