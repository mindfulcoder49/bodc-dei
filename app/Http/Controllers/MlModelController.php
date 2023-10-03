<?php

namespace App\Http\Controllers;

use App\Models\ThreeOneOneCase;
use App\Models\MlModel;
use App\Models\Prediction;

use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Facades\Log;


class MlModelController extends Controller
{
    /**
     * Display a listing of the cases with associated predictions.
     *
     * @return \Inertia\Response
     */
    public function index(Request $request)
    {
        //get all models
        $models = MlModel::with(['predictions'])
            ->get();
        
        //create an array of model names and accuracy
        $modelAccuracy = [];
        foreach ($models as $model) {
            $modelAccuracy[$model->ml_model_name] = $model->accuracy;
        }

        //return an inertia app with the models and their accuracy
        return Inertia::render('ThreeOneOneModelList', [
            'modelAccuracy' => $modelAccuracy
        ]);
    }
}
