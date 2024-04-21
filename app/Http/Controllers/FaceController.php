<?php

namespace App\Http\Controllers;

use App\Http\Requests\FaceDetectRequest;
use App\Http\Requests\FaceRegisterRequest;
use App\Http\Requests\FaceUpdateRequest;
use App\Models\Employee;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Storage;
use Aws\Rekognition\RekognitionClient;

class FaceController extends Controller
{
    protected $client;

    public function __construct()
    {
        $this->client = new RekognitionClient([
            'region'    => env('AWS_DEFAULT_REGION'),
            'version'   => 'latest'
        ]);
    }

    public function register(FaceRegisterRequest $request): JsonResponse
    {
        try {
            // Store the image
            $imagePath = $request->file('image')->store('images', 'local');

            //Saving Employee
            $employee = Employee::create([
                'name' => $request->name,
                'email' => $request->email,
                'source_emp_id' => $request->empId,
                'image' => $imagePath
            ]);
        } catch (\Throwable $th) {
            throw $th;
        }

        return response()->json([
            'message' => 'Registration successful',
            'emplpoyee' => $employee
        ]);
    }

    public function update(FaceUpdateRequest $request): JsonResponse
    {
        try {

            //Get Employee
            $employee = Employee::where('email', $request->email)->first();

            // Delete the file
            if (Storage::disk('local')->exists($employee->image)) {
                Storage::disk('local')->delete($employee->image);
            }

            // Store the image
            $imagePath = $request->file('image')->store('images', 'local');

            //Update Employee
            $employee->image = $imagePath;
            $employee->name = $request->name;
            $employee->save();
        } catch (\Throwable $th) {
            throw $th;
        }

        return response()->json([
            'message' => 'Updated successful',
            'emplpoyee' => $employee
        ]);
    }

    public function compare(FaceDetectRequest $request): JsonResponse
    {
        try {
            //Get Employee
            $employee = Employee::where('email', $request->email)->first();
            
            $image = fopen($request->file('image')->getPathName(), 'r');
            $sourceBytes = fread($image, $request->file('image')->getSize());
            
            $targetBytes = Storage::disk('local')->get($employee->image);
            // Prepare parameters for compareFaces function
            $params = [
                'SourceImage' => [
                    'Bytes' => $sourceBytes,
                ],
                'TargetImage' => [
                    'Bytes' => $targetBytes,
                ],
                'SimilarityThreshold' => 99, // Adjust similarity threshold as needed
            ];

            // Call compareFaces function
            $result = $this->client->compareFaces($params);

            // Handle the result
            if (!empty($result['FaceMatches'])) {
                $similarity = $result['FaceMatches'][0]['Similarity'];
                return response()->json([
                    'message' => 'Match successful',
                    'similarity' => $similarity
                ]);
            } else {
                return response()->json([
                    'message' => 'No Match found'
                ]);
            }

        } catch (\Throwable $th) {
            throw $th;
        }

        
    }
}
