<?php

namespace App\Controllers;

use App\Models\BorrowTransactionModel;
use App\Models\FineModel;
use Dompdf\Dompdf;
use Dompdf\Options;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class Reports extends BaseController
{
    protected $borrowModel;
    protected $fineModel;

    public function __construct()
    {
        $this->borrowModel = new BorrowTransactionModel();
        $this->fineModel = new FineModel();
    }

    public function index()
    {
        $data = [
            'title' => 'Reports',
        ];
        return view('reports/index', $data);
    }

    public function generate()
    {
        $type = $this->request->getPost('report_type'); // borrows, returns, fines
        $format = $this->request->getPost('format'); // pdf, excel
        $startDate = $this->request->getPost('start_date');
        $endDate = $this->request->getPost('end_date');

        if ($type === 'fines') {
            $builder = $this->fineModel->select('fines.*, users.username as member_name, books.title as book_title')
                                       ->join('users', 'users.id = fines.user_id')
                                       ->join('borrow_transactions', 'borrow_transactions.id = fines.borrow_id')
                                       ->join('books', 'books.id = borrow_transactions.book_id');
            if ($startDate && $endDate) {
                $builder->where('fines.created_at >=', $startDate . ' 00:00:00')
                        ->where('fines.created_at <=', $endDate . ' 23:59:59');
            }
            $results = $builder->findAll();
        } else {
            // borrows or returns
            $builder = $this->borrowModel->select('borrow_transactions.*, users.username as member_name, books.title as book_title')
                                         ->join('users', 'users.id = borrow_transactions.user_id')
                                         ->join('books', 'books.id = borrow_transactions.book_id');
            if ($type === 'returns') {
                $builder->whereIn('borrow_transactions.status', ['RETURNED', 'DAMAGED', 'LOST']);
            } else {
                $builder->where('borrow_transactions.status', 'BORROWED');
            }

            if ($startDate && $endDate) {
                $builder->where('borrow_transactions.borrow_date >=', $startDate)
                        ->where('borrow_transactions.borrow_date <=', $endDate);
            }
            $results = $builder->findAll();
        }

        if ($format === 'pdf') {
            return $this->exportPdf($type, $results);
        } elseif ($format === 'excel') {
            return $this->exportExcel($type, $results);
        }

        return redirect()->back()->with('error', 'Invalid format selected.');
    }

    private function exportPdf($type, $data)
    {
        $html = view('reports/pdf_template', ['type' => $type, 'data' => $data]);

        $options = new Options();
        $options->set('defaultFont', 'Helvetica');
        $dompdf = new Dompdf($options);
        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'landscape');
        $dompdf->render();

        $dompdf->stream("Report_{$type}_" . date('Ymd_His') . ".pdf", ["Attachment" => true]);
    }

    private function exportExcel($type, $data)
    {
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        if ($type === 'fines') {
            $sheet->setCellValue('A1', 'ID');
            $sheet->setCellValue('B1', 'Member');
            $sheet->setCellValue('C1', 'Book');
            $sheet->setCellValue('D1', 'Amount');
            $sheet->setCellValue('E1', 'Type');
            $sheet->setCellValue('F1', 'Status');

            $row = 2;
            foreach ($data as $item) {
                $sheet->setCellValue('A' . $row, $item['id']);
                $sheet->setCellValue('B' . $row, $item['member_name']);
                $sheet->setCellValue('C' . $row, $item['book_title']);
                $sheet->setCellValue('D' . $row, $item['amount']);
                $sheet->setCellValue('E' . $row, $item['fine_type']);
                $sheet->setCellValue('F' . $row, $item['payment_status']);
                $row++;
            }
        } else {
            $sheet->setCellValue('A1', 'ID');
            $sheet->setCellValue('B1', 'Member');
            $sheet->setCellValue('C1', 'Book');
            $sheet->setCellValue('D1', 'Borrow Date');
            $sheet->setCellValue('E1', 'Due Date');
            $sheet->setCellValue('F1', 'Status');
            $sheet->setCellValue('G1', 'Late Days');
            $sheet->setCellValue('H1', 'Condition');

            $row = 2;
            foreach ($data as $item) {
                $sheet->setCellValue('A' . $row, $item['id']);
                $sheet->setCellValue('B' . $row, $item['member_name']);
                $sheet->setCellValue('C' . $row, $item['book_title']);
                $sheet->setCellValue('D' . $row, $item['borrow_date']);
                $sheet->setCellValue('E' . $row, $item['due_date']);
                $sheet->setCellValue('F' . $row, $item['status']);
                $sheet->setCellValue('G' . $row, $item['late_days']);
                $sheet->setCellValue('H' . $row, $item['return_condition']);
                $row++;
            }
        }

        $writer = new Xlsx($spreadsheet);
        $fileName = "Report_{$type}_" . date('Ymd_His') . ".xlsx";
        
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment; filename="'. urlencode($fileName).'"');
        $writer->save('php://output');
        exit;
    }
}
