<?php

namespace App\Models;

use CodeIgniter\Model;

class ReturnModel extends Model
{
    protected $table = 'returns';
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
        $builder = $this->select('returns.*, borrows.user_id, borrows.book_id, users.username as member_name, books.title as book_title')
                        ->join('borrows', 'borrows.id = returns.borrow_id')
                        ->join('users', 'users.id = borrows.user_id')
                        ->join('books', 'books.id = borrows.book_id');

        if (!empty($search)) {
            $builder->groupStart()
                    ->like('users.username', $search)
                    ->orLike('books.title', $search)
                    ->groupEnd();
        }

        return [
            'returns' => $builder->orderBy('returns.id', 'DESC')->paginate($perPage),
            'pager'   => $this->pager
        ];
    }
}
