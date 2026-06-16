<?php
namespace App\Commands;
use CodeIgniter\CLI\BaseCommand;
use CodeIgniter\CLI\CLI;

class TestUpdate extends BaseCommand
{
    protected $group       = 'Custom';
    protected $name        = 'test:update';
    protected $description = 'Tests updating fine model';

    public function run(array $params)
    {
        $fineModel = new \App\Models\FineModel();
        
        $result = $fineModel->update(3, [
            'payment_status' => 'PAID',
            'payment_method' => 'DIGITAL'
        ]);

        if ($result) {
            CLI::write('Update successful!', 'green');
        } else {
            CLI::write('Update failed!', 'red');
            CLI::write(print_r($fineModel->errors(), true));
        }
    }
}
