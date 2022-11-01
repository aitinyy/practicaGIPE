<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pista;
use App\Models\Deporte;
use App\Models\Reserva;
use App\Models\Socio;

    /**
    * @OA\Get(
    *     tags={"Pistas"},
    *     path="/api/pistas",
    *     summary="Mostrar pistas existentes",
    *     @OA\Response(
    *         response=200,
    *         description="Mostrar todas las pistas."
    *     ),
    *     @OA\Response(
    *         response="default",
    *         description="Ha ocurrido un error."
    *     )
    * )
    */

class PistaController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $pistas = Pista::all();
        return $pistas;
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
     * Crear Pista
     * @OA\Post (
     *     path="/api/pistas",
     *     tags={"Pistas"},
     *     @OA\RequestBody(
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                 @OA\Property(
     *                      type="object",
     *                      @OA\Property(
     *                          property="deporte",
     *                          type="number"
     *                      )
     *                 ),
     *                 example={
     *                     "deporte":"2"
     *                }
     *             )
     *         )
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="success",
     *          @OA\JsonContent(
     *              @OA\Property(property="id", type="number", example=1),
     *              @OA\Property(property="deporte", type="number", example="title"),
     *          )
     *      ),
     *      @OA\Response(
     *          response=201,
     *          description="success",
     *          @OA\JsonContent(
     *              @OA\Property(property="id", type="number", example=1),
     *              @OA\Property(property="deporte", type="number", example="title"),
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
        $pista = new Pista();
        $pista->deporte=$request->deporte;
        $existe = false;
        $deportes = Deporte::all();

        for($i=0; $i<sizeof($deportes) && !$existe; $i++){
            if($deportes[$i]->id == $pista->deporte){
                $existe = true; 
            }
        }

        if($existe){
            $pista->save();
        }else{
            $pista = "Ese deporte no existe";
        }
        
        

        return $pista;
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
    * @OA\Get(
    *     tags={"Búsqueda pistas"},
    *     path="/api/busqueda/{fecha}&{socio}&{deporte}",
    *     summary="Mostrar las pistas",
    *      @OA\Parameter(
    *          name="fecha",
    *          in="path",
    *          description="Fecha de la reserva",
    *          required=true,
    *      ),
    *      @OA\Parameter(
    *          name="socio",
    *          in="path",
    *          description="Email del socio que quiere reservar",
    *          required=true,
    *      ),
    *      @OA\Parameter(
    *          name="deporte",
    *          in="path",
    *          description="Nombre del deporte",
    *          required=true,
    *      ),
    *     @OA\Response(
    *         response=200,
    *         description="Mostrar todas las reservas.",
    *          @OA\MediaType(
    *              mediaType="application/json",
    *          )
    *     ),
    *     @OA\Response(
    *         response="default",
    *         description="Ha ocurrido un error."
    *     )
    * )
    */

    public function buscador(Request $request)
    {

        //buscar pistas con el deporte 
        $pistas = Pista::all();
        $deportes = Deporte::all();
        $max = sizeof($deportes);
        $idDeporte = -1;
        for ($i = 0; $i < $max && $idDeporte==-1; $i++){
            if(strcasecmp($deportes[$i]->nombre, $request->deporte)==0){
                $idDeporte = $deportes[$i]->id;
            }
        }

        if($idDeporte!=-1){
            $max = sizeof($pistas);
            for ($j = 0; $j < $max; $j++){
                if($pistas[$j]->deporte != $idDeporte){
                    unset($pistas[$j]);
                    //$max = sizeof($pistas);
                }
            }

            //comprobar reservas del socio
            $reservas = Reserva::all();
            $socios = Socio::all();
            $idSocio=-1;
            $max = sizeof($socios);

            for ($k = 0; $k < $max && $idSocio==-1; $k++){
                if(strcasecmp($socios[$k]->email, $request->socio)==0){ //existe el socio
                    $idSocio = $socios[$k]->id;
                }
            }

            if($idSocio!=-1){

                //contar reservas
                $contReservas = 0;
                $max = sizeof($reservas);
                for ($l = 0; $l < $max; $l++){
                    if($reservas[$l]->socio == $idSocio){ 
                        $contReservas++;
                    }
                }

                $maxNP = sizeof($pistas);
                //echo 'Valor de pistas '.$maxNP.'   '.'\n';
                if($contReservas<3){
                    //quitar aquellas pistas q estan seleccionadas a esa hora
                    for ($m = 0; $m < $max; $m++){
                        if(strcasecmp($reservas[$m]->fecha,$request->fecha) == 0){
                            $idP = $reservas[$m]->pista;
                            for($n = 0; $n<$maxNP; $n++){
                                //echo 'Valor '.$n.' de '.$maxNP.'   '.'\n';
                                if(isset($pistas[$n])){
                                    if($pistas[$n]->id==$idP){
                                        unset($pistas[$n]);
                                        //$maxNP = sizeof($pistas);
                                        //echo 'Pistas '.$maxNP.'  t';
                                    }    
                                }
                            }
                        }
                    }


                    for($n = 0; $n<$max; $n++){
                        if(strcasecmp($reservas[$n]->fecha,$request->fecha) == 0){
                            if($reservas[$n]->socio == $idSocio){
                                $pistas = "No puede reservar este socio a esta hora";
                            }
                        }
                    }

                    //Comprobar fecha valida
                    $anyo = substr($request->fecha,0,4); $anyo = intval($anyo);
                    $mes = substr($request->fecha,5,2); $mes = intval($mes);
                    $dia = substr($request->fecha,8,2); $dia = intval($dia);
                    $hora = substr($request->fecha,11,2); $hora = intval($hora);
                    $min = substr($request->fecha,14,2); $min = intval($min);
                    $sec = substr($request->fecha,17,2); $sec = intval($sec);
                    $fechaVal = false;

                    if($anyo >= 2022 && ($mes >= 1 && $mes<=12)){
                        if(($mes == 1 || $mes == 3 || $mes == 5 || $mes == 7 || $mes == 8 || $mes == 10 || $mes == 12) && $dia>=1 && $dia<=31){
                            $fechaVal = true;
                        }else if(($mes == 4 || $mes == 6 || $mes == 9 || $mes == 11) && $dia>=1 && $dia<=30){
                            $fechaVal = true;
                        }else if($mes==2 && $dia>=1 && $dia<=28){
                            $fechaVal = true;
                        }
                        //comprobar hora
                        $horaVal = false;
                        if($fechaVal){
                            if($hora>=8 && $hora<22 && $min==0 && $sec==0){
                                $horaVal = true;
                            }else{
                                $pistas = "Hora no válida";
                            }
                        }else{
                            $pistas = "Fecha no válida";
                        }
                    }else{
                        $pistas = "Fecha no válida";
                    }

                }

            }else{
                $pistas = "Socio no válido";
            }

        }else{
            $pistas = "Deporte no válido";
        }


        

        return $pistas;
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
     * Update Pistas
     * @OA\Put (
     *     path="/api/pistas/{id}",
     *     tags={"Pistas"},
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
     *                          property="deporte",
     *                          type="number"
     *                      )
     *                 ),
     *                 example={
     *                     "deporte":"3"
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
     *              @OA\Property(property="content", type="string", example="content")
     *          ),
     *      ),
     *      @OA\Response(
     *          response=201,
     *          description="success",
     *          @OA\JsonContent(
     *              @OA\Property(property="id", type="number", example=1),
     *              @OA\Property(property="deporte", type="number", example="title"),
     *          )
     *      )
     * )
     */
    public function update(Request $request, $id)
    {
        $pista = Pista::findOrFail($request->id);
        $pista->deporte=$request->deporte;
        $existe = false;
        $deportes = Deporte::all();

        for($i=0; $i<sizeof($deportes) && !$existe; $i++){
            if($deportes[$i]->id == $pista->deporte){
                $existe = true; 
            }
        }

        if($existe){
            $pista->save();
        }else{
            $pista = "Ese deporte no existe";
        }
        
        

        return $pista;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */

    
    /**
     * Delete Pistas
     * @OA\Delete (
     *     path="/api/pistas/{id}",
     *     tags={"Pistas"},
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
        $pista = Pista::destroy($request->id);

        return $pista;
    }
}
