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
            $words = explode(' ', trim($search));
            $this->bookModel->groupStart();
            foreach ($words as $word) {
                if (trim($word) !== '') {
                    $this->bookModel->groupStart()
                                    ->like('title', trim($word))
                                    ->orLike('author', trim($word))
                                    ->orLike('isbn', trim($word))
                                    ->groupEnd();
                }
            }
            $this->bookModel->groupEnd();
        }

        $data = [
            'title' => 'Manage Books',
            'books' => $this->bookModel->paginate(10),
            'pager' => $this->bookModel->pager,
            'search' => $search
        ];

        if ($this->request->getGet('ajax') == 1) {
            $suggestions = [];
            $q = strtolower(trim($search));
            foreach($data['books'] as $b) {
                if ($q === '' || strpos(strtolower($b['title']), $q) !== false) {
                    $suggestions[] = $b['title'];
                }
                if ($q !== '' && strpos(strtolower($b['author']), $q) !== false) {
                    $suggestions[] = $b['author'];
                }
            }
            return $this->response->setJSON(array_values(array_unique($suggestions)));
        }

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
            'isbn' => [
                'rules' => 'required|is_unique[books.isbn]|regex_match[/^[A-Za-z0-9]{3}-[A-Za-z0-9]{3}-[A-Za-z0-9]{3}-[A-Za-z0-9]{3}$/]',
                'errors' => [
                    'is_unique' => 'This ISBN is already registered.',
                    'regex_match' => 'ISBN must be in xxx-xxx-xxx-xxx format.'
                ]
            ],
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
            'publisher' => $this->request->getPost('publisher'),
            'publication_year' => $this->request->getPost('publication_year'),
            'cover_image' => $this->request->getPost('cover_image'),
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
            'isbn' => [
                'rules' => 'required|is_unique[books.isbn,id,' . $id . ']|regex_match[/^[A-Za-z0-9]{3}-[A-Za-z0-9]{3}-[A-Za-z0-9]{3}-[A-Za-z0-9]{3}$/]',
                'errors' => [
                    'is_unique' => 'This ISBN is already registered.',
                    'regex_match' => 'ISBN must be in xxx-xxx-xxx-xxx format.'
                ]
            ],
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
            'publisher' => $this->request->getPost('publisher'),
            'publication_year' => $this->request->getPost('publication_year'),
            'cover_image' => $this->request->getPost('cover_image'),
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
