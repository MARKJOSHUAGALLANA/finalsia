<?php

namespace App\Http\Controllers;

use App\Models\Saint;
use Illuminate\Http\Request;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use Dompdf\Dompdf;
use Dompdf\Options;
use Illuminate\Support\Facades\CSV;
use Illuminate\Support\Facades\Response as FileResponse;

class SaintController extends Controller
{
    public function index()
    {
        $saints = Saint::all();
        return view('saints.index', compact('saints'));
    }

    public function create()
    {
        return view('saints.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'description' => 'nullable',
            'feast_day' => 'nullable|date',
        ]);

        Saint::create($request->all());

        return redirect()->route('saints.index')->with('success', 'Saint created successfully.');
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required',
            'description' => 'nullable',
            'feast_day' => 'nullable|date',
        ]);

        $saint = Saint::findOrFail($id);
        $saint->update($request->all());

        return response()->json(['message' => 'Saint updated successfully.']);
    }

    public function destroy($id)
    {
        $saint = Saint::findOrFail($id);
        $saint->delete();
    
        return response()->json(['message' => 'Saint deleted successfully.']);
    }

    public function generateQRCode(Saint $saint)
    {
        $qrCode = QrCode::size(200)->generate($saint->name . ' - ' . $saint->description . ' - ' . $saint->feast_day);
        return response($qrCode, 200, ['Content-Type' => 'image/svg+xml']);
    }

    public function generatePDF()
    {
        // Fetch data from the database or prepare content
        $saints = Saint::all();
        $content = '<h1>List of Saints</h1>';
        foreach ($saints as $saint) {
            $content .= '<p>Name: ' . $saint->name . '</p>';
            $content .= '<p>Description: ' . $saint->description . '</p>';
            $content .= '<p>Feast Day: ' . $saint->feast_day . '</p>';
            $content .= '<hr>';
        }

        // Generate PDF
        $dompdf = new Dompdf();
        $options = new Options();
        $options->set('defaultFont', 'Arial');
        $dompdf->setOptions($options);
        $dompdf->loadHtml($content);
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();

        // Output PDF to the browser
        return $dompdf->stream('saints.pdf');
    }

    public function exportCSV()
{
    $saints = Saint::all();

    // Define CSV headers
    $headers = array(
        "Content-type" => "text/csv",
        "Content-Disposition" => "attachment; filename=saints.csv",
        "Pragma" => "no-cache",
        "Cache-Control" => "must-revalidate, post-check=0, pre-check=0",
        "Expires" => "0"
    );

    // Create a file pointer
    $output = fopen("php://output", "w");

    // Write CSV headers
    fputcsv($output, array('Name', 'Description', 'Feast Day'));

    // Write CSV data
    foreach ($saints as $saint) {
        fputcsv($output, array($saint->name, $saint->description, $saint->feast_day));
    }

    // Close the file pointer
    fclose($output);

    // Return CSV file as response
    return FileResponse::make('', 200, $headers);
}
   
}
