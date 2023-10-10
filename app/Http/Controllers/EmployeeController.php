<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use App\Models\Department;
use Illuminate\Http\Request;
use Inertia\Inertia;
use DB;

class EmployeeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Dolgozók lekérése
        $employees = Employee::select(
            'employees.id', 
            'employees.name', 
            'email', 
            'phone', 
            'department_id', 
            'departments.name as department')
            ->join('departments', 'departments.id', '=', 'employees.department_id')
            ->paginate(10);
        // Irodák lekérése
        $departments = Department::all();
        
        return Inertia::render('Employees/Index', [
            'employees' => $employees, 
            'departments' => $departments
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return Inertia::render('Employees/Create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request){
        // Validáció
        $request->validate([
                     'name' => 'required|max:150',
                    'email' => 'required|max:80',
                    'phone' => 'required|max:15',
            'department_id' => 'required|numeric',
        ]);
        
        // Új dolgozó létrehozása
        $employee = new Employee($request->input());
        // Új dolgozó mentése
        $employee->save();
        
        return redirect('employees');
    }

    /**
     * Display the specified resource.
     */
    public function show(Employee $employee){}

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Employee $employee){
        return Inertia::render(
            'Employees/Edit', 
            ['employee' => $employee]
        );
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Employee $employee)
    {
        // Validálás
        $request->validate([
                     'name' => 'required|max:150',
                    'email' => 'required|max:80',
                    'phone' => 'required|max:15',
            'department_id' => 'required|numeric',
        ]);
        // Dolgozó frissítése
        $employee->update($request->input());
        
        return redirect('employees');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Employee $employee)
    {
        // Dolgozó törlése
        $employee->delete();
        
        return redirect('employees');
    }
    
    public function EmployeeByDepartment(){
        $data = Employee::selectDB::raw('count(employees.id) as count, departments.name')
            ->join('departments', 'departments.id', '=', 'employees.department_id')
            ->groupBy('departments.name')
            ->get();
        
        return Inertia::render('Employes/Graphic', ['data' => $data]);
    }
    
    public function reports(){
        $employees = Employee::select('employees.id', 'employees.name', 'email', 'phone', 
            'department_id', 'departments.name as department')
            ->join('departments', 'departments.id', '=', 'employees.department_id')
            ->get();
        
        $departments = Department::all();
        
        return Inertia::render('Employees/Reports', [
            'employees' => $employees, 
            'departments' => $departments
        ]);
    }
}
