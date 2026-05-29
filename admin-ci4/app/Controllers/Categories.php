<?php

namespace App\Controllers;

use App\Models\CategoryModel;

class Categories extends BaseController
{
    protected $categoryModel;

    public function __construct()
    {
        $this->categoryModel = new CategoryModel();
    }

    public function index()
    {
        $data = [
            'title' => 'Manage Categories',
            'categories' => $this->categoryModel->paginate(10),
            'pager' => $this->categoryModel->pager
        ];
        return view('categories/index', $data);
    }

    public function new()
    {
        return view('categories/form', ['title' => 'Add Category']);
    }

    public function create()
    {
        $this->categoryModel->save([
            'name' => $this->request->getPost('name'),
            'description' => $this->request->getPost('description')
        ]);
        return redirect()->to('/categories')->with('success', 'Category added.');
    }

    public function edit($id = null)
    {
        $data = [
            'title' => 'Edit Category',
            'category' => $this->categoryModel->find($id)
        ];
        return view('categories/form', $data);
    }

    public function update($id = null)
    {
        $this->categoryModel->update($id, [
            'name' => $this->request->getPost('name'),
            'description' => $this->request->getPost('description')
        ]);
        return redirect()->to('/categories')->with('success', 'Category updated.');
    }

    public function delete($id = null)
    {
        $this->categoryModel->delete($id);
        return redirect()->to('/categories')->with('success', 'Category deleted.');
    }
}
