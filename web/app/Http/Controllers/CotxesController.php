<?php

namespace App\Http\Controllers;

use App\Models\Parking;
use Illuminate\Http\Request;

class CotxesController extends Controller
{
    private function getParkings(){
        $parkings = Parking::all();
        // create a new value that is % of occupied
        foreach ($parkings as $parking) {
            $parking->occupied_percentage = $parking->occupied * 100 / $parking->capacity ;
        }
        $parkings = $parkings->sortBy('occupied_percentage');
        return $parkings;
    }

    public function mapIndex(){
        $parkings = $this->getParkings();
        
        return view('map', compact('parkings'));
    }


    public function index(){
        $parkings = $this->getParkings();
        // order them by occupied rate min to max
        return view('parkings', compact('parkings'));
    }
 
    public function getOneParking($pkid){
        $parking = Parking::find($pkid);
        // if parking is not found, return 404        
        if (!$parking) {
            return response()->json(['success' => false, 'message' => 'Parking not found'], 404);
        }

        $parking->occupied_percentage = $parking->occupied * 100 / $parking->capacity ;

        return view('parking', compact('parking'));
    }
    // increment parking occupied if there is space
    
    public function enter(Request $request){
        $parkingId = $request->input('parking_id');
        $parking = Parking::find($parkingId);
        if(!$parking){
            return response()->json(['success' => false, 'message' => 'Parking not found'], 404);
        }
        if ($parking->occupied < $parking->capacity) {
            $parking->occupied++;
            $parking->save();
            return response()->json(['success' => true, 'message' => 'Car entered successfully']);
        } else {
            return response()->json(['success' => false, 'message' => 'Parking is full']);
        }
    }

    public function exit(Request $request){
        $parkingId = $request->input('parking_id');
        $parking = Parking::find($parkingId);
        if(!$parking){
            return response()->json(['success' => false, 'message' => 'Parking not found'], 404);
        }
        if ($parking->occupied > 0) {
            $parking->occupied--;
            $parking->save();
            return response()->json(['success' => true, 'message' => 'Car exited successfully']);
        } else {
            return response()->json(['success' => false, 'message' => 'Parking is empty']);
        }
    }

    public function createHardcodedParking()
{
    // delete all parkings
    Parking::truncate();
        // Parking 1
        $parking = new Parking();
        $parking->name = 'Free Public Parking UdL';
        $parking->address = 'Av. de I\'Estudi General, 4';
        $parking->city = 'Lleida';
        $parking->state = 'Catalunya';
        $parking->zip = '25001';
        $parking->capacity = 160;
        $parking->occupied = 149;
        $parking->lat = 41.60685567656295;
        $parking->lng = 0.6256165340329318;
        $parking->save();
    
        // Parking 2
        $parking = new Parking();
        $parking->name = 'Parking Saba Estación Tren Lleida';
        $parking->address = 'Carrer Príncep de Viana';
        $parking->city = 'Lleida';
        $parking->state = 'Catalunya';
        $parking->zip = '25007';
        $parking->capacity = 559;
        $parking->occupied = 382;
        $parking->lat = 41.62038694228236;
        $parking->lng = 0.6326513035063315;
        $parking->save();
    
        // Parking 3
        $parking = new Parking();
        $parking->name = 'Aparcamiento Presidente Josep Tarradellas';
        $parking->address = 'Avinguda President Josep Tarradellas, 87';
        $parking->city = 'Lleida';
        $parking->state = 'Catalunya';
        $parking->zip = '25001';
        $parking->capacity = 340;
        $parking->occupied = 240;
        $parking->lat = 41.617802021454615;
        $parking->lng = 0.6389702813637674;
        $parking->save();
    
        // Parking 4
        $parking = new Parking();
        $parking->name = 'Parking Janot';
        $parking->address = 'Carrer Cronista Muntaner, 25';
        $parking->city = 'Lleida';
        $parking->state = 'Catalunya';
        $parking->zip = '25001';
        $parking->capacity = 54;
        $parking->occupied = 20;
        $parking->lat = 41.61197472171145;
        $parking->lng = 0.6346299710173237;
        $parking->save();
    
        // Parking 5
        $parking = new Parking();
        $parking->name = 'Parkia Blas de Infante';
        $parking->address = 'Plaça Blas Infante';
        $parking->city = 'Lleida';
        $parking->state = 'Catalunya';
        $parking->zip = '25001';
        $parking->capacity = 306;
        $parking->occupied = 50;
        $parking->lat = 41.613065638398815;
        $parking->lng = 0.6281068391978788;
        $parking->save();
    
        // Parking 6
        $parking = new Parking();
        $parking->name = 'Parking Auditori - Promoparc';
        $parking->address = 'Carrer la Parra, 25';
        $parking->city = 'Lleida';
        $parking->state = 'Catalunya';
        $parking->zip = '25007';
        $parking->capacity = 190;
        $parking->occupied = 180;
        $parking->lat = 41.619931572856736;
        $parking->lng = 0.628879315334392;
        $parking->save();
    
        // Parking 7
        $parking = new Parking();
        $parking->name = 'Interparking Sant Joan';
        $parking->address = 'Plaça de Sant Joan';
        $parking->city = 'Lleida';
        $parking->state = 'Catalunya';
        $parking->zip = '25007';
        $parking->capacity = 220;
        $parking->occupied = 107;
        $parking->lat = 41.61633827776262;
        $parking->lng = 0.6277635164705396;
        $parking->save();
    
        // Parking 8
        $parking = new Parking();
        $parking->name = 'Interparking Blondel';
        $parking->address = 'Avinguda de Blondel, s/n';
        $parking->city = 'Lleida';
        $parking->state = 'Catalunya';
        $parking->zip = '25002';
        $parking->capacity = 330;
        $parking->occupied = 320;
        $parking->lat = 41.61396402652344;
        $parking->lng = 0.6248452732881563;
        $parking->save();
    
        // Parking 9
        $parking = new Parking();
        $parking->name = 'Parking Plaça España 3';
        $parking->address = 'Plaça d\'Espanya, 3';
        $parking->city = 'Lleida';
        $parking->state = 'Catalunya';
        $parking->zip = '25002';
        $parking->capacity = 260;
        $parking->occupied = 260;
        $parking->lat = 41.611397170113655;
        $parking->lng = 0.6214978766965992;
        $parking->save();
    
        // Parking 10
        $parking = new Parking();
        $parking->name = 'Parking PARKIA - Euroforum';
        $parking->address = 'Carrer Lluís Companys, 1';
        $parking->city = 'Lleida';
        $parking->state = 'Catalunya';
        $parking->zip = '25003';
        $parking->capacity = 192;
        $parking->occupied = 135;
        $parking->lat = 41.612873125029914;
        $parking->lng = 0.6184938028323811;
        $parking->save();
    
        // Parking 11
        $parking = new Parking();
        $parking->name = 'Parking OLIVER, S.L.';
        $parking->address = 'Carrer de Roca Labrador, 14';
        $parking->city = 'Lleida';
        $parking->state = 'Catalunya';
        $parking->zip = '25003';
        $parking->capacity = 17;
        $parking->occupied = 6;
        $parking->lat = 41.6146698941291;
        $parking->lng = 0.6151464062408238;
        $parking->save();
    
        // Parking 12
        $parking = new Parking();
        $parking->name = 'Parking Vilella';
        $parking->address = 'Carrer Vallcalent, 14';
        $parking->city = 'Lleida';
        $parking->state = 'Catalunya';
        $parking->zip = '25006';
        $parking->capacity = 221;
        $parking->occupied = 195;
        $parking->lat = 41.6176144421475;
        $parking->lng = 0.6188000525191047;
        $parking->save();
    
        // Parking 13
        $parking = new Parking();
        $parking->name = 'Parking La Muralla';
        $parking->address = 'Passatge Empordà, 5';
        $parking->city = 'Lleida';
        $parking->state = 'Catalunya';
        $parking->zip = '25006';
        $parking->capacity = 230;
        $parking->occupied = 206;
        $parking->lat = 41.61767860861726;
        $parking->lng = 0.6208599888831401;
        $parking->save();
    
        // Parking 14
        $parking = new Parking();
        $parking->name = 'Parking Carrer Bonaire';
        $parking->address = 'Carrer Bonaire, 24';
        $parking->city = 'Lleida';
        $parking->state = 'Catalunya';
        $parking->zip = '25004';
        $parking->capacity = 40;
        $parking->occupied = 40;
        $parking->lat = 41.61936877006903;
        $parking->lng = 0.6221181658037825;
        $parking->save();
    
        // Parking 15
        $parking = new Parking();
        $parking->name = 'Parking Ricard Viñes';
        $parking->address = 'Pl. Ricard Vinyes, 25006';
        $parking->city = 'Lleida';
        $parking->state = 'Catalunya';
        $parking->zip = '25006';
        $parking->capacity = 315;
        $parking->occupied = 211;
        $parking->lat = 41.61896409944809;
        $parking->lng = 0.6196642255728779;
        $parking->save();
    
        // Parking 16
        $parking = new Parking();
        $parking->name = 'Parquing Zona Alta';
        $parking->address = 'Carrer Magí Morera, 31';
        $parking->city = 'Lleida';
        $parking->state = 'Catalunya';
        $parking->zip = '25006';
        $parking->capacity = 260;
        $parking->occupied = 245;
        $parking->lat = 41.619233880144215;
        $parking->lng = 0.6167411497095945;
        $parking->save();
    
        // Parking 17
        $parking = new Parking();
        $parking->name = 'Aparcament gratuït Camp d\'Esports';
        $parking->address = 'Av. del Doctor Fleming';
        $parking->city = 'Lleida';
        $parking->state = 'Catalunya';
        $parking->zip = '25006';
        $parking->capacity = 640;
        $parking->occupied = 640;
        $parking->lat = 41.62212046294977;
        $parking->lng = 0.6126993657998692;
        $parking->save();
    
        // Parking 18
        $parking = new Parking();
        $parking->name = 'Parking Hospital Santa Maria Lleida';
        $parking->address = 'Carrer Roda d\'Isabena, 17-19';
        $parking->city = 'Lleida';
        $parking->state = 'Catalunya';
        $parking->zip = '25199';
        $parking->capacity = 110;
        $parking->occupied = 23;
        $parking->lat = 41.62557340161448;
        $parking->lng = 0.6171020232729628;
        $parking->save();
    
        // Parking 19
        $parking = new Parking();
        $parking->name = 'Continental Parking S.L.';
        $parking->address = 'Carrer Eugeni d\'Ors, 0';
        $parking->city = 'Lleida';
        $parking->state = 'Catalunya';
        $parking->zip = '25196';
        $parking->capacity = 937;
        $parking->occupied = 788;
        $parking->lat = 41.62635568233836;
        $parking->lng = 0.6137458991336373;
        $parking->save();
    
        // Parking 21
        $parking = new Parking();
        $parking->name = 'Parking Carrer Governador Montcada, 13';
        $parking->address = 'Carrer Governador Montcada, 13';
        $parking->city = 'Lleida';
        $parking->state = 'Catalunya';
        $parking->zip = '25002';
        $parking->capacity = 143;
        $parking->occupied = 142;
        $parking->lat = 41.613230475795646;
        $parking->lng = 0.6190514772232365;
        $parking->save();
    }
    

}
