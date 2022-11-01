<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Socio;


    /**
    * @OA\Get(
    *     tags={"Socios"},
    *     path="/api/socios",
    *     summary="Mostrar los socios",
    *     @OA\Response(
    *         response=200,
    *         description="Mostrar todos los socios."
    *     ),
    *     @OA\Response(
    *         response="default",
    *         description="Ha ocurrido un error."
    *     )
    * )
    */

class SocioController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $socios = Socio::all();
        return $socios;
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
     * Crear socio
     * @OA\Post (
     *     path="/api/socios",
     *     tags={"Socios"},
     *     @OA\RequestBody(
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                 @OA\Property(
     *                      type="object",
     *                      @OA\Property(
     *                          property="name",
     *                          type="string"
     *                      ),
     *                      @OA\Property(
     *                          property="email",
     *                          type="string"
     *                      )
     *                 ),
     *                 example={
     *                     "name":"example",
     *                     "email":"example@gmail.com"
     *                }
     *             )
     *         )
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="success",
     *          @OA\JsonContent(
     *              @OA\Property(property="id", type="number", example=1),
     *              @OA\Property(property="name", type="string", example="nombre"),
     *              @OA\Property(property="email", type="string", example="nombre@gmail.com")
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
        $socio = new Socio();
        $socio->name=$request->name;
        $socio->email=$request->email;

        //comprobar si existe ya
        $socios = Socio::all();
        $existe = false;

        for($i = 0; $i<sizeof($socios) && !$existe; $i++){
            if( strcmp($socios[$i]->email,$socio->email)==0){
                $existe = true;
            }
        }

        if(!$existe){
            if( strcmp("example",$socio->name)!=0){
                if( strcmp("example@gmail.com",$socio->email)!=0){
                    $socio->save();
                }else{
                    $socio = "Email no válido";
                }
            }else{
            $socio="Nombre no válido";
            }
        }else{
            $socio = "Ese socio ya existe ya existe";
        }
        
        return $socio;
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
     * Update Socios
     * @OA\Put (
     *     path="/api/socios/{id}",
     *     tags={"Socios"},
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
     *                          property="name",
     *                          type="string"
     *                      ),
     *                      @OA\Property(
     *                          property="email",
     *                          type="string"
     *                      )
     *                 ),
     *                 example={
     *                     "name":"nuevonombre",
     *                     "email":"nuevocorreo@gmail.com"
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
     *              @OA\Property(property="email", type="string", example="content")
     *          )
     *      )
     * )
     */ 
    public function update(Request $request, $id)
    {
        $socio = Socio::findOrFail($request->id);
        $socio->name=$request->name;
        $socio->email=$request->email;
        
        //comprobar si existe ya
        $socios = Socio::all();
        $existe = false;

        for($i = 0; $i<sizeof($socios) && !$existe; $i++){
            if( strcmp($socios[$i]->email,$socio->email)==0){
                $existe = true;
            }
        }

        if(!$existe){
            if( strcmp("nuevonombre",$socio->name)!=0){
                if( strcmp("nuevocorreo@gmail.com",$socio->email)!=0){
                    $socio->save();
                }else{
                    $socio = "Email no válido";
                }
            }else{
            $socio="Nombre no válido";
            }
        }else{
            $socio = "Ese socio ya existe ya existe";
        }
        
        return $socio;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    /**
     * Delete Socios
     * @OA\Delete (
     *     path="/api/socios/{id}",
     *     tags={"Socios"},
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
    public function destroy(Request $request, $id)
    {
        $socio = Socio::destroy($request->id);

        return $socio;
    }
}
