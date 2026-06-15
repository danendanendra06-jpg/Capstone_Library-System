<?php

namespace App\Models;

use CodeIgniter\Model;

class BorrowModel extends Model
{
    protected $table = 'borrows';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $useSoftDeletes = false;
    protected $allowedFields = ['user_id', 'book_id', 'borrow_date', 'due_date', 'status', 'return_condition', 'late_days'];

    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    public function getTransactionsWithDetailsPaginated($search = '', $perPage = 10)
    {
        $builder = $this->select('borrows.*, users.username as member_name, books.title as book_title')
                        ->join('users', 'users.id = borrows.user_id')
                        ->join('books', 'books.id = borrows.book_id');

        if (!empty($search)) {
            $builder->groupStart()
                    ->like('users.username', $search)
                    ->orLike('books.title', $search)
                    ->groupEnd();
        }

        return [
            'transactions' => $builder->orderBy('borrows.id', 'DESC')->paginate($perPage),
            'pager'        => $this->pager
        ];
    }

    public function getTransactionsWithDetails($limit = 5)
    {
        return $this->select('borrows.*, users.username as member_name, books.title as book_title')
                    ->join('users', 'users.id = borrows.user_id')
                    ->join('books', 'books.id = borrows.book_id')
                    ->orderBy('borrows.id', 'DESC')
                    ->findAll($limit);
    }
}
