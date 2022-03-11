<?php 
use Illuminate\Database\Seeder; 
use App\Models\Role;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Faker\Generator as Faker;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(Faker $faker)
    {
        User::truncate();
        DB::table('role_user')->truncate();

        $adminRole = Role::where('name','admin')->first();
        $authorRole = Role::where('name','author')->first();
        $userRole = Role::where('name','user')->first();
        $riderRole = Role::where('name','rider')->first();
 
        $admin = User::create([
            'first_name' => 'Admin',
            'last_name' => $faker->name,
            'phone' => $faker->PhoneNumber,
            'address' => $faker->address, 
            'email' => 'admin@admin.com',
            'email_verified_at' => now(),
            'password' => Hash::make('password'),  // password         
            'remember_token' => Str::random(10),
        ]);

        $author = User::create([ 
            
            'first_name' => 'Author',
            'last_name' => $faker->name,
            'phone' => $faker->PhoneNumber,
            'address' => $faker->address, 
            'email' => 'author@author.com',
            'email_verified_at' => now(),
            'password' => Hash::make('password'),  // password         
            'remember_token' => Str::random(10),
        ]);

        $user = User::create([ 
            
            'first_name' => 'User',
            'last_name' => $faker->name,
            'phone' => $faker->PhoneNumber,
            'address' => $faker->address, 
            'email' => 'user@user.com',
            'email_verified_at' => now(),
            'password' => Hash::make('password'),  // password         
            'remember_token' => Str::random(10),
        ]); 
        
        $rider = User::create([ 
            
            'first_name' => 'Rider',
            'last_name' => $faker->name,
            'phone' => $faker->PhoneNumber,
            'address' => $faker->address, 
            'email' => 'rider@rider.com',
            'email_verified_at' => now(),
            'password' => Hash::make('password'),  // password         
            'remember_token' => Str::random(10),
        ]); 

        $admin->roles()->attach($adminRole);
        $author->roles()->attach($authorRole);
        $user->roles()->attach($userRole);
        $rider->roles()->attach($riderRole);
        //App\User::factory()->count(30)->create();
        //
    }
}
