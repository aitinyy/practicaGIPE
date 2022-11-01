<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Deporte;

    /**
    * @OA\Get(
    *     tags={"Deporte"},
    *     path="/api/deportes",
    *     summary="Mostrar deportes",
    *     @OA\Response(
    *         response=200,
    *         description="Mostrar todos los deportes."
    *     ),
    *     @OA\Response(
    *         response="default",
    *         description="Ha ocurrido un error."
    *     )
    * )
    */

    





class DeporteController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $deportes = Deporte::all();
        $limit = 10;
        return $deportes;
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */


     /**
     * Crear deporte
     * @OA\Post (
     *     path="/api/deportes",
     *     tags={"Deporte"},
     *     @OA\RequestBody(
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                 @OA\Property(
     *                      type="object",
     *                      @OA\Property(
     *                          property="nombre",
     *                          type="string"
     *                      )
     *                 ),
     *                 example={
     *                     "nombre":"example"
     *                }
     *             )
     *         )
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="success",
     *          @OA\JsonContent(
     *              @OA\Property(property="id", type="number", example=1),
     *              @OA\Property(property="name", type="string", example="title"),
     *              @OA\Property(property="updated_at", type="string", example="2021-12-11T09:25:53.000000Z"),
     *              @OA\Property(property="created_at", type="string", example="2021-12-11T09:25:53.000000Z"),
     *          )
     *      ),
     *      @OA\Response(
     *          response=400,
     *          description="invalid",
     *          @OA\JsonContent(
     *              @OA\Property(property="msg", type="string", example="fail"),
     *          )
     *      )
     * )
     */
    public function store(Request $request)
    {
        $deporte = new Deporte();
        $deporte->nombre=$request->nombre;
        $deportes = Deporte::all();
        $existe = false;

        for($i = 0; $i<sizeof($deportes) && !$existe; $i++){
            if( strcmp($deportes[$i]->nombre,$deporte->nombre)==0){
                $existe = true;
            }
        }

        if(!$existe){
            if( strcmp("example",$deporte->nombre)!=0){
                $deporte->save();
            }else{
                $deporte = "Nombre no válido";
            }
        }else{
            $deporte = "Ese nombre ya existe";
        }

        return $deporte;
        
        
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */

     /**
     * Update Deportes
     * @OA\Put (
     *     path="/api/deportes/{id}",
     *     tags={"Deporte"},
     *     @OA\Parameter(
     *         in="path",
     *         name="id",
     *         required=true,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\RequestBody(
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                 @OA\Property(
     *                      type="object",
     *                      @OA\Property(
     *                          property="nombre",
     *                          type="string"
     *                      )
     *                 ),
     *                 example={
     *                     "nombre":"nuevo nombre"
     *                }
     *             )
     *         )
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="success",
     *          @OA\JsonContent(
     *              @OA\Property(property="id", type="number", example=1),
     *              @OA\Property(property="title", type="string", example="title"),
     *              @OA\Property(property="content", type="string", example="content"),
     *              @OA\Property(property="updated_at", type="string", example="2021-12-11T09:25:53.000000Z"),
     *              @OA\Property(property="created_at", type="string", example="2021-12-11T09:25:53.000000Z")
     *          )
     *      )
     * )
     */
    public function update(Request $request)
    {
        $deporte = Deporte::findOrFail($request->id);
        $deporte->nombre=$request->nombre;
        $deportes = Deporte::all();
        $existe = false;

        for($i = 0; $i<sizeof($deportes) && !$existe; $i++){
            if( strcmp($deportes[$i]->nombre,$deporte->nombre)==0){
                $existe = true;
            }
        }

        if(!$existe){
            if( strcmp("nuevo nombre",$deporte->nombre)!=0){
                $deporte->save();
            }else{
                $deporte = "Nombre no válido";
            }
        }else{
            $deporte = "Ese nombre ya existe";
        }

        return $deporte;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */

    /**
     * Delete Todo
     * @OA\Delete (
     *     path="/api/deportes/{id}",
     *     tags={"Deporte"},
     *     @OA\Parameter(
     *         in="path",
     *         name="id",
     *         required=true,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="success",
     *         @OA\JsonContent(
     *             @OA\Property(property="msg", type="string", example="deleted succesfully")
     *         )
     *     )
     * )
     */
    public function destroy(Request $request)
    {
        $deporte = Deporte::destroy($request->id);

        return $deporte;
    }
}
