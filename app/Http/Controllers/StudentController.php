<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Student;
use PhpOffice\PhpSpreadsheet\IOFactory;
use Inertia\Inertia;

class StudentController extends Controller
{
    public function index()
    {
        $students = Student::where('status', 'active')->get(); // Only fetch active students
        return Inertia::render('SSCAdmin/Student', [
            'students' => $students, // Pass to the view
        ]);
    }

    public function import(Request $request)
    {
        // Validate the file input
        $request->validate([
            'file' => 'required|mimes:xlsx,xls',
        ]);

        // Load the uploaded Excel file
        $file = $request->file('file');
        $spreadsheet = IOFactory::load($file->getPathname());

        // Get the first worksheet
        $worksheet = $spreadsheet->getActiveSheet();
        $rows = $worksheet->toArray();

        $updatedCount = 0;
        $createdCount = 0;

        // Get all current student IDs to track which ones are not in the import
        $existingStudentIds = Student::pluck('id_number')->toArray();
        $importedStudentIds = [];

        // Skip the first row if it contains headers
        foreach ($rows as $index => $row) {
            if ($index === 0) continue; // Skip header row

            // Check if all required fields are present
            if (!empty($row[0]) && !empty($row[1]) && !empty($row[2])) {
                // Store the ID for tracking
                $importedStudentIds[] = $row[2];

                // Use updateOrCreate to update existing records or create new ones
                $student = Student::updateOrCreate(
                    ['id_number' => $row[2]], // Check if a student with this ID exists
                    [
                        'first_name' => $row[0],
                        'last_name' => $row[1],
                        'univ_email' => $row[3],
                        'college' => $row[4],
                        'contact' => $row[5],
                        'status' => 'active' // Set status to active for all imported students
                    ]
                );

                if ($student->wasRecentlyCreated) {
                    $createdCount++;
                } else {
                    $updatedCount++;
                }
            }
        }

        // Set status to 'inactive' for students not in the current import
        $studentsToDeactivate = array_diff($existingStudentIds, $importedStudentIds);
        if (!empty($studentsToDeactivate)) {
            Student::whereIn('id_number', $studentsToDeactivate)
                  ->update(['status' => 'inactive']);
        }

        return redirect()->route('student.index')->with('success', "Import completed. Created: $createdCount, Updated: $updatedCount");
    }

    public function store(Request $request)
    {
        // Validate the input fields in a single call
        $validated = $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'id_number' => 'required|string|max:255',
            'univ_email' => 'required|string|max:255',
            'college' => 'required|string|max:255',
            'contact' => 'required|string|max:255',
            'status' => 'required|string|max:255',
        ]);
                    
        // Create a new college using the validated data
        Student::create($validated);
                    
        // Redirect with a success message
        return redirect()->back()->with('success', 'Student successfully added.');
    }
}