<?php

namespace App\Http\Controllers;

use App\Models\Data;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;
use Maatwebsite\Excel\Facades\Excel;

class UploadController extends Controller
{
    public function uploadFile(Request $request)
    {
      /* $request->validate([
            'file' => 'required|max:2048|mimes:json,ldif,csv',
        ]);*/

        $file = $request->file('file');

            if ($file== null){
                Session::flash('error', 'Dodaj plik.');
                return redirect()->back()->with('error', 'Nie podaleś pliku.');
            }
        $mime = $file->getMimeType();



        switch ($mime) {

            case 'application/json':
                $this->processJsonFile($file);
                break;

            case 'text/plain':

                $extension = $file->getClientOriginalExtension();

                switch ($extension) {
                    case 'ldif':
                        $this->processLdifFile($file);
                        break;

                    case 'csv':
                        $this->processCsvFile($file);
                        break;

                    default:
                        return redirect()->back()->with('error', 'Niewspierany format pliku.');
                }
                break;
            default:
                return redirect()->back()->with('error', 'Niewspierany format pliku.');
        }

        return redirect()->back()->with('success', 'Plik został przetworzony pomyślnie.');
    }



    protected function processJsonFile($file)
    {
        $jsonContent = json_decode(file_get_contents($file), true);
        $cols = $jsonContent['cols'];
        $data = $jsonContent['data'];
        foreach ($data as $row) {
            $rowData = array_combine($cols, $row);
            $rowData['Format']="json";

            Data::create($rowData);
        }

    }

    protected function processLdifFile($file)
    {
        $content = file_get_contents($file);

        $entries = explode("\n\n", $content);

        foreach ($entries as $entry) {
            if (empty($entry)) {
                continue;
            }
            $attributes = explode("\n", $entry);

            $data = [];
            foreach ($attributes as $attribute) {
                list($key, $value) = explode(':', $attribute);
                $data[trim($key)] = trim($value);
            }


            $data['Format']="ldif";

            Data::create($data);
        }
    }

    protected function processCsvFile($file)
    {

        $handle = fopen($file, 'r');
        fgets($handle);

        while (($line = fgets($handle)) !== false) {
            $data = explode('|', $line);

            Data::create([
                'Customer' => $data[0],
                'Country' => $data[1],
                'Order' => $data[2],
                'Status' => $data[3],
                'Group' => $data[4],
                'Format'=>"csv"

            ]);
        }

        fclose($handle);

    }
}
