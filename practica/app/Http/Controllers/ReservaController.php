<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Reserva;
use App\Models\Deporte;
use App\Models\Pista;
use App\Models\Socio;


    /**
    * @OA\Get(
    *     tags={"Reservas"},
    *     path="/api/reservas",
    *     summary="Mostrar las reservas",
    *     @OA\Response(
    *         response=200,
    *         description="Mostrar todas las reservas."
    *     ),
    *     @OA\Response(
    *         response="default",
    *         description="Ha ocurrido un error."
    *     )
    * )
    */

class ReservaController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $reservas = Reserva::all();
        return $reservas;
    }

    /**
    * @OA\Get(
    *     tags={"Listado reservas"},
    *     path="/api/listado/{fecha}",
    *     summary="Mostrar las reservas",
    *      @OA\Parameter(
    *          name="fecha",
    *          in="path",
    *          description="Fecha de la reserva",
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

    public function lista(Request $request)
    {
        $reservas = Reserva::all();
        $max = sizeof($reservas);
        $reservasFin=[];
        $pista="";
        $deporte="";
        $nomDeporte="";

        for ($i = 0; $i < $max; $i++){
            $date = $reservas[$i]->fecha;
            $date2 = substr($date,0,10);
            if($date2!=$request->fecha){
                unset($reservas[$i]);
            }else{
                $pista = $reservas[$i]->pista;
                $deporte = Pista::findOrFail($pista)->deporte;
                $nomDeporte = Deporte::findOrFail($deporte)->nombre;
                //$decoded_json = json_decode($reservas[$i], true);
                $reservas[$i]['deporte']=$nomDeporte;
                //$reservas[$i]=$decoded_json;

            }
        } 
        
        //Comprobar fecha valida
        $anyo = substr($request->fecha,0,4); $anyo = intval($anyo);
        $mes = substr($request->fecha,5,2); $mes = intval($mes);
        $dia = substr($request->fecha,8,2); $dia = intval($dia);
        $fechaVal = false;

        if($anyo >= 2022 && ($mes >= 1 && $mes<=12)){
            if(($mes == 1 || $mes == 3 || $mes == 5 || $mes == 7 || $mes == 8 || $mes == 10 || $mes == 12) && $dia>=1 && $dia<=31){
                $fechaVal = true;
            }else if(($mes == 4 || $mes == 6 || $mes == 9 || $mes == 11) && $dia>=1 && $dia<=30){
                $fechaVal = true;
            }else if($mes==2 && $dia>=1 && $dia<=28){
                $fechaVal = true;
            }

            if(!$fechaVal){
                $reservas = "Fecha no válida";
            }
        }else{
            $reservas = "Fecha no válida";
        }

        return $reservas;
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
     * Crear reserva
     * @OA\Post (
     *     path="/api/reservas",
     *     tags={"Reservas"},
     *     @OA\RequestBody(
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                 @OA\Property(
     *                      type="object",
     *                      @OA\Property(
     *                          property="socio",
     *                          type="number"
     *                      ),
     *                      @OA\Property(
     *                          property="pista",
     *                          type="number"
     *                      ),
     *                      @OA\Property(
     *                          property="fecha",
     *                          type="string"
     *                      )
     *                 ),
     *                 example={
     *                     "socio":"1",
     *                     "pista":"1",
     *                     "fecha":"2022-11-07 10:00:00"
     *                }
     *             )
     *         )
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="success",
     *          @OA\JsonContent(
     *              @OA\Property(property="id", type="number", example=1),
     *              @OA\Property(property="socio", type="number", example=1),
     *              @OA\Property(property="pista", type="number", example=1),
     *              @OA\Property(property="fecha", type="string", example="2022-11-07 10:00:00")
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
        $reserva = new Reserva();
        $reserva->socio=$request->socio;
        $reserva->pista=$request->pista;
        $reserva->fecha=$request->fecha;

        //comprobar existencia de socio
        $socios = Socio::all();
        $existeSocio = false;

        for($i = 0; $i<sizeof($socios) && !$existeSocio; $i++){
            if($socios[$i]->id == $reserva->socio){
                $existeSocio = true;
            }
        }

        if($existeSocio){
            //comprobar existencia pista
            $pistas = Pista::all();
            $existePista = false;
            for($j = 0; $j < sizeof($pistas) && !$existePista; $j++){
                if($pistas[$j]->id == $reserva->pista){
                    $existePista = true;
                }
            }

            if($existePista){
                //comprobar fecha disponible con pista
                $reservas = Reserva::all();
                $fechaDisp = true;
                for($k = 0; $k < sizeof($reservas) && $fechaDisp; $k++){
                    if(strcmp($reservas[$k]->fecha, $reserva->fecha)==0){
                        if($reservas[$k]->pista == $reserva->pista){
                            $fechaDisp = false;
                        }
                    }
                }

                if($fechaDisp){
                    //comprobar q la fecha es valida
                    $anyo = substr($reserva->fecha,0,4); $anyo = intval($anyo);
                    $mes = substr($reserva->fecha,5,2); $mes = intval($mes);
                    $dia = substr($reserva->fecha,8,2); $dia = intval($dia);
                    $hora = substr($reserva->fecha,11,2); $hora = intval($hora);
                    $min = substr($reserva->fecha,14,2); $min = intval($min);
                    $sec = substr($reserva->fecha,17,2); $sec = intval($sec);
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
                                $reserva->save();
                            }else{
                                $reserva = "Hora no válida";
                            }
                        }else{
                            $reserva = "Fecha no válida";
                        }
                    }else{
                        $reserva = "Fecha no válida";
                    }
                }else{
                    $reserva = "Pista no disponible";
                }
            }else{
                $reserva = "Pista no válida";
            }

        }else{
            $reserva = "Socio no válido";
        }
        
        return $reserva;
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
     * Update Reservas
     * @OA\Put (
     *     path="/api/reservas/{id}",
     *     tags={"Reservas"},
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
     *                          property="socio",
     *                          type="number"
     *                      ),
     *                      @OA\Property(
     *                          property="pista",
     *                          type="number"
     *                      ),
     *                      @OA\Property(
     *                          property="fecha",
     *                          type="string"
     *                      )
     *                 ),
     *                 example={
     *                     "socio":"1",
     *                     "pista":"1",
     *                     "fecha":"2022-11-07 10:00:00"
     *                }
     *             )
     *         )
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="success",
     *          @OA\JsonContent(
     *              @OA\Property(property="id", type="number", example=1),
     *              @OA\Property(property="socio", type="number", example=1),
     *              @OA\Property(property="pista", type="number", example=1),
     *              @OA\Property(property="fecha", type="string", example="2022-11-07 10:00:00")
     *          )
     *      )
     * )
    */
    public function update(Request $request, $id)
    {
        $reserva = Reserva::findOrFail($request->id);
        $reserva->socio=$request->socio;
        $reserva->pista=$request->pista;
        $reserva->fecha=$request->fecha;
        
        //comprobar existencia de socio
        $socios = Socio::all();
        $existeSocio = false;

        for($i = 0; $i<sizeof($socios) && !$existeSocio; $i++){
            if($socios[$i]->id == $reserva->socio){
                $existeSocio = true;
            }
        }

        if($existeSocio){
            //comprobar existencia pista
            $pistas = Pista::all();
            $existePista = false;
            for($j = 0; $j < sizeof($pistas) && !$existePista; $j++){
                if($pistas[$j]->id == $reserva->pista){
                    $existePista = true;
                }
            }

            if($existePista){
                //comprobar fecha disponible con pista
                $reservas = Reserva::all();
                $fechaDisp = true;
                for($k = 0; $k < sizeof($reservas) && $fechaDisp; $k++){
                    if(strcmp($reservas[$k]->fecha, $reserva->fecha)==0){
                        if($reservas[$k]->pista == $reserva->pista){
                            $fechaDisp = false;
                        }
                    }
                }

                if($fechaDisp){
                    //comprobar q la fecha es valida
                    $anyo = substr($reserva->fecha,0,4); $anyo = intval($anyo);
                    $mes = substr($reserva->fecha,5,2); $mes = intval($mes);
                    $dia = substr($reserva->fecha,8,2); $dia = intval($dia);
                    $hora = substr($reserva->fecha,11,2); $hora = intval($hora);
                    $min = substr($reserva->fecha,14,2); $min = intval($min);
                    $sec = substr($reserva->fecha,17,2); $sec = intval($sec);
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
                                $reserva->save();
                            }else{
                                $reserva = "Hora no válida";
                            }
                        }else{
                            $reserva = "Fecha no válida";
                        }
                    }else{
                        $reserva = "Fecha no válida";
                    }
                }else{
                    $reserva = "Pista no disponible";
                }
            }else{
                $reserva = "Pista no válida";
            }

        }else{
            $reserva = "Socio no válido";
        }
        
        return $reserva;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */

    /**
     * Delete Reserva
     * @OA\Delete (
     *     path="/api/reservas/{id}",
     *     tags={"Reservas"},
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
        $reserva = Reserva::destroy($request->id);

        return $reserva;
    }
}
