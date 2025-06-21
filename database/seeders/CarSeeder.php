<?php

namespace Database\Seeders;

use App\Models\Car;
use Illuminate\Database\Seeder;

class CarSeeder extends Seeder
{
    public function run(): void
    {
        $cars = [
            ['name' => 'S-Class Sedan', 'brand' => 'Mercedes-Benz', 'model' => 'S 580 4MATIC', 'car_type' => 'Sedan', 'year' => 2022, 'daily_rent_price' => 35000, 'availability' => true],
            ['name' => '7 Series Executive', 'brand' => 'BMW', 'model' => '740i', 'car_type' => 'Sedan', 'year' => 2023, 'daily_rent_price' => 33000, 'availability' => true],
            ['name' => 'A8 L Quattro', 'brand' => 'Audi', 'model' => 'A8 L 55 TFSI', 'car_type' => 'Sedan', 'year' => 2023, 'daily_rent_price' => 32000, 'availability' => true],
            ['name' => 'Range Rover Autobiography', 'brand' => 'Land Rover', 'model' => 'Range Rover LWB', 'car_type' => 'SUV', 'year' => 2022, 'daily_rent_price' => 40000, 'availability' => true],
            ['name' => 'Cayenne Turbo GT', 'brand' => 'Porsche', 'model' => 'Cayenne Turbo GT', 'car_type' => 'SUV', 'year' => 2023, 'daily_rent_price' => 38000, 'availability' => true],
            ['name' => 'Flying Spur V8', 'brand' => 'Bentley', 'model' => 'Flying Spur', 'car_type' => 'Sedan', 'year' => 2021, 'daily_rent_price' => 45000, 'availability' => true],
            ['name' => 'Rolls-Royce Ghost', 'brand' => 'Rolls-Royce', 'model' => 'Ghost', 'car_type' => 'Sedan', 'year' => 2022, 'daily_rent_price' => 60000, 'availability' => true],
            ['name' => 'Urus Performante', 'brand' => 'Lamborghini', 'model' => 'Urus', 'car_type' => 'SUV', 'year' => 2023, 'daily_rent_price' => 50000, 'availability' => true],
            ['name' => 'Ferrari Roma', 'brand' => 'Ferrari', 'model' => 'Roma', 'car_type' => 'Coupe', 'year' => 2022, 'daily_rent_price' => 55000, 'availability' => true],
            ['name' => 'McLaren GT', 'brand' => 'McLaren', 'model' => 'GT', 'car_type' => 'Coupe', 'year' => 2023, 'daily_rent_price' => 58000, 'availability' => true],
            ['name' => 'E-Class Sedan', 'brand' => 'Mercedes-Benz', 'model' => 'E 350 4MATIC', 'car_type' => 'Sedan', 'year' => 2022, 'daily_rent_price' => 30000, 'availability' => true],
            ['name' => 'GLS SUV', 'brand' => 'Mercedes-Benz', 'model' => 'GLS 450', 'car_type' => 'SUV', 'year' => 2023, 'daily_rent_price' => 38000, 'availability' => true],
            ['name' => '5 Series', 'brand' => 'BMW', 'model' => '530i', 'car_type' => 'Sedan', 'year' => 2023, 'daily_rent_price' => 31000, 'availability' => true],
            ['name' => 'X5 SUV', 'brand' => 'BMW', 'model' => 'xDrive40i', 'car_type' => 'SUV', 'year' => 2022, 'daily_rent_price' => 36000, 'availability' => true],
            ['name' => 'Q7 Luxury SUV', 'brand' => 'Audi', 'model' => 'Q7 55 TFSI', 'car_type' => 'SUV', 'year' => 2023, 'daily_rent_price' => 37000, 'availability' => true],
            ['name' => 'A6 Premium Plus', 'brand' => 'Audi', 'model' => 'A6 45 TFSI', 'car_type' => 'Sedan', 'year' => 2021, 'daily_rent_price' => 28000, 'availability' => true],
            ['name' => 'Defender 110', 'brand' => 'Land Rover', 'model' => 'Defender X', 'car_type' => 'SUV', 'year' => 2023, 'daily_rent_price' => 39000, 'availability' => true],
            ['name' => 'Panamera 4', 'brand' => 'Porsche', 'model' => 'Panamera 4', 'car_type' => 'Sedan', 'year' => 2022, 'daily_rent_price' => 42000, 'availability' => true],
            ['name' => 'Bentayga V8', 'brand' => 'Bentley', 'model' => 'Bentayga', 'car_type' => 'SUV', 'year' => 2022, 'daily_rent_price' => 46000, 'availability' => true],
            ['name' => 'Cullinan Black Badge', 'brand' => 'Rolls-Royce', 'model' => 'Cullinan', 'car_type' => 'SUV', 'year' => 2023, 'daily_rent_price' => 65000, 'availability' => true],
            ['name' => 'Huracan EVO', 'brand' => 'Lamborghini', 'model' => 'Huracan EVO Coupe', 'car_type' => 'Coupe', 'year' => 2023, 'daily_rent_price' => 62000, 'availability' => true],
            ['name' => 'F8 Tributo', 'brand' => 'Ferrari', 'model' => 'F8 Tributo', 'car_type' => 'Coupe', 'year' => 2023, 'daily_rent_price' => 70000, 'availability' => true],
            ['name' => '720S Coupe', 'brand' => 'McLaren', 'model' => '720S', 'car_type' => 'Coupe', 'year' => 2022, 'daily_rent_price' => 68000, 'availability' => true],
            ['name' => 'CLA Coupe', 'brand' => 'Mercedes-Benz', 'model' => 'CLA 250', 'car_type' => 'Coupe', 'year' => 2022, 'daily_rent_price' => 27000, 'availability' => true],
            ['name' => 'X6 M', 'brand' => 'BMW', 'model' => 'X6 M50i', 'car_type' => 'SUV', 'year' => 2023, 'daily_rent_price' => 43000, 'availability' => true],
            ['name' => 'Q8 Sportback', 'brand' => 'Audi', 'model' => 'Q8 55 TFSI', 'car_type' => 'SUV', 'year' => 2023, 'daily_rent_price' => 42000, 'availability' => true],
            ['name' => 'Discovery Sport', 'brand' => 'Land Rover', 'model' => 'Discovery Sport R-Dynamic', 'car_type' => 'SUV', 'year' => 2021, 'daily_rent_price' => 30000, 'availability' => true],
            ['name' => 'Taycan Turbo', 'brand' => 'Porsche', 'model' => 'Taycan Turbo', 'car_type' => 'Sedan', 'year' => 2023, 'daily_rent_price' => 50000, 'availability' => true],
            ['name' => 'Bentley Continental', 'brand' => 'Bentley', 'model' => 'Continental GT', 'car_type' => 'Coupe', 'year' => 2022, 'daily_rent_price' => 61000, 'availability' => true],
            ['name' => 'Rolls-Royce Phantom', 'brand' => 'Rolls-Royce', 'model' => 'Phantom', 'car_type' => 'Sedan', 'year' => 2023, 'daily_rent_price' => 75000, 'availability' => true],
            ['name' => 'Lamborghini Aventador', 'brand' => 'Lamborghini', 'model' => 'Aventador S', 'car_type' => 'Coupe', 'year' => 2021, 'daily_rent_price' => 72000, 'availability' => true],
            ['name' => 'Ferrari SF90', 'brand' => 'Ferrari', 'model' => 'SF90 Stradale', 'car_type' => 'Coupe', 'year' => 2023, 'daily_rent_price' => 80000, 'availability' => true],
            ['name' => 'McLaren Artura', 'brand' => 'McLaren', 'model' => 'Artura', 'car_type' => 'Coupe', 'year' => 2024, 'daily_rent_price' => 69000, 'availability' => true],
            ['name' => 'Mercedes-Maybach GLS', 'brand' => 'Mercedes-Benz', 'model' => 'GLS 600', 'car_type' => 'SUV', 'year' => 2023, 'daily_rent_price' => 62000, 'availability' => true],
            ['name' => 'BMW i7', 'brand' => 'BMW', 'model' => 'i7 xDrive60', 'car_type' => 'Sedan', 'year' => 2024, 'daily_rent_price' => 50000, 'availability' => true],
        ];

        foreach ($cars as $car) {
            Car::create($car);
        }
    }
}
