<?php

namespace App\Http\Controllers;

use App\DataTables\ProjectDataTable;
use App\Http\Requests\ProjectRequest;
use App\Models\Project\Project;
use App\Models\Project\ProjectStage;
use App\Models\Cash\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProjectController extends Controller
{
    // Show all projects
    public function index(ProjectDataTable $dataTable)
    {
        return $dataTable->render('project.index');
    }


    // Show a single project
    public function show($url_address)
    {
        // Find the project by its url_address
        $project = Project::with(['stages', 'investors', 'transactions'])
            ->where('url_address', $url_address)
            ->firstOrFail();

        // Return a view with the project data
        return view('project.show', compact('project'));
    }

    // Create a new project
    // Show the form for creating a new project
    public function create()
    {
        return view('project.create'); // Assuming you have a 'create' view for the project
    }

    public function store(ProjectRequest $request)
    {
        Project::create($request->validated());

        //inform the user
        return redirect()->route('project.index')
            ->with('success', 'تمت أضافة البيانات بنجاح ');
    }

    // Update an existing project
    public function update(Request $request, $id)
    {
        $project = Project::findOrFail($id);

        $validated = $request->validate([
            'url_address' => 'required|string|max:255',
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'budget' => 'required|numeric',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
        ]);

        $project->update($validated);
        $project->user_id_update = Auth::id(); // Store the updater's user ID
        $project->save();

        return response()->json($project);
    }

    // Delete a project
    public function destroy($url_address)
    {
        $project = Project::with(['stages', 'investors', 'transactions'])
            ->where('url_address', $url_address)
            ->firstOrFail();
        $project->delete();

        //inform the user
        return redirect()->route('project.index')
            ->with('success', 'تمت حذف البيانات بنجاح ');
    }

    // Add a stage to a project
    public function addStage(Request $request, $projectId)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
        ]);

        $project = Project::findOrFail($projectId);

        $stage = new ProjectStage($validated);
        $stage->project_id = $project->id;
        $stage->user_id_create = Auth::id();
        $stage->user_id_update = Auth::id();
        $stage->save();

        return response()->json($stage, 201);
    }

    // Add an investor to a project
    public function addInvestor(Request $request, $projectId)
    {
        $validated = $request->validate([
            'investor_id' => 'required|exists:investors,id',
            'investment_amount' => 'required|numeric',
        ]);

        $project = Project::findOrFail($projectId);

        // Check if the investor is already linked to this project
        $existingInvestor = $project->investors()->where('investor_id', $validated['investor_id'])->first();
        if ($existingInvestor) {
            return response()->json(['message' => 'Investor already added to this project'], 400);
        }

        $project->investors()->attach($validated['investor_id'], ['investment_amount' => $validated['investment_amount']]);

        return response()->json(['message' => 'Investor added successfully']);
    }

    // Add a transaction to a project
    public function addTransaction(Request $request, $projectId)
    {
        $validated = $request->validate([
            'amount' => 'required|numeric',
            'transaction_type' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        $project = Project::findOrFail($projectId);

        $transaction = new Transaction($validated);
        $transaction->project_id = $project->id;
        $transaction->save();

        return response()->json($transaction, 201);
    }
}
