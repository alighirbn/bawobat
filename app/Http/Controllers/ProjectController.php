<?php

namespace App\Http\Controllers;

use App\DataTables\ProjectDataTable;
use App\Http\Requests\ProjectRequest;
use App\Models\Project\Project;
use App\Models\Project\ProjectStage;
use App\Models\Cash\Transaction;
use App\Services\WiaScanner;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

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


    private $scanner;

    public function __construct(WiaScanner $scanner)
    {
        $this->scanner = $scanner;
    }

    /**
     * Display the list of available scanner devices.
     *
     * @return \Illuminate\View\View
     */
    public function scancreate(string $url_address)
    {
        try {
            // Retrieve the project with necessary relationships
            $project = Project::where('url_address', '=', $url_address)->first();
            // Attempt to get the list of devices
            $devices = $this->scanner->listDevices();
            return view('project.scanner', compact(['devices', 'project']));
        } catch (Exception $e) {
            // Handle error if listing devices fails

            return response()->json(['error' => 'Failed to list devices. Please try again later.'], 500);
        }
    }

    /**
     * Initiate the scan process for the selected scanner device.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function scanstore(Request $request): JsonResponse
    {
        try {
            // Validate the provided device ID
            $deviceId = $request->input('device_id');
            if (empty($deviceId)) {
                return response()->json(['error' => 'Device ID is required.'], 400);
            }

            // Connect to the selected scanner device
            $this->scanner->connect($deviceId);

            // Ensure that the directory for storing scanned images exists
            $scansDirectory = storage_path('app/public/scans/');
            if (!is_dir($scansDirectory)) {
                mkdir($scansDirectory, 0755, true);
            }

            $filename = uniqid() . '.png';
            // Generate a unique file path for saving the scanned image
            $outputPath = $scansDirectory . $filename;

            // Perform the scan and save the image
            $scannedImagePath = $this->scanner->scan($outputPath);

            // Retrieve the URL address from the request (assuming it's passed)
            $url_address = $request->input('url_address');
            if (empty($url_address)) {
                return response()->json(['error' => 'URL address is required.'], 400);
            }

            // Retrieve the project based on the provided URL address
            $project = Project::where('url_address', '=', $url_address)->first();
            if (!$project) {
                return response()->json(['error' => 'project not found for the given URL address.'], 404);
            }


            // Save the scanned image and associate it with the project
            $project->archives()->create([
                'image_path' => 'storage/scans/' . $filename,
            ]);

            // Return the URL to the scanned image
            return response()->json([
                'message' => 'Scan successful and image associated with project.',
                'image_path' => asset('storage/scans/' . basename($scannedImagePath))
            ], 200);
        } catch (Exception $e) {
            // Log and return detailed error message on failure

            return response()->json(['error' => 'Failed to scan the document. Please try again later.'], 500);
        }
    }

    public function archivecreate(string $url_address)
    {
        // Retrieve the project with necessary relationships
        $project = Project::where('url_address', '=', $url_address)->first();

        return view('project.archivecreate', compact(['project']));
    }

    public function archivestore(Request $request, string $url_address)
    {
        // Retrieve the project
        $project = Project::where('url_address', '=', $url_address)->firstOrFail();

        // Validate input
        $request->validate([
            'images' => 'required|array',
            'images.*' => 'string', // Expecting an array of base64 strings
        ]);

        foreach ($request->input('images') as $image) {
            // Decode the base64 string
            $image = str_replace('data:image/jpeg;base64,', '', $image);
            $image = str_replace(' ', '+', $image);
            $imageName = 'project_image_' . time() . '_' . uniqid() . '.jpeg'; // Unique names for each image
            $imagePath = 'public/project_images/' . $imageName; // Use a relative path for the 'public' disk

            // Save the image to storage
            Storage::put($imagePath, base64_decode($image));

            // Store the image path in the database
            $project->archives()->create([
                'image_path' => str_replace('public/', 'storage/', $imagePath), // Save relative path
            ]);
        }

        return redirect()->route('project.show', $project->url_address)
            ->with('success', 'تم ارشفة الصور بنجاح');
    }


    public function archiveshow(string $url_address)
    {
        // Retrieve the project with its archives
        $project = Project::where('url_address', '=', $url_address)->with('archives')->first();

        if ($project) {
            // Pass the project and its archives to the view
            $archives = $project->archives->map(function ($archive) {
                return $archive->image_path;
            });

            return view('project.archiveshow', compact('project', 'archives'));
        } else {
            // Handle access denied
            $ip = $this->getIPAddress();
            return view('project.accessdenied', ['ip' => $ip]);
        }
    }
}
