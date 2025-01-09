<?php

namespace App\Http\Controllers;

use App\Services\WiaScanner;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class UniversalScannerController extends Controller
{
    private $scanner;

    public function __construct(WiaScanner $scanner)
    {
        $this->scanner = $scanner;
    }

    /**
     * Map of model names to their fully qualified namespaces.
     */
    protected $modelMap = [
        'Project' => \App\Models\Project\Project::class,
        'Transaction' => \App\Models\Account\Transaction::class,
        'Investor' => \App\Models\Investor\Investor::class,
        'ProjectStage' => \App\Models\Project\ProjectStage::class,
        'Income' => \App\Models\Income\Income::class,
        'Expense' => \App\Models\Expense\Expense::class,
        'OpeningBalance' => \App\Models\Account\OpeningBalance::class,
        'Period' => \App\Models\Account\Period::class,
        // Add other models as needed
    ];

    /**
     * Resolve the model class from the map.
     *
     * @param string $model
     * @return string
     * @throws \Exception
     */
    protected function resolveModelClass(string $model)
    {
        if (!isset($this->modelMap[$model])) {
            throw new \Exception('Invalid model specified.');
        }

        return $this->modelMap[$model];
    }

    /**
     * Display the list of available scanner devices for a model and ID.
     */
    public function scanCreate(string $model, int $id, string $url_address)
    {
        try {
            $modelClass = $this->resolveModelClass($model);
            $record = resolve($modelClass)->findOrFail($id);

            $devices = $this->scanner->listDevices();

            return view('attachment.scan.create', compact('devices', 'record', 'model', 'url_address'));
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 400);
        }
    }

    /**
     * Store scanned documents for a model and ID.
     */
    public function scanStore(Request $request, string $model, int $id)
    {
        try {
            $modelClass = $this->resolveModelClass($model);
            $record = resolve($modelClass)->findOrFail($id);

            $deviceId = $request->input('device_id');
            if (empty($deviceId)) {
                return response()->json(['error' => 'Device ID is required.'], 400);
            }

            $scanner = app('App\Services\WiaScanner');
            $scanner->connect($deviceId);

            $scansDirectory = storage_path('app/public/scans/');
            if (!is_dir($scansDirectory)) {
                mkdir($scansDirectory, 0755, true);
            }

            $filename = uniqid() . '.png';
            $outputPath = $scansDirectory . $filename;

            $scanner->scan($outputPath);

            $record->archives()->create([
                'image_path' => 'storage/scans/' . $filename,
            ]);

            return response()->json([
                'message' => 'Scan successful.',
                'image_path' => asset('storage/scans/' . $filename),
            ], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 400);
        }
    }

    /**
     * Display the form to upload archives for a model and ID.
     */
    public function archiveCreate(string $model, int $id, string $url_address)
    {
        try {
            $modelClass = $this->resolveModelClass($model);
            $record = resolve($modelClass)->findOrFail($id);

            return view('attachment.archive.create', compact('record', 'model', 'url_address'));
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 400);
        }
    }

    /**
     * Store archives for a model and ID.
     */
    public function archiveStore(Request $request, string $model, int $id)
    {
        try {
            $modelClass = $this->resolveModelClass($model);
            $record = resolve($modelClass)->findOrFail($id);

            $request->validate([
                'images' => 'required|array',
                'images.*' => 'string', // Expecting an array of base64 strings
            ]);

            foreach ($request->input('images') as $image) {
                $image = str_replace('data:image/jpeg;base64,', '', $image);
                $image = str_replace(' ', '+', $image);

                $imageName = 'archive_' . uniqid() . '.jpeg';
                $imagePath = 'public/archives/' . $imageName;

                Storage::put($imagePath, base64_decode($image));

                $record->archives()->create([
                    'image_path' => str_replace('public/', 'storage/', $imagePath),
                ]);
            }

            return redirect()->route('archive.show', ['model' => $model, 'id' => $id, 'url_address' => $record->url_address])
                ->with('success', 'Archives saved successfully.');
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 400);
        }
    }

    /**
     * Show archives for a model and ID.
     */
    public function archiveShow(string $model, int $id, string $url_address)
    {
        try {
            $modelClass = $this->resolveModelClass($model);
            $record = resolve($modelClass)->with('archives')->findOrFail($id);

            // Pass the full archives collection instead of just the image paths
            $archives = $record->archives;

            return view('attachment.archive.show', compact('record', 'model', 'archives', 'url_address'));
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 400);
        }
    }


    /**
     * Delete an archive for a model and ID.
     */
    public function archiveDelete(int $archiveId)
    {
        try {
            // Find the archive record
            $archive = \App\Models\Archive::findOrFail($archiveId);

            // Get necessary information for redirecting back to the archiveShow view
            $model = $archive->model_type; // Assuming you have a polymorphic model or column to identify the model
            $id = $archive->model_id;      // ID of the related model
            $urlAddress = $archive->url_address; // Assuming this is stored in your archive

            // Delete the image file from storage
            $imagePath = str_replace('storage/', 'public/', $archive->image_path);
            Storage::delete($imagePath);

            // Delete the archive record from the database
            $archive->delete();

            // Redirect back to the archiveShow view with a success message
            return redirect()
                ->route('archive.show', ['model' => $model, 'id' => $id, 'url_address' => $urlAddress])
                ->with('success', 'تم الحذف بنجاح');
        } catch (\Exception $e) {
            return redirect()
                ->back()
                ->with('error', 'حدث خطأ أثناء الحذف: ' . $e->getMessage());
        }
    }
}
