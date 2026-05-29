<?php

namespace App\Models;

use CodeIgniter\Model;

class FineModel extends Model
{
    protected $table = 'fines';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $useSoftDeletes = false;
    protected $allowedFields = ['user_id', 'borrow_id', 'fine_amount', 'reason', 'status'];
    protected $useTimestamps = true;

    public function getFinesPaginated($search = '', $perPage = 10)
    {
        $builder = $this->select('fines.*, users.username as member_name, users.email, books.title as book_title')
                        ->join('users', 'users.id = fines.user_id')
                        ->join('borrow_transactions', 'borrow_transactions.id = fines.borrow_id')
                        ->join('books', 'books.id = borrow_transactions.book_id');

        if (!empty($search)) {
            $builder->groupStart()
                    ->like('users.username', $search)
                    ->orLike('books.title', $search)
                    ->groupEnd();
        }

        return [
            'fines' => $builder->orderBy('fines.id', 'DESC')->paginate($perPage),
            'pager' => $this->pager
        ];
    }

    public function getFineDetail($id)
    {
        return $this->select('fines.*, users.username as member_name, users.email, books.title as book_title, borrow_transactions.borrow_date, borrow_transactions.due_date')
                    ->join('users', 'users.id = fines.user_id')
                    ->join('borrow_transactions', 'borrow_transactions.id = fines.borrow_id')
                    ->join('books', 'books.id = borrow_transactions.book_id')
                    ->where('fines.id', $id)
                    ->first();
    }
}
