<?php

namespace Database\Seeders;

use App\Models\TeamMember;
use Illuminate\Database\Seeder;

class TeamMemberSeeder extends Seeder
{
    /**
     * Transcribed verbatim from the legacy index.html testimonial-item cards.
     */
    public function run(): void
    {
        $lecturerQuote = 'Lecturers of Pignatelli Triputra University, Faculty of Science and Technology, Information Systems Study Program.';
        $studentQuote4th = 'Students of Pignatelli Triputra University, Faculty of Science and Technology, Information Systems Study Program, 4th Semester.';
        $studentQuote6th = 'Students of Pignatelli Triputra University, Faculty of Science and Technology, Information Systems Study Program, 6th Semester.';

        $members = [
            ['name' => 'Maria Atik Sunarti Ekowati, ST.,M.Kom', 'role' => 'Senior Lecturer', 'quote' => $lecturerQuote, 'photo_path' => 'home/team-1.png'],
            ['name' => 'Unggul Widya Saputra', 'role' => 'Leader', 'quote' => $studentQuote4th, 'photo_path' => 'home/team-2.png'],
            ['name' => 'Gabriel Silvano', 'role' => 'Member', 'quote' => $studentQuote4th, 'photo_path' => 'home/team-3.png'],
            ['name' => 'Evan Valentino', 'role' => 'Member', 'quote' => $studentQuote4th, 'photo_path' => 'home/team-4.png'],
            ['name' => 'Zadrak Yaboisembut', 'role' => 'Member', 'quote' => $studentQuote4th, 'photo_path' => 'home/team-5.png'],
            ['name' => 'Benedikta Cindy Permatasari', 'role' => 'Member', 'quote' => $studentQuote6th, 'photo_path' => 'home/team-6.png'],
        ];

        foreach ($members as $i => $member) {
            TeamMember::updateOrCreate(
                ['name' => $member['name']],
                $member + ['sort_order' => $i]
            );
        }
    }
}
