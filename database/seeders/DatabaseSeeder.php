<?php

namespace Database\Seeders;

use App\Models\Mission;
use App\Models\Module;
use App\Models\Strand;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // ── Admin user ───────────────────────────────────────────────
        User::create([
            'name'                  => 'Admin SkillCraft',
            'first_name'            => 'Admin',
            'last_name'             => 'SkillCraft',
            'email'                 => 'admin@skillcraft.com',
            'username'              => 'admin',
            'password'              => Hash::make('password123'),
            'password_hash'         => Hash::make('password123'),
            'confirm_password_hash' => Hash::make('password123'),
            'role'                  => 'admin',
        ]);

        // ── Strands ──────────────────────────────────────────────────
        $ict = Strand::create([
            'strand_name' => 'ICT',
            'description' => 'Information and Communications Technology strand covering computer hardware, software, and networking.',
        ]);

        $cookery = Strand::create([
            'strand_name' => 'Cookery',
            'description' => 'Cookery strand covering food preparation, kitchen safety, and proper cooking techniques.',
        ]);

        $smaw = Strand::create([
            'strand_name' => 'SMAW',
            'description' => 'Shielded Metal Arc Welding strand covering fabrication, welding techniques, and equipment maintenance.',
        ]);

        // ── ICT Modules & Missions ───────────────────────────────────
        $ictMod1 = Module::create([
            'strand_id'       => $ict->strand_id,
            'module_name'     => 'Computer Hardware Servicing',
            'competency_area' => 'Computer Repair and Troubleshooting',
        ]);

        Mission::create([
            'module_id'            => $ictMod1->module_id,
            'mission_title'        => 'Diagnose the Faulty PC',
            'scenario_description' => 'A customer reports that the PC does not boot. Identify and replace the faulty component.',
            'max_score'            => 100,
            'difficulty_level'     => 1,
        ]);

        Mission::create([
            'module_id'            => $ictMod1->module_id,
            'mission_title'        => 'Install an Operating System',
            'scenario_description' => 'Perform a clean installation of Windows 10 on a newly assembled PC following proper procedures.',
            'max_score'            => 100,
            'difficulty_level'     => 2,
        ]);

        $ictMod2 = Module::create([
            'strand_id'       => $ict->strand_id,
            'module_name'     => 'Network Configuration',
            'competency_area' => 'System Configuration and Networking',
        ]);

        Mission::create([
            'module_id'            => $ictMod2->module_id,
            'mission_title'        => 'Configure a LAN Network',
            'scenario_description' => 'Set up a small LAN network connecting 5 computers using a switch and proper IP addressing.',
            'max_score'            => 100,
            'difficulty_level'     => 3,
        ]);

        // ── Cookery Modules & Missions ───────────────────────────────
        $cookeryMod1 = Module::create([
            'strand_id'       => $cookery->strand_id,
            'module_name'     => 'Food and Beverage Services',
            'competency_area' => 'Food Preparation and Kitchen Safety',
        ]);

        Mission::create([
            'module_id'            => $cookeryMod1->module_id,
            'mission_title'        => 'Prepare Chicken Adobo',
            'scenario_description' => 'Follow the standard recipe to prepare Chicken Adobo, observing proper food safety and kitchen sanitation.',
            'max_score'            => 100,
            'difficulty_level'     => 1,
        ]);

        Mission::create([
            'module_id'            => $cookeryMod1->module_id,
            'mission_title'        => 'Set Up a Dining Table',
            'scenario_description' => 'Arrange a formal dining table setup following standard table service procedures.',
            'max_score'            => 100,
            'difficulty_level'     => 2,
        ]);

        $cookeryMod2 = Module::create([
            'strand_id'       => $cookery->strand_id,
            'module_name'     => 'Bread and Pastry Production',
            'competency_area' => 'Baking and Pastry Preparation',
        ]);

        Mission::create([
            'module_id'            => $cookeryMod2->module_id,
            'mission_title'        => 'Bake Pandesal',
            'scenario_description' => 'Prepare and bake a standard batch of Pandesal following proper measurements and baking procedures.',
            'max_score'            => 100,
            'difficulty_level'     => 1,
        ]);

        Mission::create([
            'module_id'            => $cookeryMod2->module_id,
            'mission_title'        => 'Decorate a Birthday Cake',
            'scenario_description' => 'Apply frosting and decorative techniques on a birthday cake following design specifications.',
            'max_score'            => 100,
            'difficulty_level'     => 2,
        ]);

        // ── SMAW Modules & Missions ──────────────────────────────────
        $smawMod1 = Module::create([
            'strand_id'       => $smaw->strand_id,
            'module_name'     => 'Shielded Metal Arc Welding',
            'competency_area' => 'Basic Welding Techniques',
        ]);

        Mission::create([
            'module_id'            => $smawMod1->module_id,
            'mission_title'        => 'Perform a Butt Weld',
            'scenario_description' => 'Complete a standard butt weld joint on mild steel plates using SMAW technique.',
            'max_score'            => 100,
            'difficulty_level'     => 1,
        ]);

        Mission::create([
            'module_id'            => $smawMod1->module_id,
            'mission_title'        => 'Perform a Fillet Weld',
            'scenario_description' => 'Execute a fillet weld on two steel plates positioned at a 90-degree angle using correct electrode movement.',
            'max_score'            => 100,
            'difficulty_level'     => 2,
        ]);

        $smawMod2 = Module::create([
            'strand_id'       => $smaw->strand_id,
            'module_name'     => 'Welding Safety and Equipment',
            'competency_area' => 'Safety Procedures and Equipment Handling',
        ]);

        Mission::create([
            'module_id'            => $smawMod2->module_id,
            'mission_title'        => 'Identify Welding Hazards',
            'scenario_description' => 'Inspect a welding workstation and identify all safety hazards, then apply proper corrective measures.',
            'max_score'            => 100,
            'difficulty_level'     => 1,
        ]);

        Mission::create([
            'module_id'            => $smawMod2->module_id,
            'mission_title'        => 'Set Up Welding Equipment',
            'scenario_description' => 'Properly assemble and set up all SMAW welding equipment including the electrode holder, ground clamp, and power source.',
            'max_score'            => 100,
            'difficulty_level'     => 3,
        ]);
    }
}
