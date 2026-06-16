<?php

namespace App\Models;

use CodeIgniter\Model;

class BookModel extends Model
{
    protected $table = 'books';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $useSoftDeletes = false;
    protected $allowedFields = ['title', 'author', 'category_id', 'isbn', 'publisher', 'publication_year', 'stock', 'cover_image'];

    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    protected $validationRules = [
        'title'       => 'required|min_length[3]|max_length[255]',
        'author'      => 'required|min_length[3]|max_length[150]',
        'category_id' => 'required|is_natural_no_zero',
        'stock'       => 'required|integer',
    ];

    public function getBooksWithCategory()
    {
        return $this->select('books.*, categories.name as category_name')
                    ->join('categories', 'categories.id = books.category_id')
                    ->findAll();
    }
}
