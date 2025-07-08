<?php
require_once __DIR__ . '/../vendor/autoload.php';

class PDFGenerator
{
    public static function generateEmployeePDF($data)
    {
        $options = new \Dompdf\Options();
        $options->set('isRemoteEnabled', true);
        $options->set('defaultFont', 'Helvetica');

        $dompdf = new \Dompdf\Dompdf($options);

        $imgPath = BASE_URL . 'uploads/' . $data['profileImg'];

        $html = "
        <h2>Employee Details</h2>
        <img src='{$imgPath}' width='100' style='margin-bottom: 20px;'>
        <p><strong>Name:</strong> {$data['name']}</p>
        <p><strong>Email:</strong> {$data['email']}</p>
        <p><strong>Created:</strong> {$data['created_at']}</p>
    ";

        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4');
        $dompdf->render();
        $dompdf->stream("employee_" . time() . ".pdf", ['Attachment' => true]);
    }
}
