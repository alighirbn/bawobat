<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;


use Illuminate\Database\Seeder;


class DatabaseSeeder extends Seeder
{
  /**
   * Seed the application's database.
   */
  public function run(): void
  {
    // Facility referance tables

    $this->call([
      // General seeders
      Permission_Seeder::class,
      Department_Seeder::class,
      Account_Seeder::class,
      Cost_Center_Seeder::class,
      Period_Seeder::class,
    ]);
  }
}
