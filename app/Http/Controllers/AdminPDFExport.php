<?php

namespace App\Http\Controllers;

use App\Models\User;




use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;


class AdminPDFExport extends Controller
{
    public function AllLessonCountExport(){
        $users = User::orderBy('name', 'asc')->get();

  
        $data = [
            
            'users' => $users
        ]; 
            
        $pdf = Pdf::loadView('remedial.pdfexport', $data);
     
        return $pdf->stream('remedial.pdf');
    }
    
}
