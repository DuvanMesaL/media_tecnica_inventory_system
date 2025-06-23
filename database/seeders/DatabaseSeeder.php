<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Warehouse;
use App\Models\TechnicalProgram;
use App\Models\Classroom;
use App\Models\Tool;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Create admin user
        User::create([
            'name' => 'System Administrator',
            'email' => 'duvanmesa2415@gmail.com',
            'password' => Hash::make('password'),
            'is_active' => true
        ]);

        // Create warehouses
        $mainWarehouse = Warehouse::create([
            'name' => 'Main Tool Warehouse',
            'code' => 'MTW-001',
            'description' => 'Primary storage facility for all technical tools',
            'location' => 'Building A - Ground Floor',
            'is_active' => true
        ]);

        $electronicsWarehouse = Warehouse::create([
            'name' => 'Electronics Storage',
            'code' => 'ELS-001',
            'description' => 'Specialized storage for electronic components and tools',
            'location' => 'Building B - Room 201',
            'is_active' => true
        ]);

        $mechanicalWarehouse = Warehouse::create([
            'name' => 'Mechanical Workshop Storage',
            'code' => 'MWS-001',
            'description' => 'Storage for mechanical tools and equipment',
            'location' => 'Building D - Workshop Storage',
            'is_active' => true
        ]);

        // Create technical programs
        $electricalProgram = TechnicalProgram::create([
            'name' => 'Electrical Engineering Technology',
            'code' => 'EET-001',
            'description' => 'Technical program focused on electrical systems and installations',
            'is_active' => true
        ]);

        $mechanicalProgram = TechnicalProgram::create([
            'name' => 'Mechanical Engineering Technology',
            'code' => 'MET-001',
            'description' => 'Technical program focused on mechanical systems and manufacturing',
            'is_active' => true
        ]);

        $electronicsProgram = TechnicalProgram::create([
            'name' => 'Electronics Technology',
            'code' => 'ET-001',
            'description' => 'Technical program focused on electronic systems and circuits',
            'is_active' => true
        ]);

        // Create classrooms
        Classroom::create([
            'name' => 'Electrical Lab 1',
            'code' => 'EL-101',
            'technical_program_id' => $electricalProgram->id,
            'capacity' => 25,
            'location' => 'Building C - Room 101',
            'is_active' => true
        ]);

        Classroom::create([
            'name' => 'Electrical Lab 2',
            'code' => 'EL-102',
            'technical_program_id' => $electricalProgram->id,
            'capacity' => 25,
            'location' => 'Building C - Room 102',
            'is_active' => true
        ]);

        Classroom::create([
            'name' => 'Mechanical Workshop',
            'code' => 'MW-201',
            'technical_program_id' => $mechanicalProgram->id,
            'capacity' => 20,
            'location' => 'Building D - Workshop 1',
            'is_active' => true
        ]);

        Classroom::create([
            'name' => 'Electronics Lab',
            'code' => 'EL-301',
            'technical_program_id' => $electronicsProgram->id,
            'capacity' => 30,
            'location' => 'Building E - Room 301',
            'is_active' => true
        ]);

        // Create comprehensive tool inventory
        $tools = [
            // Electrical Tools
            [
                'name' => 'Digital Multimeter',
                'code' => 'DMM-001',
                'description' => 'Professional digital multimeter for electrical measurements',
                'category' => 'Electrical Testing',
                'condition' => 'good',
                'total_quantity' => 15,
                'warehouse_id' => $electronicsWarehouse->id,
                'unit_price' => 89.99
            ],
            [
                'name' => 'Oscilloscope',
                'code' => 'OSC-001',
                'description' => 'Digital storage oscilloscope for waveform analysis',
                'category' => 'Electronic Testing',
                'condition' => 'good',
                'total_quantity' => 8,
                'warehouse_id' => $electronicsWarehouse->id,
                'unit_price' => 450.00
            ],
            [
                'name' => 'Wire Strippers',
                'code' => 'WS-001',
                'description' => 'Professional wire stripping and crimping tool',
                'category' => 'Electrical Tools',
                'condition' => 'good',
                'total_quantity' => 20,
                'warehouse_id' => $electronicsWarehouse->id,
                'unit_price' => 19.99
            ],
            [
                'name' => 'Soldering Iron Kit',
                'code' => 'SI-001',
                'description' => 'Complete soldering iron kit with tips and accessories',
                'category' => 'Electronic Assembly',
                'condition' => 'good',
                'total_quantity' => 12,
                'warehouse_id' => $electronicsWarehouse->id,
                'unit_price' => 35.50
            ],
            [
                'name' => 'Function Generator',
                'code' => 'FG-001',
                'description' => 'Signal generator for testing electronic circuits',
                'category' => 'Electronic Testing',
                'condition' => 'good',
                'total_quantity' => 6,
                'warehouse_id' => $electronicsWarehouse->id,
                'unit_price' => 299.99
            ],

            // Mechanical Tools
            [
                'name' => 'Drill Press',
                'code' => 'DP-001',
                'description' => 'Bench-top drill press for precision drilling',
                'category' => 'Power Tools',
                'condition' => 'good',
                'total_quantity' => 3,
                'warehouse_id' => $mechanicalWarehouse->id,
                'unit_price' => 299.99
            ],
            [
                'name' => 'Micrometer Set',
                'code' => 'MS-001',
                'description' => 'Precision micrometer set for accurate measurements',
                'category' => 'Measuring Tools',
                'condition' => 'good',
                'total_quantity' => 10,
                'warehouse_id' => $mechanicalWarehouse->id,
                'unit_price' => 125.00
            ],
            [
                'name' => 'Lathe Tool Set',
                'code' => 'LTS-001',
                'description' => 'Complete set of lathe cutting tools',
                'category' => 'Machining Tools',
                'condition' => 'good',
                'total_quantity' => 5,
                'warehouse_id' => $mechanicalWarehouse->id,
                'unit_price' => 180.00
            ],
            [
                'name' => 'Torque Wrench',
                'code' => 'TW-001',
                'description' => 'Calibrated torque wrench for precise fastening',
                'category' => 'Hand Tools',
                'condition' => 'good',
                'total_quantity' => 8,
                'warehouse_id' => $mechanicalWarehouse->id,
                'unit_price' => 95.00
            ],

            // General Tools
            [
                'name' => 'Screwdriver Set',
                'code' => 'SDS-001',
                'description' => 'Complete set of Phillips and flathead screwdrivers',
                'category' => 'Hand Tools',
                'condition' => 'good',
                'total_quantity' => 25,
                'warehouse_id' => $mainWarehouse->id,
                'unit_price' => 24.99
            ],
            [
                'name' => 'Safety Glasses',
                'code' => 'SG-001',
                'description' => 'ANSI approved safety glasses',
                'category' => 'Safety Equipment',
                'condition' => 'good',
                'total_quantity' => 50,
                'warehouse_id' => $mainWarehouse->id,
                'unit_price' => 8.99
            ],
            [
                'name' => 'Tool Box',
                'code' => 'TB-001',
                'description' => 'Portable tool box with multiple compartments',
                'category' => 'Storage',
                'condition' => 'good',
                'total_quantity' => 15,
                'warehouse_id' => $mainWarehouse->id,
                'unit_price' => 45.00
            ],

            // Some tools with low stock for testing
            [
                'name' => 'Precision Calipers',
                'code' => 'PC-001',
                'description' => 'Digital precision calipers',
                'category' => 'Measuring Tools',
                'condition' => 'good',
                'total_quantity' => 3,
                'warehouse_id' => $mechanicalWarehouse->id,
                'unit_price' => 65.00
            ],
            [
                'name' => 'Logic Analyzer',
                'code' => 'LA-001',
                'description' => 'Digital logic analyzer for circuit debugging',
                'category' => 'Electronic Testing',
                'condition' => 'good',
                'total_quantity' => 2,
                'warehouse_id' => $electronicsWarehouse->id,
                'unit_price' => 350.00
            ],
        ];

        foreach ($tools as $toolData) {
            $toolData['available_quantity'] = $toolData['total_quantity'];
            Tool::create($toolData);
        }

        // Create sample users
        User::create([
            'name' => 'John Smith',
            'email' => 'john.smith@school.edu',
            'password' => Hash::make('password'),
            'technical_program_id' => $electricalProgram->id,
            'is_active' => true
        ]);

        User::create([
            'name' => 'Maria Garcia',
            'email' => 'maria.garcia@school.edu',
            'password' => Hash::make('password'),
            'technical_program_id' => $mechanicalProgram->id,
            'is_active' => true
        ]);

        User::create([
            'name' => 'David Chen',
            'email' => 'david.chen@school.edu',
            'password' => Hash::make('password'),
            'technical_program_id' => $electronicsProgram->id,
            'is_active' => true
        ]);

        User::create([
            'name' => 'Robert Johnson',
            'email' => 'robert.johnson@school.edu',
            'password' => Hash::make('password'),
            'is_active' => true
        ]);

        User::create([
            'name' => 'Sarah Wilson',
            'email' => 'sarah.wilson@school.edu',
            'password' => Hash::make('password'),
            'is_active' => true
        ]);

        // Run role and permission seeder
        $this->call(RolePermissionSeeder::class);
    }
}
