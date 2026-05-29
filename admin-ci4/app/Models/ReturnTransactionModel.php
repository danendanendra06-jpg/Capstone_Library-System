<?php

namespace App\Models;

use CodeIgniter\Model;

class ReturnTransactionModel extends Model
{
    protected $table = 'return_transactions';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $useSoftDeletes = false;
    protected $allowedFields = ['borrow_id', 'return_date', 'fine_amount'];

    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    public function getReturnsWithDetailsPaginated($search = '', $perPage = 10)
    {
        $builder = $this->select('return_transactions.*, borrow_transactions.user_id, borrow_transactions.book_id, users.username as member_name, books.title as book_title')
                        ->join('borrow_transactions', 'borrow_transactions.id = return_transactions.borrow_id')
                        ->join('users', 'users.id = borrow_transactions.user_id')
                        ->join('books', 'books.id = borrow_transactions.book_id');

        if (!empty($search)) {
            $builder->groupStart()
                    ->like('users.username', $search)
                    ->orLike('books.title', $search)
                    ->groupEnd();
        }

        return [
            'returns' => $builder->orderBy('return_transactions.id', 'DESC')->paginate($perPage),
            'pager'   => $this->pager
        ];
    }
}
