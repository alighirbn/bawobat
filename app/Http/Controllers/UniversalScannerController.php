<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class UniversalScannerController extends Controller
{
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
    public function scanCreate(string $model, int $id)
    {
        try {
            $modelClass = $this->resolveModelClass($model);
            $record = resolve($modelClass)->findOrFail($id);

            $devices = app('App\Services\WiaScanner')->listDevices();

            return view('attachment.scan.create', compact('devices', 'record', 'model'));
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
    public function archiveCreate(string $model, int $id)
    {
        try {
            $modelClass = $this->resolveModelClass($model);
            $record = resolve($modelClass)->findOrFail($id);

            return view('attachment.archive.create', compact('record', 'model'));
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

            return redirect()->route('archive.show', ['model' => $model, 'id' => $id])
                ->with('success', 'Archives saved successfully.');
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 400);
        }
    }

    /**
     * Show archives for a model and ID.
     */
    public function archiveShow(string $model, int $id)
    {
        try {
            $modelClass = $this->resolveModelClass($model);
            $record = resolve($modelClass)->with('archives')->findOrFail($id);

            $archives = $record->archives->map(function ($archive) {
                return $archive->image_path;
            });

            return view('attachment.archive.show', compact('record', 'model', 'archives'));
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 400);
        }
    }
}
