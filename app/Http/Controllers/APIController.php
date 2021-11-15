<?php

namespace App\Http\Controllers;

use Illuminate\Routing\Controller as BaseController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Services\FilterQueryBuilder;
use App\User;
use App\Patient;
use App\PatientData;
use App\Session;

class APIController extends BaseController
{
    public function getUsers(FilterQueryBuilder $filters) {
        // Define allowed filters
        $allowedFilters = ['name', 'clinic.name', 'created_at'];

        // Return query with provided filters and pagination
        return $filters->buildQuery(User::query(), $allowedFilters)->paginate(15);
    }

    public function deleteUser(Request $request) {
        $user = User::find($request->post('id'));

        if($user) {
            $user->delete();
        }
    
        return collect([
            'success' => $user ? true : false
        ]);
    }

    public function changePassword(Request $request) {
        if($request->post('email')) {
            $user = User::where('email', $request->post('email'))->first();
            if($user) {
                try {
                    $user->sendFpMail();
                    return collect([
                        'success' => true
                    ]);
                } catch(\Exception $e) {
                    return collect([
                        'success' => false,
                        'error' => $e->getMessage()
                    ]);
                }
            }
        }
    }

    public function getPatientsSessions(FilterQueryBuilder $filters, $patientId) {
        // Define allowed filters
        $allowedFilters = ['created_at', 'area.name', 'machine.name'];

        // Return query with provided filters and pagination
        return $filters->buildQuery(Session::where('patient_id', (int)$patientId), $allowedFilters)->paginate(15);
    }

    public function getPatientsGraph(Request $request, $patientId) {
        // Define allowed intervals & their format
        $allowedIntervals = [
            'day' => '%Y-%m-%d',
            'month' => '%Y-%m',
            'year' => '%Y'
        ];

        // Set default interval
        $selectedInterval = $allowedIntervals['day'];

        // Set interval if specificed in request and if is allowed
        if($request->post('interval')) {
            if(isset($allowedIntervals[$request->post('interval')])) {
                $selectedInterval = $allowedIntervals[$request->post('interval')];
            }
        }

        // Run query
        $res = Session::where('patient_id', (int)$patientId)
        ->groupBy(\DB::raw("DATE_FORMAT(created_at, '".$selectedInterval."')"))
        ->selectRaw("count(*) as sessions, DATE_FORMAT(created_at, '".$selectedInterval."') as date")
        ->get();

        // Reurn results
        return collect([
            'total' => $res->sum('sessions'),
            'graph' => [
                'interval' => array_search($selectedInterval, $allowedIntervals),
                'data' => $res
            ]
        ]);
    }

    // Return patient data
    public function getPatientData(Request $request, $patientId) {
        return PatientData::where('patient_id', $patientId)->get();
    }

    public function getPatientSessions(FilterQueryBuilder $filters, $patientId, $sessionId = null) {
        $allowedFilters = ['area.id', 'area.name', 'created_at'];
        $q = Session::where('patient_id', $patientId);

        // Session id is optional. If set, filter to a specific sessionId
        if($sessionId) {
           $q->where('id', $sessionId);
        }

        // Build query and get pagination results
        $res = $filters->buildQuery($q, $allowedFilters)->with('area', 'machine', 'clinic')->paginate(15);

        // Clean uneeded data
        $res->getCollection()->transform(function($sess) {
            unset($sess->patient_id);
            unset($sess->area_id);
            unset($sess->machine_id);
            unset($sess->clinic_id);
            return $sess;
        });

        return $res;
    }

    
    public function setPatientPicture(Request $request, Patient $patient) {
        try {
            // Upload picture and get path
            $path = $request->file('picture')->store('public/profile-pictures');
        } catch(\Exception $e) {
            // An error has occured while uploading. Return error details
            return collect([
                'success' => false,
                'error' => $e->getMessage()
            ]);
        }

        // Get uploaded picture url
        $pictureUrl = Storage::url($path);
        // Get patient data model or create a new one if doesn't exist
        $patientData = $patient->data ? $patient->data : new PatientData;
        // always set patient_id incase this is a new model
        $patientData->patient_id = $patient->id;
        // Update picture url
        $patientData->picture = $pictureUrl;
        // Save patient data
        $patientData->save();

        // Return sucess
        return collect([
            'success' => true,
            'imageUrl' => $pictureUrl
        ]);
    }

    public function updatePatientData(Request $request, Patient $patient) {
        // Define editable fields on PatientData
        $editableFields = ['name', 'passport'];
        // Get patient data model or create a new one if doesn't exist
        $patientData = $patient->data ? $patient->data : new PatientData;
        // always set patient_id incase this is a new model
        $patientData->patient_id = $patient->id;

        // Loop through editableFields and check if any post data is available foreach field
        foreach($editableFields as $field) {
            if($request->post($field)) {
                // Update field's value
                $patientData->{$field} = $request->post($field);
            }
        }

        // Save patient data
        $patientData->save();

        // Return success
        return collect([
            'success' => true
        ]);
    }
}
