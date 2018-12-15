<?php

namespace App\Http\Controllers\v1;

use App\Services\v1\FlightService;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class FlightController extends Controller
{
    private $flights;

    public function __construct(FlightService $service)
    {
        $this->flights = $service;

        $this->middleware('auth:api',['only'=>['store','update','destroy']]);
        // add the user token to the api Header
        // Authorization: Bearer FJ98bMWcsfUpXwA4oozsfaToNmJuBoYl3cBNZzdk5dvkuXl56RZZVyxyFNKW
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $parameters = request()->input();
        $data = $this->flights->getFlights($parameters);
        // http://127.0.0.1:8000/api/v1/flights?include=arrival,departure
        return response()->json($data);
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->flights->validate($request->all());
        try {
            $flight = $this->flights->createFlight($request);
            return response()->json($flight, 201);
        } catch (Exception $e){
            return response()->json(['message'=>$e->getMessage()],500);
        }
        // Postman: POST
        // Content-Type: application/json
        // Accept : application/json
        // {
        //	"flightNumber":"JWM12345",
        //	"status":"ontime",
        //	"arrival":
        //	{
        //		"datetime":"2018-05-10 22:15:08",
        //		"iataCode":"iuo"
        //	},
        //	"departure":
        //	{
        //		"datetime":"2018-05-10 20:15:08",
        //		"iataCode":"3Bc"
        //	}
        //}
    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //http://127.0.0.1:8000/api/v1/flights/gz321024?include=departure,arrival
        $parameters = request()->input();
        $parameters['flightNumber'] = $id;
        $data = $this->flights->getFlights($parameters);
        return response()->json($data);
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $this->flights->validate($request->all());
        try {
            $flight = $this->flights->updateFlight($request,$id);
            return response()->json($flight, 200);
        }
        catch (ModelNotFoundException $ex){
            throw $ex;
        }
        catch (Exception $e){
            return response()->json(['message'=>$e->getMessage()],500);
        }
        // Postman: PUT
        // Content-Type: application/json
        // Accept : application/json
        // http://127.0.0.1:8000/api/v1/flights/JWM12345
        //{
        //	"flightNumber":"JWM12345",
        //	"status":"delayed",
        //	"arrival":
        //	{
        //		"datetime":"2018-05-10 22:15:08",
        //		"iataCode":"iuo"
        //	},
        //	"departure":
        //	{
        //		"datetime":"2018-05-10 20:15:08",
        //		"iataCode":"3Bc"
        //	}
        //}
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try {
            $flight = $this->flights->deleteFlight($id);
            return response()->make('',204);
        }
        catch (ModelNotFoundException $ex){
            throw $ex;
        }
        catch (Exception $e){
            return response()->json(['message'=>$e->getMessage()],500);
        }
    }
}
