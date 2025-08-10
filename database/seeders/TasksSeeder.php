<?php

namespace Database\Seeders;

use App\Models\Tasks;
use Illuminate\Database\Seeder;

class TasksSeeder extends Seeder
{
    public function run(): void
    {
        Tasks::create([
            'title' => 'Сверстать шапку сайта',
            'description' => 'Сделать верстку шапки сайта + меню + форму поиска',
            'creator_id' => 1, // Test user 1
            'executor_id' => 2, // Test user 2
            'end_date' => now()->addDays(5),
            'is_done' => false
        ])->save();

        Tasks::create([
            'title' => 'Сделать бэкэнд поиска',
            'description' => 'Сделать обработчик формы поиска + написать запрос для корректного вывода результатов поиска на странице. Сделать пагинацию.',
            'creator_id' => 1, // Test user 1
            'executor_id' => 2, // Test user 2
            'end_date' => now()->addDays(5),
            'is_done' => false
        ])->save();
    }
}
