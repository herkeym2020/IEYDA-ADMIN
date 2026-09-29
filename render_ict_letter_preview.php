<?php

use Barryvdh\DomPDF\Facade\Pdf;

require __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

// Create a sample applicant-like object for preview
$applicant = new stdClass();
$applicant->registration_number = 'IEYDA-ICT-2026-0001';
$applicant->full_name = 'Abdullahi Ibrahim';
$applicant->address = '12 Adebayo Street, Ilorin';
$applicant->lga = 'Ilorin West';
$applicant->state = 'Kwara';
$applicant->gender = 'male';
$applicant->date_of_birth = Carbon\Carbon::parse('2005-04-15');
$applicant->phone = '08012345678';
$applicant->email = 'abdullahi@example.com';

$admissionNumber = 'IEYDA-ADM-2026-0001';
$courses = 'Digital Marketing';
$vocational = null;

$html = view('emails.ict-admission-letter', compact('applicant', 'admissionNumber', 'courses', 'vocational'))->render();

$pdf = Pdf::loadHTML($html);
$pdf->setPaper('a4', 'portrait');

$outputPath = __DIR__ . '/public/ict-admission-letter-preview.pdf';
file_put_contents($outputPath, $pdf->output());

echo "PDF generated: " . $outputPath . "\n";