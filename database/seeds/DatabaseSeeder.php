<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //alap admin feltoltese az adatbazisba
        $user = new \App\User([
            'name' => 'admin',
            'email' => 'admin',
            'password' => 'admin'
        ]);

        $user->save();

        //jsonne convertalt fajl szetbontasa
        $jsonfile = file_get_contents("resources/excelFiles/fdb.json");
        $json = json_decode($jsonfile, true);

        //adatbazis feltoltese soronkent
        foreach ($json as $row){
            logger($row['Name']);
            $row = new \App\FakeDb([
                'Name' => $row['Name'],
                'Sex' => $row['Sex'],
                'Birthday' =>  date("Y-m-d", strtotime($row['Birthday'])),
                'Phone number' => $row['Phone number'],
                'Adress' => $row['Adress'],
                'Country' => $row['Country'],
                'Email' => $row['Email'],
                'Salary' => $row['Salary'],
                'Profession' => $row['Profession'],
                'Professional Level (1-5)' => $row['Professional Level (1-5)']
            ]);
            $row->save();
            //vagy ezzel egysorban: DB::table('fake_dbs')->insert($row);
        }
    }
}
