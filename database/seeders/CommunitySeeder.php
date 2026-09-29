<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Community;

class CommunitySeeder extends Seeder
{
    public function run()
    {
        $communities = [
            [ 'name' => "Adewole Youth Development Association", 'lga' => "Ilorin East" ],
            [ 'name' => "Adio Community Youth Development Association", 'lga' => "Ilorin West" ],
            [ 'name' => "Agaka Development Association", 'lga' => "Ilorin South" ],
            [ 'name' => "Agbaji Youth Development Association", 'lga' => "Ilorin East" ],
            [ 'name' => "Agege Abattoir Youth Development Association", 'lga' => "Ilorin West" ],
            [ 'name' => "Ajasa Youth Development Association", 'lga' => "Ilorin South" ],
            [ 'name' => "Ajikobi Youth Development Association", 'lga' => "Ilorin West" ],
            [ 'name' => "Akalambi Youth Development Association", 'lga' => "Ilorin East" ],
            [ 'name' => "Alalubosa Youth Development Association", 'lga' => "Ilorin West" ],
            [ 'name' => "Alanamu Youth Development Association", 'lga' => "Ilorin South" ],
            [ 'name' => "Alapa Youth Development Association", 'lga' => "Ilorin East" ],
            [ 'name' => "Alimayaki Development Progressive Forum", 'lga' => "Moro" ],
            [ 'name' => "Amule-Olomooba Youth Development Association", 'lga' => "Ilorin West" ],
            [ 'name' => "An-Nur Isale-Maliki Youth Development Association", 'lga' => "Ilorin West" ],
            [ 'name' => "Apata Aiyegun Youth Development Association", 'lga' => "Ilorin East" ],
            [ 'name' => "Asalapa Youth Development Association", 'lga' => "Asa" ],
            [ 'name' => "ASHI", 'lga' => "Ilorin South" ],
            [ 'name' => "Awoli Progressive Forum", 'lga' => "Ilorin West" ],
            [ 'name' => "Baboko Ward Youth Development Association", 'lga' => "Ilorin South" ],
            [ 'name' => "Badari Youth Development Association", 'lga' => "Ilorin East" ],
            [ 'name' => "Balogun Gambari Youth Movement", 'lga' => "Ilorin West" ],
            [ 'name' => "Banni Community Youth Development Association", 'lga' => "Ilorin East" ],
            [ 'name' => "Baruba Youth Development Association", 'lga' => "Moro" ],
            [ 'name' => "Bijouro Youth Development Association", 'lga' => "Ilorin South" ],
            [ 'name' => "Bolanta Youth Development Association", 'lga' => "Ilorin West" ],
            [ 'name' => "Budo-Egba Youth Development Association", 'lga' => "Ilorin East" ],
            [ 'name' => "Ebu-Gada Youth Development Association", 'lga' => "Ilorin South" ],
            [ 'name' => "Edun Youth Development Association", 'lga' => "Ilorin West" ],
            [ 'name' => "Efue Youth Development Association", 'lga' => "Ilorin East" ],
            [ 'name' => "Ehinkule-Oba Youth Development Association", 'lga' => "Ilorin West" ],
            [ 'name' => "Ejidongari Youth Development Association", 'lga' => "Ilorin South" ],
            [ 'name' => "Erubu-Asunnara Youth Development Association", 'lga' => "Ilorin East" ],
            [ 'name' => "Fate Youth Development Association", 'lga' => "Ilorin West" ],
            [ 'name' => "Gaa-Ajia Youth Development Association", 'lga' => "Ilorin South" ],
            [ 'name' => "Gaa-Akanbi Youth Development Association", 'lga' => "Ilorin East" ],
            [ 'name' => "Gaa-Mejiro Youth Development Association", 'lga' => "Ilorin West" ],
            [ 'name' => "Gbagba Youth Development Association", 'lga' => "Ilorin South" ],
            [ 'name' => "Gegele Youth Development Association", 'lga' => "Ilorin East" ],
            [ 'name' => "Gerewu Youth Development Association", 'lga' => "Ilorin West" ],
            [ 'name' => "Idiape/Baba-Isale Youth Development Association", 'lga' => "Ilorin South" ],
            [ 'name' => "Ikokoro Youth Development Association", 'lga' => "Ilorin East" ],
            [ 'name' => "Isale-Koto Youth Development Association", 'lga' => "Ilorin West" ],
            [ 'name' => "Isale-Oja Youth Development Association", 'lga' => "Ilorin West" ],
            [ 'name' => "Ita-Ogunbo Youth Development Association", 'lga' => "Ilorin South" ],
            [ 'name' => "Jebba Youth Development Association", 'lga' => "Moro" ],
            [ 'name' => "Koro-Gurumoh Youth Development Association", 'lga' => "Moro" ],
            [ 'name' => "Korosayodun Youth Development Association", 'lga' => "Ilorin East" ],
            [ 'name' => "Kulende Youth Development Association", 'lga' => "Ilorin West" ],
            [ 'name' => "Laduba Youth Development Association", 'lga' => "Ilorin South" ],
            [ 'name' => "Lajiki Youth Development Association", 'lga' => "Ilorin East" ],
            [ 'name' => "Lanwa Youth Development Association", 'lga' => "Ilorin West" ],
            [ 'name' => "Magaji-Ngeri Joint Youth Development Association", 'lga' => "Ilorin South" ],
            [ 'name' => "Magajin-Yabba Youth Development Association", 'lga' => "Ilorin East" ],
        ];
        foreach ($communities as $community) {
            Community::create([
                'name' => $community['name'],
                'lga' => $community['lga'],
                'status' => 'approved',
            ]);
        }
    }
}
