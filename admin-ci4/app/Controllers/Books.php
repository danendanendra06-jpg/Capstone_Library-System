<?php

namespace App\Controllers;

use App\Models\BookModel;
use App\Models\CategoryModel;

class Books extends BaseController
{
    protected $bookModel;
    protected $categoryModel;

    public function __construct()
    {
        $this->bookModel = new BookModel();
        $this->categoryModel = new CategoryModel();
    }

    public function index()
    {
        $search = $this->request->getGet('search');
        
        $this->bookModel->select('books.*, categories.name as category_name')
                        ->join('categories', 'categories.id = books.category_id');

        if ($search) {
            $this->bookModel->groupStart()
                            ->like('title', $search)
                            ->orLike('author', $search)
                            ->orLike('isbn', $search)
                            ->groupEnd();
        }

        $data = [
            'title' => 'Manage Books',
            'books' => $this->bookModel->paginate(10),
            'pager' => $this->bookModel->pager,
            'search' => $search
        ];

        return view('books/index', $data);
    }

    public function new()
    {
        $data = [
            'title' => 'Add New Book',
            'categories' => $this->categoryModel->findAll()
        ];
        return view('books/create', $data);
    }

    public function create()
    {
        $rules = [
            'title' => 'required|min_length[3]|max_length[255]',
            'author' => 'required|min_length[3]|max_length[150]',
            'category_id' => 'required|is_natural_no_zero',
            'stock' => 'required|integer',
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $this->bookModel->save([
            'title' => $this->request->getPost('title'),
            'author' => $this->request->getPost('author'),
            'category_id' => $this->request->getPost('category_id'),
            'isbn' => $this->request->getPost('isbn'),
            'stock' => $this->request->getPost('stock'),
        ]);

        return redirect()->to('/books')->with('success', 'Book added successfully.');
    }

    public function edit($id = null)
    {
        $data = [
            'title' => 'Edit Book',
            'book' => $this->bookModel->find($id),
            'categories' => $this->categoryModel->findAll()
        ];
        if (empty($data['book'])) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('Book not found');
        }
        return view('books/create', $data);
    }

    public function update($id = null)
    {
        $rules = [
            'title' => 'required|min_length[3]|max_length[255]',
            'author' => 'required|min_length[3]|max_length[150]',
            'category_id' => 'required|is_natural_no_zero',
            'stock' => 'required|integer',
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $this->bookModel->update($id, [
            'title' => $this->request->getPost('title'),
            'author' => $this->request->getPost('author'),
            'category_id' => $this->request->getPost('category_id'),
            'isbn' => $this->request->getPost('isbn'),
            'stock' => $this->request->getPost('stock'),
        ]);

        return redirect()->to('/books')->with('success', 'Book updated successfully.');
    }

    public function delete($id = null)
    {
        $this->bookModel->delete($id);
        return redirect()->to('/books')->with('success', 'Book deleted successfully.');
    }
}
