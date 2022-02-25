<?php

namespace Database\Seeders;

use Faker\Generator as Faker;
use Illuminate\Database\Seeder;
use Illuminate\Http\Request;

class DatabaseSeeder extends Seeder
{
    protected Faker $faker;

    public function run()
    {
        $this->faker = app('Faker\Generator', ['locale' => 'id_ID']);

        if (app()->environment(['local', 'staging'])) $this->fakerGenerate();
    }

    protected function fakerGenerate()
    {

        $numTenant = rand(111,999);
        $addTenant = rand(3,5);
        for ($i=$numTenant; $i < ($numTenant + $addTenant); $i++) 
        {
            $user = $this->fakerUser("company-$i");
            
            $tenant = $this->fakerTenant("$i", $user);

            $this->fakerTenantAccess("user-$i", $tenant);

            $numSubtenant = rand(10,99);
            $addSubtenant = rand(5,10);
            for ($j=$numSubtenant; $j < ($numSubtenant + $addSubtenant); $j++) 
            { 
                $subtenant = $this->fakerSubtenant("$i-$j", $tenant);

                $this->fakerSubtenantAccess("user-$i$j", $subtenant);
                
                $numMember = rand(50,69);
                $addMember = rand(10,15);
                for ($x=$numMember; $x < ($numMember + $addMember); $x++) 
                {
                    $member = $this->fakerMember("$i$j$x", $subtenant);

                    $numPerson = rand(70,99);
                    $addPerson = rand(1,6);
                    for ($y=$numPerson; $y < ($numPerson + $addPerson); $y++) 
                    {
                        $this->fakerPerson("$i$j$x$y", $member);
                    } 
                } 
            }

        }

    }

    protected function fakerSubtenantAccess($name, $subtenant) 
    {
        $num = rand(111,999);
        $add = rand(0,3);
        for ($i=$num; $i < ($num + $add); $i++) { 
            if ($user = $this->fakerUser("$name-$i"))
            {
                $subtenant->accessables()->firstOrCreate([
                    'user_id' => $user['id']
                ]);
            }
        }

    }

    protected function fakerTenantAccess($name, $tenant) 
    {
        $num = rand(111,999);
        $add = rand(0,3);
        for ($i=$num; $i < ($num + $add); $i++) 
        { 
            if ($user = $this->fakerUser("$name-$i"))
            {
                $tenant->accessables()->firstOrCreate([
                    'user_id' => $user['id']
                ]);
            }
        }

    }

    protected function fakerPerson(string $key, $member)
    {
        $gender = $this->faker->randomElement(['MALE', 'FEMALE']);
        $birthDate = app(\Carbon\Carbon::class)
            ->addYears(rand(-60, -1))
            ->addDays(rand(50, 200))
            ->format('Y-m-d');

        $request = new Request([
            'number' => $this->faker->numerify("$key####"),
            'name' => $this->faker->name($gender),
            'gender' => $gender,
            'birth_place' => $this->faker->city,
            'birth_date' => $birthDate,
            'address' => rand(1,5) <= 1 ? $this->faker->address : null,
        ]);

        return app(\App\Http\Controllers\PersonController::class)->store($member, $request)->resource;
    }

    protected function fakerMember(string $key, $subtenant)
    {
        $request = new Request([
            'number' => $this->faker->numerify("$key######"),
            'address' => $this->faker->address,
            'subtenant_id' => $subtenant->id,
        ]);

        return app(\App\Http\Controllers\MemberController::class)->store($request)->resource;
    }

    protected function fakerSubtenant(string $key, $tenant)
    {
        $request = new Request([
            'name' => "Kiosk-$key",
            'number' => $this->faker->numerify("REG-KIOSK-$key-###-###-##-#"),
            'tenant_id' => $tenant->id,
        ]);

        return app(\App\Http\Controllers\SubtenantController::class)->store($request)->resource;
    }

    protected function fakerTenant(string $key, $user)
    {
        $request = new Request([
            'name' => "Company-$key",
            'number' => $this->faker->numerify("REG-$key-###-###-##-#"),
            'owner_id' => $user["id"],
        ]);

        return app(\App\Http\Controllers\TenantController::class)->store($request)->resource;
    }

    protected function fakerUser($name)
    {
        // return ['id' => $this->faker->unique()->uuid];

        $data = [
            'name' => $name,
            'email' => "$name@example.com",
            'phone' => $this->faker->unique()->phoneNumber(),
            'password' => 'password',
        ];

        $response = app('http')
            ->accept('Aplication/json')
            ->post('nginx:81/api/register', $data)
            ->throw();

        if (!$response->successful()) return null;

        return $response->json()['user'];
    }
}
