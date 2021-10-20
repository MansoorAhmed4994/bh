<?php 
use Illuminate\Database\Seeder;
use App\User;
use App\Models\Role;
use Illuminate\Support\Facades\Hash;
use Faker\Generator as Faker;
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
        //Role::truncate();
        factory(User::class, 3)->create();

        $adminRole = Role::where('name','admin');
        $authorRole = Role::where('name','author');
        $userRole = Role::where('name','user');

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

        //$admin->roles()->attach($adminRole);
        //$author->roles()->attach($authorRole);
        //$user->roles()->attach($userRole);
        //App\User::factory()->count(30)->create();
        //
    }
}